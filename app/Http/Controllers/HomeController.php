<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $totalSuratMasuk = SuratMasuk::count();
        $totalSuratKeluar = SuratKeluar::count();

        $chartSuratMasuk = $this->getSuratMasukMonthly();
        $chartSuratKeluar = $this->getSuratKeluarMonthly();

        $jenisSuratKeluar = $this->getJenisSuratKeluar();

        $statusDisposisi = $this->getStatusDisposisi();

        $latestSuratMasuk = SuratMasuk::latest()->take(5)->get();
        $latestSuratKeluar = SuratKeluar::latest()->take(5)->get();

        return view('home', compact(
            'totalSuratMasuk',
            'totalSuratKeluar',
            'chartSuratMasuk',
            'chartSuratKeluar',
            'jenisSuratKeluar',
            'statusDisposisi',
            'latestSuratMasuk',
            'latestSuratKeluar'
        ));
    }

    /**
     * Get monthly data for surat masuk
     */
    private function getSuratMasukMonthly()
    {
        $sixMonthsAgo = Carbon::now()->subMonths(5)->startOfMonth();

        $data = SuratMasuk::select(
            DB::raw("DATE_FORMAT(tgl_terima, '%Y-%m') as month"),
            DB::raw('COUNT(*) as total')
        )
            ->where('tgl_terima', '>=', $sixMonthsAgo)
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        return $this->formatChartData($data);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    

    /**
     * Get monthly data for surat keluar
     */
    private function getSuratKeluarMonthly()
    {
        $sixMonthsAgo = Carbon::now()->subMonths(5)->startOfMonth();

        $data = SuratKeluar::select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
            DB::raw('COUNT(*) as total')
        )
            ->where('created_at', '>=', $sixMonthsAgo)
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        return $this->formatChartData($data);
    }

    /**
     * Format chart data with consistent month labels
     */
    private function formatChartData($data)
    {
        // Create months array (last 6 months)
        $months = [];
        $counts = [];

        for ($i = 5; $i >= 0; $i--) {
            $monthKey = Carbon::now()->subMonths($i)->format('Y-m');
            $monthName = Carbon::now()->subMonths($i)->format('M Y');
            $months[$monthKey] = $monthName;
            $counts[$monthKey] = 0;
        }

        // Fill in actual counts
        foreach ($data as $item) {
            if (isset($counts[$item->month])) {
                $counts[$item->month] = $item->total;
            }
        }

        return [
            'labels' => array_values($months),
            'data' => array_values($counts)
        ];
    }

    /**
     * Get jenis surat keluar data for charts
     */
    private function getJenisSuratKeluar()
    {
        // Karena metadata adalah field JSON, kita menggunakan logic dari accessor getJenisSuratAttribute
        // Untuk memastikan akurasi data, kita mengambil semua SuratKeluar dan mengelompokkannya di aplikasi

        $suratKeluar = SuratKeluar::all();
        $jenisCount = [
            'perintah' => 0,
            'rekomendasi' => 0,
            'keterangan' => 0,
            'undangan' => 0,
            'pengumuman' => 0,
            'lainnya' => 0
        ];

        foreach ($suratKeluar as $surat) {
            $jenis = $surat->getJenisSuratAttribute(); // Menggunakan accessor dari model
            $jenisCount[$jenis]++;
        }

        // Filter hanya jenis yang memiliki data
        $labels = [];
        $data = [];

        foreach ($jenisCount as $jenis => $count) {
            if ($count > 0) {
                $labels[] = ucfirst($jenis);
                $data[] = $count;
            }
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    /**
     * Get status disposisi data for charts
     */
    private function getStatusDisposisi()
    {
        $sudahDisposisi = SuratMasuk::where('status_disposisi', 'sudah_disposisi')->count();
        $belumDitentukan = SuratMasuk::where('status_disposisi', 'belum_ditentukan')->count();
        $tidakPerluDisposisi = SuratMasuk::where('status_disposisi', 'tidak_perlu_disposisi')->count();

        // Handle empty database
        if (($sudahDisposisi + $belumDitentukan + $tidakPerluDisposisi) == 0) {
            $belumDitentukan = 1; // Default value untuk menghindari chart kosong
        }

        return [
            'labels' => ['Sudah Disposisi', 'Belum Ditentukan', 'Tidak Perlu Disposisi'],
            'data' => [$sudahDisposisi, $belumDitentukan, $tidakPerluDisposisi]
        ];
    }
}
