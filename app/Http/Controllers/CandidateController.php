<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Dusun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CandidateController extends Controller
{
    public function index()
    {
        $candidates = Candidate::query()
            ->with('dusuns')
            ->orderBy('number')
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
                'unique:candidates,number',
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
                'nullable',
                'array',
            ],
            'dusun_ids.*' => [
                'integer',
                'exists:dusuns,id',
            ],
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')
                ->store('candidates', 'public');
        }

        $dusunIds = $validated['dusun_ids'] ?? [];

        unset($validated['dusun_ids']);

        $candidate = Candidate::create($validated);

        $candidate->dusuns()->sync($dusunIds);

        return redirect()
            ->route('candidates.index')
            ->with('success', 'Data calon berhasil ditambahkan.');
    }

    public function show(Candidate $candidate)
    {
        $candidate->load('dusuns');

        return view('candidates.show', compact('candidate'));
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
                'unique:candidates,number,' . $candidate->id,
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
                'nullable',
                'array',
            ],
            'dusun_ids.*' => [
                'integer',
                'exists:dusuns,id',
            ],
        ]);

        if ($request->hasFile('photo')) {
            if ($candidate->photo) {
                Storage::disk('public')
                    ->delete($candidate->photo);
            }

            $validated['photo'] = $request->file('photo')
                ->store('candidates', 'public');
        }

        $dusunIds = $validated['dusun_ids'] ?? [];

        unset($validated['dusun_ids']);

        $candidate->update($validated);

        $candidate->dusuns()->sync($dusunIds);

        return redirect()
            ->route('candidates.index')
            ->with('success', 'Data calon berhasil diperbarui.');
    }

    public function destroy(Candidate $candidate)
    {
        if ($candidate->photo) {
            Storage::disk('public')
                ->delete($candidate->photo);
        }

        $candidate->dusuns()->detach();

        $candidate->delete();

        return redirect()
            ->route('candidates.index')
            ->with('success', 'Data calon berhasil dihapus.');
    }
}