<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CandidateController extends Controller
{
    public function index()
    {
        $candidates = Candidate::orderBy('number')->get();

        return view('candidates.index', compact('candidates'));
    }

    public function create()
    {
        return view('candidates.create');
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'number' => ['required', 'integer', 'min:1', 'unique:candidates,number'],
        'name' => ['required', 'string', 'max:255'],
        'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        'vision' => ['nullable', 'string'],
        'mission' => ['nullable', 'string'],
    ]);

    if ($request->hasFile('photo')) {
        $validated['photo'] = $request->file('photo')
            ->store('candidates', 'public');
    }

    Candidate::create($validated);

    return redirect()
        ->route('candidates.index')
        ->with('success', 'Data calon berhasil ditambahkan.');
}

    public function show(Candidate $candidate)
    {
        return view('candidates.show', compact('candidate'));
    }

    public function edit(Candidate $candidate)
    {
        return view('candidates.edit', compact('candidate'));
    }

    public function update(Request $request, Candidate $candidate)
{
    $validated = $request->validate([
        'number' => [
            'required',
            'integer',
            'min:1',
            'unique:candidates,number,' . $candidate->id,
        ],
        'name' => ['required', 'string', 'max:255'],
        'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        'vision' => ['nullable', 'string'],
        'mission' => ['nullable', 'string'],
    ]);

    if ($request->hasFile('photo')) {
        if ($candidate->photo) {
            \Illuminate\Support\Facades\Storage::disk('public')
                ->delete($candidate->photo);
        }

        $validated['photo'] = $request->file('photo')
            ->store('candidates', 'public');
    }

    $candidate->update($validated);

    return redirect()
        ->route('candidates.index')
        ->with('success', 'Data calon berhasil diperbarui.');
}

    public function destroy(Candidate $candidate)
{
    if ($candidate->photo) {
        Storage::disk('public')->delete($candidate->photo);
    }

    $candidate->delete();

    return redirect()
        ->route('candidates.index')
        ->with('success', 'Data calon berhasil dihapus.');
}
}