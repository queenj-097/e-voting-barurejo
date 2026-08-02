<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BallotScanController;
use App\Http\Controllers\BoothController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DusunController;
use App\Http\Controllers\ElectionSettingController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\VoterController;
use App\Http\Controllers\VotingController;
use App\Http\Controllers\VoterImportController;
use App\Http\Controllers\DatabaseBackupController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Login
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.process');

});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Semua petugas yang sudah login
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/dashboard/live', [DashboardController::class, 'live'])
        ->name('dashboard.live');

    Route::get('/bilik-status', [BoothController::class, 'status'])
        ->name('booths.status');

});

/*
|--------------------------------------------------------------------------
| Administrator
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:admin',
])->group(function () {

    Route::resource('candidates', CandidateController::class);

    Route::get('/voters/import', [VoterImportController::class, 'create'])
        ->name('voters.import');

    Route::post('/voters/import', [VoterImportController::class, 'store'])
        ->name('voters.import.store');

    Route::get('/voters/qr/print-all', [VoterController::class, 'printAllQr'])
        ->name('voters.qr.print-all');

    Route::get('/voters/{voter}/qr', [VoterController::class, 'qr'])
        ->name('voters.qr');

    Route::get('/voters/{voter}/qr-image', [VoterController::class, 'qrImage'])
        ->name('voters.qr.image');

    Route::delete('/voters/destroy-all', [VoterController::class, 'destroyAll'])
        ->name('voters.destroyAll');

    Route::resource('voters', VoterController::class);

    Route::resource('dusuns', DusunController::class)
        ->except(['show']);

    Route::get('/results', [ResultController::class, 'index'])
        ->name('results.index');

    Route::get('/results/pdf', [ResultController::class, 'exportPdf'])
        ->name('results.pdf');

    Route::get('/results/live-count', [ResultController::class, 'liveCount'])
        ->name('results.live-count');

    Route::get('/results/live-count/data', [ResultController::class, 'liveCountData'])
        ->name('results.live-count.data');

    Route::get('/activity-logs', [ActivityLogController::class, 'index'])
        ->name('activity-logs.index');

    Route::delete('/activity-logs/destroy-all', [ActivityLogController::class, 'destroyAll'])
        ->name('activity-logs.destroy-all');

    Route::delete('/activity-logs/{activityLog}', [ActivityLogController::class, 'destroy'])
        ->name('activity-logs.destroy');

    Route::get('/settings', [ElectionSettingController::class, 'edit'])
        ->name('settings.edit');

    Route::put('/settings', [ElectionSettingController::class, 'update'])
        ->name('settings.update');

    Route::get('/settings/database-backup', [DatabaseBackupController::class, 'download'])
        ->name('settings.database-backup');

    /*
    |--------------------------------------------------------------------------
    | Reset Sistem
    |--------------------------------------------------------------------------
    */

    Route::delete(
        '/settings/reset-activations',
        [ElectionSettingController::class, 'resetActivations']
    )->name('settings.reset-activations');

    Route::delete(
        '/settings/reset-election',
        [ElectionSettingController::class, 'resetElection']
    )->name('settings.reset-election');

    Route::delete(
        '/settings/reset-system',
        [ElectionSettingController::class, 'resetSystem']
    )->name('settings.reset-system');

});

/*
|--------------------------------------------------------------------------
| Administrator dan Verifikator
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:admin,verifikator',
])->group(function () {

    Route::get('/verification', [VerificationController::class, 'index'])
        ->name('verification.index');

    Route::post('/verification', [VerificationController::class, 'verify'])
        ->name('verification.verify');

    Route::get(
        '/verification/result',
        [VerificationController::class, 'result']
    )->name('verification.result');

    Route::post(
        '/verification/cancel',
        [VerificationController::class, 'cancel']
    )->name('verification.cancel');

    Route::post(
        '/verification/assign-booth',
        [VerificationController::class, 'assignToBooth']
    )->name('verification.assign-booth');

    Route::post(
        '/verification/assign',
        [VerificationController::class, 'assignToBooth']
    )->name('verification.assign');
});

/*
|--------------------------------------------------------------------------
| Administrator dan Scanner
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'role:admin,scanner',
])->group(function () {

    Route::get('/scan', [BallotScanController::class, 'index'])
        ->name('scan.index');

    Route::post('/scan', [BallotScanController::class, 'store'])
        ->name('scan.store');

    Route::get(
    '/scan/ballots/{ballot}/reprint', [BallotScanController::class, 'reprint'])
        ->name('scan.ballots.reprint');
});

/*
|--------------------------------------------------------------------------
| Perangkat Bilik
|--------------------------------------------------------------------------
*/

Route::get('/bilik/{booth}', [BoothController::class, 'show'])
    ->name('booths.show');

Route::post('/bilik/{booth}/mulai', [BoothController::class, 'start'])
    ->name('booths.start');

Route::get('/voting', [VotingController::class, 'index'])
    ->name('voting.index');

Route::post('/voting', [VotingController::class, 'store'])
    ->name('voting.store');

Route::get(
    '/voting/receipt/{ballot}',
    [VotingController::class, 'receipt']
)->name('voting.receipt');