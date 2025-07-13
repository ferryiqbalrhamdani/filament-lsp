<?php

namespace App\Http\Controllers;

use App\Models\AplOne;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Crypt;

class PrintController extends Controller
{
    public function printPdf($id)
    {
        try {
            $realId = Crypt::decrypt($id);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            abort(403, 'ID tidak valid.');
        }
        // Ambil data sesuai kebutuhan
        $data = AplOne::findOrFail($realId);

        // Contoh data sederhana
        // $data = [
        //     'id' => $id,

        //     'judul' => 'Contoh PDF',
        //     'deskripsi' => 'Ini adalah contoh isi PDF.'
        // ];

        // Generate PDF dari view
        $pdf = Pdf::loadView('print.apl-one', compact('data'));

        // Return sebagai download
        return $pdf->stream();

        // return view('print.apl-one', compact('id')); // contoh tampilan view
    }
}
