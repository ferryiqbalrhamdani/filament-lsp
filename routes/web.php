<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckNotAsesi;
use App\Http\Controllers\PrintController;

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/print/apl-one/{id}', [PrintController::class, 'printPdf'])->name('print.apl-one')->middleware([CheckNotAsesi::class]);
