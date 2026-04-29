<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('reports.index');
});

use App\Http\Controllers\ReportController;

Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
Route::post('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
Route::get('/reports/download/{id}/{type}', [ReportController::class, 'download'])->name('reports.download');
Route::get('/reports/email/{id}', [ReportController::class, 'sendEmail'])->name('reports.email');
Route::get('/reports/delete-selected', [ReportController::class, 'deleteSelected'])->name('reports.deleteSelected');

use App\Http\Controllers\AdminController;
Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
Route::post('/admin/status/{id}', [AdminController::class, 'updateStatus'])->name('admin.status.update');
