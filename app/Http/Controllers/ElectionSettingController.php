<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Ballot;
use App\Models\Booth;
use App\Models\Candidate;
use App\Models\Dusun;
use App\Models\ElectionGroup;
use App\Models\ElectionSetting;
use App\Models\Voter;
use App\Models\VotingAccess;
use App\Services\AutoElectionGroupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ElectionSettingController extends Controller
{
    public function __construct(
        private AutoElectionGroupService $groupService
    ) {
    }

    public function edit()
    {
        $setting = ElectionSetting::firstOrCreate([]);

        return view('settings.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'institution' => [
                'required',
                'string',
                'max:255',
            ],
            'location' => [
                'nullable',
                'string',
                'max:255',
            ],
            'election_date' => [
                'nullable',
                'date',
            ],
            'status' => [
                'required',
                'in:persiapan,berlangsung,selesai',
            ],
            'candidate_scope' => [
                'required',
                'in:general,grouped',
            ],
        ]);

        $setting = ElectionSetting::firstOrCreate([]);

        $setting->update($validated);

        /*
         * Sinkronkan kelompok pemilihan otomatis.
         *
         * Mode general:
         * - election_group_id kandidat dan pemilih dikosongkan
         * - data kelompok dihapus
         *
         * Mode grouped:
         * - kelompok dibuat berdasarkan kombinasi dusun kandidat
         * - kandidat dan pemilih dimasukkan ke kelompok yang sesuai
         */
        $this->groupService->sync();

        return redirect()
            ->route('settings.edit')
            ->with(
                'success',
                'Pengaturan pemilihan berhasil diperbarui.'
            );
    }

    /**
     * Menghapus riwayat aktivasi dan mengembalikan
     * seluruh bilik ke kondisi awal.
     *
     * Tidak menghapus DPT, kandidat, dusun,
     * surat suara, atau hasil pemilihan.
     */
    public function resetActivations(Request $request)
    {
        DB::transaction(function () {
            VotingAccess::query()->delete();

            Booth::query()->update([
                'status' => 'idle',
                'current_voter_id' => null,
                'assigned_at' => null,
                'voting_started_at' => null,
            ]);
        });

        $request->session()->forget([
            'verified_voter_id',
            'active_booth_id',
        ]);

        return redirect()
            ->route('settings.edit')
            ->with(
                'success',
                'Seluruh aktivasi dan status bilik berhasil direset.'
            );
    }

    /**
     * Menghapus hasil pemilihan, tetapi tetap
     * mempertahankan DPT, kandidat, dusun,
     * kelompok pemilihan, dan pengaturan.
     */
    public function resetElection(Request $request)
    {
        DB::transaction(function () {
            Ballot::query()->delete();

            VotingAccess::query()->delete();

            Voter::query()->update([
                'has_voted' => false,
                'voted_at' => null,
            ]);

            Booth::query()->update([
                'status' => 'idle',
                'current_voter_id' => null,
                'assigned_at' => null,
                'voting_started_at' => null,
            ]);
        });

        $request->session()->forget([
            'verified_voter_id',
            'active_booth_id',
        ]);

        /*
         * Bentuk ulang kelompok agar relasi kandidat
         * dan pemilih tetap konsisten setelah reset.
         */
        $this->groupService->sync();

        return redirect()
            ->route('settings.edit')
            ->with(
                'success',
                'Data pemilihan berhasil direset. Data master tetap tersimpan.'
            );
    }

    /**
     * Menghapus seluruh data pemilihan dan data master.
     *
     * Akun petugas dan perangkat bilik tetap dipertahankan
     * supaya admin tetap dapat masuk ke aplikasi.
     */
    public function resetSystem(Request $request)
    {
        $request->validate([
            'reset_confirmation' => [
                'required',
                'in:RESET SELURUH SISTEM',
            ],
        ], [
            'reset_confirmation.required' =>
                'Teks konfirmasi wajib diisi.',
            'reset_confirmation.in' =>
                'Teks konfirmasi tidak sesuai.',
        ]);

        DB::transaction(function () {
            /*
             * Hapus data transaksi terlebih dahulu
             * agar tidak melanggar foreign key.
             */
            Ballot::query()->delete();
            VotingAccess::query()->delete();

            /*
             * Lepaskan seluruh bilik dari pemilih.
             */
            Booth::query()->update([
                'status' => 'idle',
                'current_voter_id' => null,
                'assigned_at' => null,
                'voting_started_at' => null,
            ]);

            /*
             * Kosongkan hubungan dengan kelompok terlebih dahulu.
             */
            Candidate::query()->update([
                'election_group_id' => null,
            ]);

            Voter::query()->update([
                'election_group_id' => null,
            ]);

            /*
             * Hapus data master.
             *
             * Relasi candidate_dusun akan ikut terhapus
             * melalui cascade pada tabel pivot.
             */
            Candidate::query()->delete();
            Voter::query()->delete();
            ElectionGroup::query()->delete();
            Dusun::query()->delete();

            /*
             * Hapus pengaturan dan log aktivitas.
             */
            ElectionSetting::query()->delete();
            ActivityLog::query()->delete();

            /*
             * Buat kembali satu pengaturan kosong agar
             * halaman Settings tetap dapat dibuka.
             */
            ElectionSetting::firstOrCreate([]);
        });

        $request->session()->forget([
            'verified_voter_id',
            'active_booth_id',
        ]);

        return redirect()
            ->route('settings.edit')
            ->with(
                'success',
                'Seluruh data sistem berhasil direset. Akun petugas dan perangkat bilik tetap tersimpan.'
            );
    }
}