<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\EyeExaminationController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/patients', [PatientController::class, 'index'])->name('patient.index');
    Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');
    Route::get('/eye-examinations', [EyeExaminationController::class, 'index'])->name('examinations');
});
