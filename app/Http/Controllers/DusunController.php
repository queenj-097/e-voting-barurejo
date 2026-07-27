<?php

namespace App\Http\Controllers;

use App\Models\Dusun;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DusunController extends Controller
{
    public function index(): View
    {
        $dusuns = Dusun::query()
            ->withCount('voters')
            ->orderBy('name')
            ->paginate(15);

        return view('dusuns.index', compact('dusuns'));
    }

    public function create(): View
    {
        return view('dusuns.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                'unique:dusuns,name',
            ],
        ], [
            'name.required' => 'Nama dusun wajib diisi.',
            'name.unique' => 'Nama dusun tersebut sudah tersedia.',
        ]);

        Dusun::create($validated);

        return redirect()
            ->route('dusuns.index')
            ->with('success', 'Dusun berhasil ditambahkan.');
    }

    public function edit(Dusun $dusun): View
    {
        return view('dusuns.edit', compact('dusun'));
    }

    public function update(
        Request $request,
        Dusun $dusun
    ): RedirectResponse {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                'unique:dusuns,name,' . $dusun->id,
            ],
        ], [
            'name.required' => 'Nama dusun wajib diisi.',
            'name.unique' => 'Nama dusun tersebut sudah tersedia.',
        ]);

        $dusun->update($validated);

        return redirect()
            ->route('dusuns.index')
            ->with('success', 'Dusun berhasil diperbarui.');
    }

    public function destroy(Dusun $dusun): RedirectResponse
    {
        if ($dusun->voters()->exists()) {
            return back()->with(
                'error',
                'Dusun tidak dapat dihapus karena masih digunakan oleh data DPT.'
            );
        }

        $dusun->delete();

        return redirect()
            ->route('dusuns.index')
            ->with('success', 'Dusun berhasil dihapus.');
    }
}