<?php

namespace App\Http\Controllers;

use App\Models\Dusun;
use App\Models\Voter;
use Illuminate\Http\Request;

class VoterController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search'));

        $voters = Voter::query()
            ->with('dusun')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', '%' . $search . '%')
                        ->orWhere('nik', 'like', '%' . $search . '%')
                        ->orWhere('dpt_number', 'like', '%' . $search . '%')
                        ->orWhere('address', 'like', '%' . $search . '%')
                        ->orWhereHas('dusun', function ($dusunQuery) use ($search) {
                            $dusunQuery->where(
                                'name',
                                'like',
                                '%' . $search . '%'
                            );
                        });
                });
            })
            ->orderBy('dpt_number')
            ->paginate(10)
            ->withQueryString();

        return view('voters.index', compact('voters', 'search'));
    }

    public function create()
    {
        $dusuns = Dusun::query()
            ->orderBy('name')
            ->get();

        return view('voters.create', compact('dusuns'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dpt_number' => [
                'required',
                'string',
                'max:50',
                'unique:voters,dpt_number',
            ],
            'nik' => [
                'required',
                'digits:16',
                'unique:voters,nik',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'dusun_id' => [
                'required',
                'exists:dusuns,id',
            ],
            'address' => [
                'nullable',
                'string',
            ],
        ], [
            'dpt_number.required' => 'Nomor DPT wajib diisi.',
            'dpt_number.unique' => 'Nomor DPT sudah digunakan.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus terdiri dari 16 digit angka.',
            'nik.unique' => 'NIK sudah terdaftar.',
            'name.required' => 'Nama pemilih wajib diisi.',
            'dusun_id.required' => 'Dusun wajib dipilih.',
            'dusun_id.exists' => 'Dusun yang dipilih tidak valid.',
            'address' => 'Misal: RT 01/RW 01, Desa Barurejo',
        ]);

        Voter::create($validated);

        return redirect()
            ->route('voters.index')
            ->with('success', 'Data DPT berhasil ditambahkan.');
    }

    public function show(Voter $voter)
    {
        $voter->load('dusun');

        return view('voters.show', compact('voter'));
    }

    public function edit(Voter $voter)
    {
        $dusuns = Dusun::query()
            ->orderBy('name')
            ->get();

        return view('voters.edit', compact('voter', 'dusuns'));
    }

    public function update(Request $request, Voter $voter)
    {
        $validated = $request->validate([
            'dpt_number' => [
                'required',
                'string',
                'max:50',
                'unique:voters,dpt_number,' . $voter->id,
            ],
            'nik' => [
                'required',
                'digits:16',
                'unique:voters,nik,' . $voter->id,
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'dusun_id' => [
                'required',
                'exists:dusuns,id',
            ],
            'address' => [
                'nullable',
                'string',
            ],
        ], [
            'dpt_number.required' => 'Nomor DPT wajib diisi.',
            'dpt_number.unique' => 'Nomor DPT sudah digunakan.',
            'nik.required' => 'NIK wajib diisi.',
            'nik.digits' => 'NIK harus terdiri dari 16 digit angka.',
            'nik.unique' => 'NIK sudah terdaftar.',
            'name.required' => 'Nama pemilih wajib diisi.',
            'dusun_id.required' => 'Dusun wajib dipilih.',
            'dusun_id.exists' => 'Dusun yang dipilih tidak valid.',
            'address' => ['nullable', 'string'],
        ]);

        $voter->update($validated);

        return redirect()
            ->route('voters.index')
            ->with('success', 'Data DPT berhasil diperbarui.');
    }

    public function destroy(Voter $voter)
    {
        if ($voter->has_voted) {
            return back()->with(
                'error',
                'Data DPT yang sudah memilih tidak dapat dihapus.'
            );
        }

        $voter->delete();

        return redirect()
            ->route('voters.index')
            ->with('success', 'Data DPT berhasil dihapus.');
    }
}