<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckNotAsesi;
use App\Http\Controllers\PrintController;

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/print/apl-one/{id}', [PrintController::class, 'printPdf'])->name('print.apl-one')->middleware([CheckNotAsesi::class]);
Route::get('/print/apl-two/{id}', [PrintController::class, 'printPdfAplTwo'])->name('print.apl-two')->middleware([CheckNotAsesi::class]);
