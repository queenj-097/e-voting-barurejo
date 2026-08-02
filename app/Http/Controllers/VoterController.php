<?php

namespace App\Http\Controllers;

use App\Models\Dusun;
use App\Models\Voter;
use App\Services\AutoElectionGroupService;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VoterController extends Controller
{
    public function __construct(
        private AutoElectionGroupService $groupService
    ) {
    }

    /**
     * Menampilkan daftar DPT.
     *
     * Tanpa pencarian:
     * data dikelompokkan berdasarkan Dusun -> RW -> RT.
     *
     * Dengan pencarian:
     * data ditampilkan sebagai hasil pencarian dengan pagination.
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search', ''));

        /*
         * Mode pencarian.
         */
        if ($search !== '') {
            $voters = Voter::query()
                ->with([
                    'dusun',
                    'electionGroup',
                ])
                ->where(function ($query) use ($search) {
                    $query
                        ->where(
                            'voter_code',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'name',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'nik',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'gender',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'rw',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhere(
                            'rt',
                            'like',
                            '%' . $search . '%'
                        )
                        ->orWhereHas(
                            'dusun',
                            function ($dusunQuery) use ($search) {
                                $dusunQuery
                                    ->where(
                                        'name',
                                        'like',
                                        '%' . $search . '%'
                                    )
                                    ->orWhere(
                                        'code',
                                        'like',
                                        '%' . $search . '%'
                                    );
                            }
                        );
                })
                ->orderBy('dusun_id')
                ->orderBy('rw')
                ->orderBy('rt')
                ->orderBy('voter_code')
                ->paginate(20)
                ->withQueryString();

            return view('voters.index', [
                'search' => $search,
                'voters' => $voters,
                'groupedVoters' => null,
                'totalVoters' => Voter::query()->count(),
            ]);
        }

        /*
         * Mode folder Dusun -> RW -> RT.
         */
        $allVoters = Voter::query()
            ->with([
                'dusun',
                'electionGroup',
            ])
            ->orderBy('dusun_id')
            ->orderBy('rw')
            ->orderBy('rt')
            ->orderBy('voter_code')
            ->get();

        $groupedVoters = $allVoters
            ->groupBy(function (Voter $voter) {
                return $voter->dusun?->name
                    ?? 'Tanpa Dusun';
            })
            ->map(function ($dusunVoters) {
                return $dusunVoters
                    ->groupBy(function (Voter $voter) {
                        return $voter->rw ?: 'Tanpa RW';
                    })
                    ->map(function ($rwVoters) {
                        return $rwVoters
                            ->groupBy(function (Voter $voter) {
                                return $voter->rt ?: 'Tanpa RT';
                            })
                            ->sortKeys();
                    })
                    ->sortKeys();
            })
            ->sortKeys();

        return view('voters.index', [
            'search' => '',
            'voters' => null,
            'groupedVoters' => $groupedVoters,
            'totalVoters' => $allVoters->count(),
        ]);
    }

    /**
     * Menampilkan form tambah DPT.
     */
    public function create(): View
    {
        $dusuns = Dusun::query()
            ->orderBy('name')
            ->get();

        return view('voters.create', compact('dusuns'));
    }

    /**
     * Menyimpan DPT baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateVoter($request);

        $validated['rw'] = $this->normalizeAreaNumber(
            $validated['rw']
        );

        $validated['rt'] = $this->normalizeAreaNumber(
            $validated['rt']
        );

        $validated['nik'] = $this->normalizeNik(
            $validated['nik'] ?? null
        );

        $validated['gender'] = strtoupper(
            $validated['gender']
        );

        DB::transaction(function () use ($validated) {
            $dusun = Dusun::query()
                ->lockForUpdate()
                ->findOrFail($validated['dusun_id']);

            $voterCode = $this->generateVoterCode(
                $dusun,
                $validated['rw'],
                $validated['rt']
            );

            Voter::create([
                'voter_code' => $voterCode,
                'name' => $validated['name'],
                'gender' => $validated['gender'],
                'dusun_id' => $validated['dusun_id'],
                'rw' => $validated['rw'],
                'rt' => $validated['rt'],
                'nik' => $validated['nik'],
                'has_voted' => false,
                'voted_at' => null,
            ]);
        });

        $this->groupService->sync();

        return redirect()
            ->route('voters.index')
            ->with(
                'success',
                'Data DPT berhasil ditambahkan.'
            );
    }

    /**
     * Menampilkan detail DPT.
     */
    public function show(Voter $voter): View
    {
        $voter->load([
            'dusun',
            'electionGroup',
        ]);

        return view(
            'voters.show',
            compact('voter')
        );
    }

    /**
     * Menampilkan form edit DPT.
     */
    public function edit(Voter $voter): View
    {
        $dusuns = Dusun::query()
            ->orderBy('name')
            ->get();

        return view(
            'voters.edit',
            compact('voter', 'dusuns')
        );
    }

    /**
     * Memperbarui data DPT.
     */
    public function update(
        Request $request,
        Voter $voter
    ): RedirectResponse {
        $validated = $this->validateVoter(
            $request,
            $voter
        );

        $validated['rw'] = $this->normalizeAreaNumber(
            $validated['rw']
        );

        $validated['rt'] = $this->normalizeAreaNumber(
            $validated['rt']
        );

        $validated['nik'] = $this->normalizeNik(
            $validated['nik'] ?? null
        );

        $validated['gender'] = strtoupper(
            $validated['gender']
        );

        DB::transaction(function () use (
            $validated,
            $voter
        ) {
            $locationChanged =
                (int) $voter->dusun_id
                    !== (int) $validated['dusun_id']
                || $voter->rw !== $validated['rw']
                || $voter->rt !== $validated['rt'];

            $voterCode = $voter->voter_code;

            /*
             * Jika dusun, RW, atau RT berubah,
             * ID DPT dibuat ulang berdasarkan lokasi baru.
             */
            if ($locationChanged) {
                $dusun = Dusun::query()
                    ->lockForUpdate()
                    ->findOrFail(
                        $validated['dusun_id']
                    );

                $voterCode = $this->generateVoterCode(
                    $dusun,
                    $validated['rw'],
                    $validated['rt'],
                    $voter->id
                );
            }

            $voter->update([
                'voter_code' => $voterCode,
                'name' => $validated['name'],
                'gender' => $validated['gender'],
                'dusun_id' => $validated['dusun_id'],
                'rw' => $validated['rw'],
                'rt' => $validated['rt'],
                'nik' => $validated['nik'],
            ]);
        });

        $this->groupService->sync();

        return redirect()
            ->route('voters.index')
            ->with(
                'success',
                'Data DPT berhasil diperbarui.'
            );
    }

    /**
     * Menghapus satu data DPT.
     */
    public function destroy(
        Voter $voter
    ): RedirectResponse {
        if ($voter->has_voted) {
            return back()->with(
                'error',
                'Data DPT yang sudah memilih tidak dapat dihapus.'
            );
        }

        $voter->delete();

        $this->groupService->sync();

        return redirect()
            ->route('voters.index')
            ->with(
                'success',
                'Data DPT berhasil dihapus.'
            );
    }

    /**
     * Validasi data DPT untuk tambah dan edit.
     */
    private function validateVoter(
        Request $request,
        ?Voter $voter = null
    ): array {
        return $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'gender' => [
                'required',
                Rule::in(['L', 'P']),
            ],

            'dusun_id' => [
                'required',
                'integer',
                'exists:dusuns,id',
            ],

            'rw' => [
                'required',
                'integer',
                'min:1',
                'max:999',
            ],

            'rt' => [
                'required',
                'integer',
                'min:1',
                'max:999',
            ],

            'nik' => [
                'nullable',
                'digits:16',
                Rule::unique(
                    'voters',
                    'nik'
                )->ignore($voter?->id),
            ],
        ], [
            'name.required' =>
                'Nama pemilih wajib diisi.',

            'name.max' =>
                'Nama pemilih maksimal 255 karakter.',

            'gender.required' =>
                'Jenis kelamin wajib dipilih.',

            'gender.in' =>
                'Jenis kelamin tidak valid.',

            'dusun_id.required' =>
                'Dusun wajib dipilih.',

            'dusun_id.exists' =>
                'Dusun yang dipilih tidak valid.',

            'rw.required' =>
                'RW wajib diisi.',

            'rw.integer' =>
                'RW harus berupa angka.',

            'rw.min' =>
                'RW minimal bernilai 1.',

            'rw.max' =>
                'Nomor RW terlalu besar.',

            'rt.required' =>
                'RT wajib diisi.',

            'rt.integer' =>
                'RT harus berupa angka.',

            'rt.min' =>
                'RT minimal bernilai 1.',

            'rt.max' =>
                'Nomor RT terlalu besar.',

            'nik.digits' =>
                'NIK harus terdiri dari 16 digit angka.',

            'nik.unique' =>
                'NIK tersebut sudah terdaftar.',
        ]);
    }

    /**
     * Mengubah RW/RT menjadi format minimal dua digit.
     *
     * Contoh:
     * 1 menjadi 01
     * 12 tetap 12
     */
    private function normalizeAreaNumber(
        string|int $number
    ): string {
        return str_pad(
            (string) ((int) $number),
            2,
            '0',
            STR_PAD_LEFT
        );
    }

    /**
     * Mengubah NIK kosong atau tanda "-" menjadi null.
     */
    private function normalizeNik(
        ?string $nik
    ): ?string {
        if ($nik === null) {
            return null;
        }

        $nik = trim($nik);

        if ($nik === '' || $nik === '-') {
            return null;
        }

        return $nik;
    }

    /**
     * Membuat ID DPT berdasarkan:
     * KODE DUSUN - RW - RT - NOMOR URUT
     *
     * Contoh:
     * KRJ-01-01-001
     */
    private function generateVoterCode(
        Dusun $dusun,
        string $rw,
        string $rt,
        ?int $exceptVoterId = null
    ): string {
        $prefix =
            strtoupper(trim($dusun->code))
            . '-' . $rw
            . '-' . $rt
            . '-';

        $lastVoterQuery = Voter::query()
            ->where(
                'dusun_id',
                $dusun->id
            )
            ->where('rw', $rw)
            ->where('rt', $rt);

        if ($exceptVoterId !== null) {
            $lastVoterQuery->where(
                'id',
                '!=',
                $exceptVoterId
            );
        }

        $lastVoter = $lastVoterQuery
            ->orderByDesc('voter_code')
            ->lockForUpdate()
            ->first();

        $nextSequence = 1;

        if ($lastVoter !== null) {
            $lastSequence = (int) substr(
                $lastVoter->voter_code,
                strrpos(
                    $lastVoter->voter_code,
                    '-'
                ) + 1
            );

            $nextSequence = $lastSequence + 1;
        }

        do {
            $voterCode =
                $prefix
                . str_pad(
                    (string) $nextSequence,
                    3,
                    '0',
                    STR_PAD_LEFT
                );

            $alreadyExists = Voter::query()
                ->where(
                    'voter_code',
                    $voterCode
                )
                ->when(
                    $exceptVoterId !== null,
                    fn ($query) => $query->where(
                        'id',
                        '!=',
                        $exceptVoterId
                    )
                )
                ->exists();

            if ($alreadyExists) {
                $nextSequence++;
            }
        } while ($alreadyExists);

        return $voterCode;
    }

    /**
     * Menampilkan kartu QR pemilih.
     */
    public function qr(Voter $voter): View
    {
        $voter->load('dusun');

        return view(
            'voters.qr',
            compact('voter')
        );
    }

    /**
     * Menghasilkan gambar QR pemilih.
     */
    public function qrImage(Voter $voter)
    {
        $writer = new PngWriter();

        $qrCode = new QrCode(
            data: $voter->voter_code,
            encoding: new Encoding('ISO-8859-1'),
            errorCorrectionLevel:
                ErrorCorrectionLevel::Medium,
            size: 900,
            margin: 15,
            roundBlockSizeMode:
                RoundBlockSizeMode::Margin
        );

        $result = $writer->write($qrCode);

        return response(
            $result->getString()
        )
            ->header(
                'Content-Type',
                $result->getMimeType()
            )
            ->header(
                'Content-Disposition',
                'inline; filename="QR-'
                    . $voter->voter_code
                    . '.png"'
            );
    }

    /**
     * Menampilkan seluruh QR untuk dicetak.
     */
    public function printAllQr(): View
    {
        $voters = Voter::query()
            ->with('dusun')
            ->orderBy('dusun_id')
            ->orderBy('rw')
            ->orderBy('rt')
            ->orderBy('voter_code')
            ->get();

        return view(
            'voters.qr-print-all',
            compact('voters')
        );
    }

    /**
     * Menghapus seluruh data DPT.
     */
    public function destroyAll(): RedirectResponse
    {
        /*
         * Melepaskan pemilih dari bilik terlebih dahulu,
         * supaya tidak tertahan foreign key.
         */
        DB::table('booths')->update([
            'status' => 'idle',
            'current_voter_id' => null,
            'assigned_at' => null,
            'voting_started_at' => null,
        ]);

        Voter::query()->delete();

        $this->groupService->sync();

        return redirect()
            ->route('voters.index')
            ->with(
                'success',
                'Seluruh data DPT berhasil dihapus.'
            );
    }
}