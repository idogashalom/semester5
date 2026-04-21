<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('reports.index');
});

use App\Http\Controllers\ReportController;

Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
Route::post('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
Route::get('/reports/download/{id}', [ReportController::class, 'download'])->name('reports.download');
Route::get('/reports/send/{id}', [ReportController::class, 'sendReport'])->name('reports.send');
