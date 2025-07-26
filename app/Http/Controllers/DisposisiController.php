<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class DisposisiController extends Controller
{
    public function table()
    {
        return view('menu.disposisi.surat-disposisi');
    }

    public function index(Request $request)
    {
        $query = SuratMasuk::with(['pegawais', 'kodeSurat'])
            ->where('status_disposisi', 'Sudah_disposisi')
            ->latest();
        if ($request->has('pencarian')) {
            $search = $request->pencarian;
            $query->where(function ($q) use ($search) {
                $q->where('no_surat', 'like', "%{$search}%")
                    ->orWhere('perihal', 'like', "%{$search}%")
                    ->orWhere('asal_surat', 'like', "%{$search}%")
                    ->orWhereHas('pegawais', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%");
                    })
                    ->orWhereHas('kodeSurat', function ($q) use ($search) {
                        $q->where('kode_klasifikasi', 'like', "%{$search}%")
                            ->orWhere('nama_kode', 'like', "%{$search}%");
                    });
            });
        }
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('no_surat', fn($surat) => $surat->no_surat ?? '-')
            ->addColumn('asal_surat', fn($surat) => $surat->asal_surat ?? '-')
            ->addColumn('tgl_terima', fn($surat) => $surat->tgl_terima ?? '-')
            ->addColumn('kode_klasifikasi', function ($surat) {
                return $surat->kodeSurat
                    ? $surat->kodeSurat->kode_klasifikasi . ' - ' . $surat->kodeSurat->nama_kode
                    : '-';
            })
            ->addColumn('nama', function ($surat) {
                return $surat->pegawais->isNotEmpty()
                    ? implode(', ', $surat->pegawais->pluck('nama')->toArray())
                    : '-';
            })
            ->addColumn('catatan', function ($surat) {
                return $surat->pegawais->isNotEmpty()
                    ?  Str::limit($surat->pegawais->pluck('pivot.catatan')->first(), 25)
                    : '-';
            })
            ->addColumn('status_disposisi', function ($surat) {
                $status = $surat->status_disposisi ?? 'belum_ditentukan';
                $status_badges = [
                    'belum_ditentukan' => '<span class="badge badge-warning">Belum Ditentukan</span>',
                    'tidak_perlu_disposisi' => '<span class="badge badge-secondary">Tidak Perlu Disposisi</span>',
                    'sudah_disposisi' => '<span class="badge badge-success">Sudah Disposisi</span>',
                ];
                return $status_badges[$status] ?? '<span class="badge badge-dark">Unknown</span>';
            })
            ->addColumn('action', function ($surat) {
                return '<div class="btn-group">
                    <a href="' . route('surat-masuk.show', $surat->id) . '" class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i> Show
                    </a>
                </div>';
            })
            ->rawColumns(['status_disposisi', 'action']) // Pastikan status_disposisi dirender sebagai HTML
            ->make(true);
    }
}
