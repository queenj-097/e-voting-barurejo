<?php

namespace App\Http\Controllers;

use App\Imports\VotersImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class VoterImportController extends Controller
{
    public function create()
    {
        return view('voters.import');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls',
                'max:10240',
            ],
        ], [
            'file.required' => 'Pilih file Excel terlebih dahulu.',
            'file.file' => 'File yang dipilih tidak valid.',
            'file.mimes' => 'File harus berformat XLSX atau XLS.',
            'file.max' => 'Ukuran file maksimal 10 MB.',
        ]);

        try {
            $import = new VotersImport();

            Excel::import(
                $import,
                $request->file('file')
            );

            $importedCount = $import->getImportedCount();
            $skippedCount = $import->getSkippedCount();
            $errors = $import->getErrors();

            if ($importedCount === 0 && $skippedCount > 0) {
                $firstErrors = collect($errors)
                    ->take(5)
                    ->map(function (array $error) {
                        return 'Baris ' .
                            $error['row'] .
                            ': ' .
                            $error['message'];
                    })
                    ->implode(' | ');

                return redirect()
                    ->route('voters.import')
                    ->withInput()
                    ->with(
                        'error',
                        'Semua data gagal diimpor. ' . $firstErrors
                    )
                    ->with('import_errors', $errors);
            }

            $message =
                "Import selesai. {$importedCount} data berhasil diimpor";

            if ($skippedCount > 0) {
                $message .=
                    ", {$skippedCount} data dilewati.";
            } else {
                $message .= '.';
            }

            return redirect()
                ->route('voters.index')
                ->with('success', $message)
                ->with('import_errors', $errors);

        } catch (Throwable $exception) {
            return redirect()
                ->route('voters.import')
                ->withInput()
                ->with(
                    'error',
                    'Import gagal: ' . $exception->getMessage()
                );
        }
    }
}