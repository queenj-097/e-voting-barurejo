<?php

namespace App\Imports;

use App\Models\Dusun;
use App\Models\Voter;
use App\Services\AutoElectionGroupService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use RuntimeException;
use Throwable;

class VotersImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    private int $importedCount = 0;
    private int $skippedCount = 0;

    private array $errors = [];

    public function collection(Collection $rows): void
    {
        DB::transaction(function () use ($rows): void {
            foreach ($rows as $index => $row) {
                /*
                 * Karena baris pertama merupakan heading,
                 * nomor baris data Excel dimulai dari baris 2.
                 */
                $excelRowNumber = $index + 2;

                try {
                    $this->importRow($row);
                    $this->importedCount++;
                } catch (Throwable $exception) {
                    $this->skippedCount++;

                    $this->errors[] = [
                        'row' => $excelRowNumber,
                        'message' => $exception->getMessage(),
                    ];
                }
            }
        });
    }

    private function importRow(Collection $row): void
    {
        $name = $this->cleanText(
            $this->getColumn($row, [
                'nama',
                'nama_lengkap',
                'nama_pemilih',
            ])
        );

        $rawGender = $this->getColumn($row, [
            'jenis_kelamin',
            'jenis_kelamin_lp',
            'jk',
            'gender',
        ]);

        $rawDusun = $this->getColumn($row, [
            'dusun',
            'nama_dusun',
            'alamat_dusun',
        ]);

        $rawRw = $this->getColumn($row, [
            'rw',
            'nomor_rw',
            'no_rw',
        ]);

        $rawRt = $this->getColumn($row, [
            'rt',
            'nomor_rt',
            'no_rt',
        ]);

        $rawNik = $this->getColumn($row, [
            'nik',
            'nomor_nik',
            'no_nik',
            'nomor_induk_kependudukan',
        ]);

        if ($name === null) {
            throw new RuntimeException('Nama pemilih kosong.');
        }

        $gender = $this->normalizeGender($rawGender);
        $dusun = $this->findDusun($rawDusun);
        $rw = $this->normalizeAreaNumber($rawRw, 'RW');
        $rt = $this->normalizeAreaNumber($rawRt, 'RT');
        $nik = $this->normalizeNik($rawNik);

        /*
         * Apabila NIK tersedia, cegah data yang sama masuk dua kali.
         */
        if ($nik !== null && Voter::where('nik', $nik)->exists()) {
            throw new RuntimeException(
                "NIK {$nik} sudah terdaftar."
            );
        }

        $voterCode = $this->generateVoterCode(
            $dusun,
            $rw,
            $rt
        );

        $voter = Voter::create([
            'voter_code' => $voterCode,
            'name' => $name,
            'gender' => $gender,
            'dusun_id' => $dusun->id,
            'rw' => $rw,
            'rt' => $rt,
            'nik' => $nik,
        ]);

        /*
         * Menyesuaikan kelompok pemilihan berdasarkan wilayah pemilih.
         */
        if (class_exists(AutoElectionGroupService::class)) {
            app(AutoElectionGroupService::class)->sync($voter);
        }
    }

    private function getColumn(
        Collection $row,
        array $possibleHeadings
    ): mixed {
        foreach ($possibleHeadings as $heading) {
            if ($row->has($heading)) {
                return $row->get($heading);
            }
        }

        return null;
    }

    private function cleanText(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        if ($value === '' || $value === '-') {
            return null;
        }

        return preg_replace('/\s+/', ' ', $value);
    }

    private function normalizeGender(mixed $value): string
    {
        $gender = $this->cleanText($value);

        if ($gender === null) {
            throw new RuntimeException('Jenis kelamin kosong.');
        }

        $gender = Str::upper($gender);
        $gender = str_replace(
            ['.', '-', '_'],
            ' ',
            $gender
        );
        $gender = preg_replace('/\s+/', ' ', $gender);

        $maleValues = [
            'L',
            'LK',
            'LAKI LAKI',
            'LAKI',
            'PRIA',
            'MALE',
        ];

        $femaleValues = [
            'P',
            'PR',
            'PEREMPUAN',
            'WANITA',
            'FEMALE',
        ];

        if (in_array($gender, $maleValues, true)) {
            return 'L';
        }

        if (in_array($gender, $femaleValues, true)) {
            return 'P';
        }

        throw new RuntimeException(
            "Jenis kelamin \"{$gender}\" tidak dikenali."
        );
    }

    private function findDusun(mixed $value): Dusun
    {
        $dusunName = $this->cleanText($value);

        if ($dusunName === null) {
            throw new RuntimeException('Dusun kosong.');
        }

        $normalizedInput = $this->normalizeDusunName($dusunName);

        $aliases = [
            'KRAJAN' => 'KRAJAN',

            'SENEPOLOR' => 'SENEPOLOR',
            'SENEPOLOR' => 'SENEPOLOR',

            'SENEPO SARI' => 'SENEPOSARI',
            'SENEPOSARI' => 'SENEPOSARI',

            'SUMBERURIP' => 'SUMBERURIP',
            'SUMBER URIP' => 'SUMBERURIP',

            'SUMBERMANGGIS' => 'SUMBERMANGGIS',
            'SUMBER MANGGIS' => 'SUMBERMANGGIS',
        ];

        $canonicalName = $aliases[$normalizedInput]
            ?? $normalizedInput;

        $dusun = Dusun::query()
            ->get()
            ->first(function (Dusun $dusun) use ($canonicalName) {
                return $this->normalizeDusunName($dusun->name)
                    === $this->normalizeDusunName($canonicalName);
            });

        if (!$dusun) {
            throw new RuntimeException(
                "Dusun \"{$dusunName}\" tidak ditemukan."
            );
        }

        return $dusun;
    }

    private function normalizeDusunName(string $value): string
    {
        $value = Str::upper(trim($value));
        $value = str_replace(
            ['.', ',', '-', '_'],
            ' ',
            $value
        );

        return preg_replace('/\s+/', ' ', $value);
    }

    private function normalizeAreaNumber(
        mixed $value,
        string $label
    ): string {
        $value = $this->cleanText($value);

        if ($value === null) {
            throw new RuntimeException("{$label} kosong.");
        }

        /*
         * Mengatasi nilai Excel seperti 1, 01, atau 1.0.
         */
        if (is_numeric($value)) {
            $number = (int) $value;
        } else {
            $digits = preg_replace('/\D/', '', $value);

            if ($digits === '') {
                throw new RuntimeException(
                    "{$label} \"{$value}\" tidak valid."
                );
            }

            $number = (int) $digits;
        }

        if ($number < 0 || $number > 99) {
            throw new RuntimeException(
                "{$label} harus berada antara 00 sampai 99."
            );
        }

        return str_pad(
            (string) $number,
            2,
            '0',
            STR_PAD_LEFT
        );
    }

    private function normalizeNik(mixed $value): ?string
    {
        $nik = $this->cleanText($value);

        if ($nik === null) {
            return null;
        }

        /*
         * Mengatasi format angka Excel seperti:
         * 3510123456789010 atau 3.510123456789E+15.
         */
        if (is_numeric($nik)) {
            $nik = number_format(
                (float) $nik,
                0,
                '',
                ''
            );
        }

        $nik = preg_replace('/\D/', '', $nik);

        if ($nik === '') {
            return null;
        }

        if (strlen($nik) !== 16) {
            throw new RuntimeException(
                "NIK harus terdiri dari 16 digit."
            );
        }

        return $nik;
    }

    private function generateVoterCode(
        Dusun $dusun,
        string $rw,
        string $rt
    ): string {
        $prefix = "{$dusun->code}-{$rw}-{$rt}-";

        $lastVoter = Voter::query()
            ->where('dusun_id', $dusun->id)
            ->where('rw', $rw)
            ->where('rt', $rt)
            ->where('voter_code', 'like', "{$prefix}%")
            ->orderByDesc('voter_code')
            ->first();

        $lastSequence = 0;

        if ($lastVoter) {
            $parts = explode('-', $lastVoter->voter_code);
            $lastSequence = (int) end($parts);
        }

        $nextSequence = $lastSequence + 1;

        do {
            $voterCode = $prefix . str_pad(
                (string) $nextSequence,
                3,
                '0',
                STR_PAD_LEFT
            );

            $nextSequence++;
        } while (
            Voter::where('voter_code', $voterCode)->exists()
        );

        return $voterCode;
    }

    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    public function getSkippedCount(): int
    {
        return $this->skippedCount;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}