<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Dusun;
use App\Services\AutoElectionGroupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class CandidateController extends Controller
{
    public function __construct(
        private AutoElectionGroupService $groupService
    ) {
    }

    public function index()
    {
        $candidates = Candidate::query()
            ->with('dusuns')
            ->orderBy('number')
            ->orderBy('name')
            ->get();

        return view('candidates.index', compact('candidates'));
    }

    public function create()
    {
        $dusuns = Dusun::query()
            ->orderBy('name')
            ->get();

        return view('candidates.create', compact('dusuns'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'number' => [
                'required',
                'integer',
                'min:1',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],
            'vision' => [
                'nullable',
                'string',
            ],
            'mission' => [
                'nullable',
                'string',
            ],
            'dusun_ids' => [
                'required',
                'array',
                'min:1',
            ],
            'dusun_ids.*' => [
                'integer',
                'distinct',
                'exists:dusuns,id',
            ],
        ], [
            'number.required' => 'Nomor urut wajib diisi.',
            'number.integer' => 'Nomor urut harus berupa angka.',
            'number.min' => 'Nomor urut minimal 1.',

            'name.required' => 'Nama kandidat wajib diisi.',

            'photo.image' => 'File foto harus berupa gambar.',
            'photo.mimes' => 'Foto harus berformat JPG, JPEG, atau PNG.',
            'photo.max' => 'Ukuran foto maksimal 2 MB.',

            'dusun_ids.required' => 'Pilih minimal satu dusun.',
            'dusun_ids.array' => 'Data dusun tidak valid.',
            'dusun_ids.min' => 'Pilih minimal satu dusun.',
            'dusun_ids.*.exists' => 'Dusun yang dipilih tidak ditemukan.',
        ]);

        $dusunIds = array_map(
            'intval',
            $validated['dusun_ids']
        );

        $this->validateCandidateNumberByDusun(
            (int) $validated['number'],
            $dusunIds
        );

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request
                ->file('photo')
                ->store('candidates', 'public');
        }

        unset($validated['dusun_ids']);

        $candidate = Candidate::create($validated);

        $candidate->dusuns()->sync($dusunIds);

        $this->groupService->sync();

        return redirect()
            ->route('candidates.index')
            ->with(
                'success',
                'Data calon berhasil ditambahkan.'
            );
    }

    public function show(Candidate $candidate)
    {
        $candidate->load('dusuns');

        return view(
            'candidates.show',
            compact('candidate')
        );
    }

    public function edit(Candidate $candidate)
    {
        $candidate->load('dusuns');

        $dusuns = Dusun::query()
            ->orderBy('name')
            ->get();

        return view(
            'candidates.edit',
            compact('candidate', 'dusuns')
        );
    }

    public function update(
        Request $request,
        Candidate $candidate
    ) {
        $validated = $request->validate([
            'number' => [
                'required',
                'integer',
                'min:1',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:10240',
            ],
            'vision' => [
                'nullable',
                'string',
            ],
            'mission' => [
                'nullable',
                'string',
            ],
            'dusun_ids' => [
                'required',
                'array',
                'min:1',
            ],
            'dusun_ids.*' => [
                'integer',
                'distinct',
                'exists:dusuns,id',
            ],
        ], [
            'number.required' => 'Nomor urut wajib diisi.',
            'number.integer' => 'Nomor urut harus berupa angka.',
            'number.min' => 'Nomor urut minimal 1.',

            'name.required' => 'Nama kandidat wajib diisi.',

            'photo.image' => 'File foto harus berupa gambar.',
            'photo.mimes' => 'Foto harus berformat JPG, JPEG, atau PNG.',
            'photo.max' => 'Ukuran foto maksimal 2 MB.',

            'dusun_ids.required' => 'Pilih minimal satu dusun.',
            'dusun_ids.array' => 'Data dusun tidak valid.',
            'dusun_ids.min' => 'Pilih minimal satu dusun.',
            'dusun_ids.*.exists' => 'Dusun yang dipilih tidak ditemukan.',
        ]);

        $dusunIds = array_map(
            'intval',
            $validated['dusun_ids']
        );

        $this->validateCandidateNumberByDusun(
            (int) $validated['number'],
            $dusunIds,
            $candidate->id
        );

        if ($request->hasFile('photo')) {
            if ($candidate->photo) {
                Storage::disk('public')
                    ->delete($candidate->photo);
            }

            $validated['photo'] = $request
                ->file('photo')
                ->store('candidates', 'public');
        }

        unset($validated['dusun_ids']);

        $candidate->update($validated);

        $candidate->dusuns()->sync($dusunIds);

        $this->groupService->sync();

        return redirect()
            ->route('candidates.index')
            ->with(
                'success',
                'Data calon berhasil diperbarui.'
            );
    }

    public function destroy(Candidate $candidate)
    {
        if ($candidate->photo) {
            Storage::disk('public')
                ->delete($candidate->photo);
        }

        $candidate->dusuns()->detach();

        $candidate->delete();

        $this->groupService->sync();

        return redirect()
            ->route('candidates.index')
            ->with(
                'success',
                'Data calon berhasil dihapus.'
            );
    }

    private function validateCandidateNumberByDusun(
        int $number,
        array $dusunIds,
        ?int $ignoredCandidateId = null
    ): void {
        $conflictingCandidate = Candidate::query()
            ->with('dusuns')
            ->where('number', $number)
            ->when(
                $ignoredCandidateId,
                function ($query) use ($ignoredCandidateId) {
                    $query->where(
                        'id',
                        '!=',
                        $ignoredCandidateId
                    );
                }
            )
            ->whereHas(
                'dusuns',
                function ($query) use ($dusunIds) {
                    $query->whereIn(
                        'dusuns.id',
                        $dusunIds
                    );
                }
            )
            ->first();

        if (!$conflictingCandidate) {
            return;
        }

        $conflictingDusunNames = $conflictingCandidate
            ->dusuns
            ->whereIn('id', $dusunIds)
            ->pluck('name')
            ->implode(', ');

        throw ValidationException::withMessages([
            'number' =>
                'Nomor urut ' .
                $number .
                ' sudah digunakan pada dusun: ' .
                $conflictingDusunNames .
                '.',
        ]);
    }
}