<?php

use App\Http\Controllers\ContainerInspectionController;
use App\Http\Controllers\ContainerReleaseController;
use App\Http\Controllers\EirNumberController;
use App\Http\Controllers\GateEntryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Gate Entry Routes
Route::resource('gate-entries', GateEntryController::class);

// Container Inspection Routes
Route::resource('inspections', ContainerInspectionController::class);

// EIR Number Routes
Route::resource('eir-numbers', EirNumberController::class);

// Container Release Routes
Route::resource('releases', ContainerReleaseController::class);
Route::post('releases/{release}/validate-gate-b', [ContainerReleaseController::class, 'validateAtGateB'])
    ->name('releases.validate-gate-b');

