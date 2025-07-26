<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratKeluar;

class CekSuratController extends Controller
{
    public function index(Request $request)
    {
        $kode = $request->input('kode');
        $suratKeluar = null;

        if ($kode) {
            $suratKeluar = SuratKeluar::where('kode_pencarian', $kode)->first();

            if ($suratKeluar && is_string($suratKeluar->metadata)) {
                $suratKeluar->metadata = json_decode($suratKeluar->metadata, true);
            }
        }

        return view('cek-surat', compact('suratKeluar', 'kode'));
    }
}
