<?php

use App\Http\Controllers\CandidateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VoterController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\VotingController;
use App\Http\Controllers\BallotScanController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\ElectionSettingController;
use App\Http\Controllers\BoothController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::get('/verification', [VerificationController::class, 'index'])
    ->name('verification.index');

Route::post('/verification', [VerificationController::class, 'verify'])
    ->name('verification.verify');

Route::get('/verification/result', [VerificationController::class, 'result'])
    ->name('verification.result');

Route::post('/verification/cancel', [VerificationController::class, 'cancel'])
    ->name('verification.cancel');

Route::get('/voting', [VotingController::class, 'index'])
    ->name('voting.index');

Route::post('/voting', [VotingController::class, 'store'])
    ->name('voting.store');

Route::get('/voting/receipt/{ballot}', [VotingController::class, 'receipt'])
    ->name('voting.receipt');

Route::get('/scan', [BallotScanController::class, 'index'])
    ->name('scan.index');

Route::post('/scan', [BallotScanController::class, 'store'])
    ->name('scan.store');

Route::get('/results', [ResultController::class, 'index'])
    ->name('results.index');

Route::get('/settings', [ElectionSettingController::class, 'edit'])
    ->name('settings.edit');

Route::put('/settings', [ElectionSettingController::class, 'update'])
    ->name('settings.update');

Route::get('/bilik/{booth}', [BoothController::class, 'show'])
    ->name('booths.show');

Route::post('/bilik/{booth}/mulai', [BoothController::class, 'start'])
    ->name('booths.start');

Route::post('/verification/assign-booth', [VerificationController::class, 'assignToBooth'])
    ->name('verification.assign-booth');

Route::get('/bilik-status', [BoothController::class, 'status'])
    ->name('booths.status');

Route::delete('/settings/reset-election', [ElectionSettingController::class, 'resetElection'])
    ->name('settings.reset-election');

Route::resource('candidates', CandidateController::class);
Route::resource('voters', VoterController::class);
