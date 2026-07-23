<?php

namespace App\Http\Controllers;

use App\Models\Voter;
use Illuminate\Http\Request;

class VoterController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $voters = Voter::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('nik', 'like', '%' . $search . '%')
                    ->orWhere('dpt_number', 'like', '%' . $search . '%')
                    ->orWhere('address', 'like', '%' . $search . '%');
            })
            ->orderBy('dpt_number')
            ->paginate(10)
            ->withQueryString();

        return view('voters.index', compact('voters', 'search'));
    }

    public function create()
    {
        return view('voters.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dpt_number' => ['required', 'string', 'max:50', 'unique:voters,dpt_number'],
            'nik' => ['required', 'string', 'max:20', 'unique:voters,nik'],
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
        ]);

        Voter::create($validated);

        return redirect()
            ->route('voters.index')
            ->with('success', 'Data DPT berhasil ditambahkan.');
    }

    public function show(Voter $voter)
    {
        return view('voters.show', compact('voter'));
    }

    public function edit(Voter $voter)
    {
        return view('voters.edit', compact('voter'));
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
                'string',
                'max:20',
                'unique:voters,nik,' . $voter->id,
            ],
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
        ]);

        $voter->update($validated);

        return redirect()
            ->route('voters.index')
            ->with('success', 'Data DPT berhasil diperbarui.');
    }

    public function destroy(Voter $voter)
    {
        $voter->delete();

        return redirect()
            ->route('voters.index')
            ->with('success', 'Data DPT berhasil dihapus.');
    }
}