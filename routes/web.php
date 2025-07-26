<?php

use App\Http\Controllers\DisposisiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HakaksesController;
use App\Http\Controllers\KodeSuratController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\SuratMasukController;
use Illuminate\Support\Facades\Auth;


use App\Http\Controllers\CekSuratController;
use Barryvdh\DomPDF\Facade\PDF;


// Test route for Surat Pengumuman PDF
Route::get('/test-pengumuman', function () {
    // Create dummy SuratKeluar object
    $suratKeluar = new stdClass();
    $suratKeluar->no_surat = '422/208/436.7.1.P33/2024';
    $suratKeluar->ttd_path = 'gambar_ttd/signature.png'; // Path to signature in storage
    
    // Create dummy metadata object
    $metadata = new stdClass();
    $metadata->perihal = 'Pemberitahuan Libur Sekolah';
    $metadata->pembuka = 'Wali Murid Kelas 8 dan 9';
    $metadata->body = "Dengan berakhirnya Kegiatan Belajar Mengajar (KBM) semester genap tahun ajaran 2023/2024 dan telah dibagikannya Laporan Hasil Belajar (Rapor) Kenaikan Kelas, dengan ini kami sampaikan hal-hal berikut :\n\n" .
        "1. Libur Akhir Tahun Ajaran 2023/2024 mulai 24 Juni s.d. 12 Juli 2024.\n\n" .
        "2. Diharapkan peserta didik mengembalikan Rapor pada hari pertama masuk sekolah dan sudah ditandatangani oleh Orang Tua atau Wali Murid.\n\n" .
        "3. Orang tua atau Wali Murid tetap melakukan pengawasan, pendampingan, memantau, dan memastikan putra putrinya melakukan kegiatan yang bersifat positif, serta membatasi aktivitas diluar rumah terutama di pusat keramaian dan waktu malam hari.\n\n" .
        "4. Hal-hal lain akan kami sampaikan lebih lanjut melalui pemberitahuan berikutnya.";
    
    // Check if the logo exists, if not, prepare a notification
    $logoPath = public_path('img/logo_SMP.png');
    $logoExists = file_exists($logoPath);
    
    if (!$logoExists) {
        // Create a placeholder for testing
        // Note: In a real implementation, you might want to handle this differently
        return "Logo file not found at {$logoPath}. Please create this directory and add a logo image.";
    }
    
    // Generate PDF
    $pdf = PDF::loadView('pdf.surat_pengumuman', [
        'suratKeluar' => $suratKeluar,
        'metadata' => $metadata
    ]);
    
    // Return PDF for preview in browser
    return $pdf->stream('surat_pengumuman.pdf');
    
    // Alternatively, if you want to download directly:
    // return $pdf->download('surat_pengumuman.pdf');
});

Route::get('/test-pdf-undangan', function () {
    // Data dummy untuk testing surat undangan
    $data = [
        'no_surat_keluar' => '005/183/436.7.1.P33/2024',
        'metadata' => [
            'kepada' => 'Lurah Putat Gede',
            'kota' => 'Surabaya',
            'tempat' => 'Halaman SMP Negeri 33 Surabaya',
            'tanggal' => '2024-06-08', // Format Y-m-d untuk Carbon processing
            'acara' => 'GEBYAR PENTAS KARYA/SENI KELAS 7&8 DAN PELEPASAN SISWA KELAS 9, TAHUN PELAJARAN 2024-2025',
            'waktu_mulai' => '07.00',
            'waktu_selesai' => 'selesai',
            'alamat' => 'Jalan Putat Gede Selatan No. 8 Surabaya',
        ],
        // Path to signature image
        'gambar_ttd' => public_path('img/ttd_kepsek.png')
    ];

    // Generate PDF
    $pdf = PDF::loadView('pdf.surat_undangan', $data);

    // Return PDF untuk preview di browser
    return $pdf->stream('undangan_pentas_seni.pdf');

    // Atau jika ingin langsung download
    // return $pdf->download('undangan_pentas_seni.pdf');
});

Route::get('/test-pdf-rekomendasi', function () {
    // Data dummy untuk testing surat rekomendasi
    $data = [
        'no_surat' => '421/123/436.7.1/2025',
        'kepala_sekolah' => (object)[
            'nama' => 'Drs. NAMA KEPALA SEKOLAH, M.Pd.',
            'nip' => '196012121990031001',
            'jabatan' => 'Kepala SMP Negeri 33 Surabaya',
            'pangkat' => 'Pembina Utama Muda, IV/c'
        ],
        'metadata' => [
            'informasi_pribadi' => [
                'nama' => 'NAMA SISWA',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '15-08-2010',
            ],
            'informasi_akademik' => [
                'nis_nisn' => '12345/54321',
                'kelas' => 'IX-A',
            ],
            'informasi_kontak' => [
                'alamat' => 'Jl. Contoh No. 123, Surabaya',
            ],
            'informasi_acara' => [
                'nama_acara' => 'Olimpiade Matematika Tingkat Nasional',
                'tanggal_acara' => '5-7 Mei 2025',
                'penyelenggara' => 'Kementerian Pendidikan dan Kebudayaan',
            ],
            'tanggal_registrasi' => '2025-04-20',
        ],
        // Opsional: path ttd jika ada
        // 'ttd_path' => public_path('img/ttd_kepsek.png')
    ];

    // Generate PDF
    $pdf = PDF::loadView('pdf.surat_rekomendasi', $data);

    // Return PDF untuk preview di browser
    return $pdf->stream('surat_rekomendasi.pdf');

    // Atau jika ingin langsung download
    // return $pdf->download('surat_rekomendasi.pdf');
});

Route::get('/test-pdf', function () {
    // Data dummy untuk testing
    $data = [
        'no_surat' => '800/123/436.7.1/2025',
        'kepala_sekolah' => (object)[
            'nama' => 'Drs. NAMA KEPALA SEKOLAH, M.Pd.',
            'nip' => '196012121990031001',
            'jabatan' => 'Kepala SMP Negeri 33 Surabaya',
            'pangkat' => 'Pembina Utama Muda, IV/c'
        ],
        'metadata' => [
            'guru_ditugaskan' => [
                [
                    'nama' => 'NAMA GURU 1, S.Pd.',
                    'nip' => '197001011995122001',
                    'keterangan' => 'Guru Matematika'
                ],
                [
                    'nama' => 'NAMA GURU 2, M.Pd.',
                    'nip' => '198002022000032002',
                    'keterangan' => 'Guru IPA'
                ]
            ],
            'hari' => 'Senin',
            'tanggal' => '27 April 2025',
            'waktu_mulai' => '08:00',
            'tempat' => 'Aula SMP Negeri 33 Surabaya',
            'alamat' => 'Jalan Putat Gede Selatan No. 8 Surabaya',
            'kota' => 'Surabaya'
        ],
        // Opsional: path ttd jika ada
        // 'ttd_path' => public_path('img/ttd_kepsek.png')
    ];

    // Generate PDF
    $pdf = PDF::loadView('pdf.surat_perintah', $data);

    // Return PDF untuk preview di browser
    return $pdf->stream('surat_perintah.pdf');

    // Atau jika ingin langsung download
    // return $pdf->download('surat_perintah.pdf');
});

Route::get('/test-keterangan', function () {
    // Data dummy untuk testing
    $data = [
        'no_surat' => '422/123/436.7.1/2025',
        'kepala_sekolah' => (object)[
            'nama' => 'Drs. NAMA KEPALA SEKOLAH, M.Pd.',
            'nip' => '196012121990031001',
            'jabatan' => 'Kepala SMP Negeri 33 Surabaya',
            'pangkat' => 'Pembina Utama Muda, IV/c'
        ],
        'metadata' => [
            'informasi_pribadi' => [
                'nama' => 'NAMA SISWA',
                'jenis_kelamin' => 'Laki-laki',
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '15 Mei 2010',
            ],
            'informasi_akademik' => [
                'nis_nisn' => '12345 / 0012345678',
                'kelas' => 'VIII-A',
            ],
            'informasi_kontak' => [
                'alamat' => 'Jl. Contoh No. 123, Kelurahan Contoh, Kecamatan Contoh, Surabaya',
            ],
            'keperluan' => 'untuk keperluan pengajuan Kartu Indonesia Pintar (KIP).',
            'tanggal_registrasi' => '2025-04-20', // Format YYYY-MM-DD untuk diproses oleh date()
            'kota' => 'Surabaya'
        ],
        // Opsional: path ttd jika ada
        // 'ttd_path' => public_path('img/ttd_kepsek.png')
    ];

    // Generate PDF
    $pdf = PDF::loadView('pdf.surat_keterangan', $data);

    // Return PDF untuk preview di browser
    return $pdf->stream('surat_keterangan.pdf');

    // Atau jika ingin langsung download
    // return $pdf->download('surat_keterangan.pdf');
});

Route::get('/cek-surat', [CekSuratController::class, 'index'])->name('cek-surat');


Route::get('/', function () {

    return redirect()->route('login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'changepassword'])->name('profile.change-password');
    Route::put('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
    Route::get('/blank-page', [HomeController::class, 'blank'])->name('blank');

    // show surat masuk pulic
    Route::get('/surat-masuk/{suratMasuk}', [SuratMasukController::class, 'show'])->name('surat-masuk.show');
    Route::get('/menu/surat-masuk', [SuratMasukController::class, 'index'])->name('surat-masuk.index');
    Route::put('/surat-masuk/{suratMasuk}', [SuratMasukController::class, 'updateStatus'])->name('surat-masuk.update-status');
    Route::post('/surat-masuk/{suratMasuk}/disposisi', [SuratMasukController::class, 'disposisi'])->name('surat-masuk.disposisi');

    
    Route::get('/menu/surat-disposisi', [DisposisiController::class, 'table'])->name('surat-disposisi.table');
    // Rute untuk menampilkan halaman dengan tabel
    Route::get('surat-disposisi', [DisposisiController::class, 'table'])->name('surat-disposisi.table');

    // Rute untuk AJAX DataTables
    Route::get('surat-disposisi/data', [DisposisiController::class, 'index'])->name('surat-disposisi.index');

    Route::get('master/surat-keluar', [SuratKeluarController::class, 'index'])->name('keluar.master');
    
    Route::get('surat/perintah', [SuratKeluarController::class, 'perintah'])->name('keluar.surat-perintah');
    Route::get('surat/rekomendasi', [SuratKeluarController::class, 'rekomendasi'])->name('keluar.rekomendasi');
    Route::get('surat/undangan', [SuratKeluarController::class, 'undangan'])->name('keluar.undangan');
    Route::get('surat/keterangan', [SuratKeluarController::class, 'keterangan'])->name('keluar.keterangan');
    Route::get('surat/pengumuman', [SuratKeluarController::class, 'pengumuman'])->name('keluar.pengumuman');


    Route::post('/rekomendasi', [SuratKeluarController::class, 'store'])->name('rekomendasi.store');
    Route::post('/keterangan', [SuratKeluarController::class, 'keteranganStore'])->name('keterangan.store');
    Route::post('/pengumuman', [SuratKeluarController::class, 'pengumumanStore'])->name('pengumuman.store');
    Route::post('/undangan', [SuratKeluarController::class, 'undanganStore'])->name('undangan.store');
    Route::post('/surat-perintah/store', [SuratKeluarController::class, 'perintahStore'])->name('surat-perintah.store');

    Route::get('/surat-keluar/{suratKeluar}', [SuratKeluarController::class, 'show'])->name('keluar.show');
    Route::put('/surat-keluar/{suratKeluar}/update-kode', [SuratKeluarController::class, 'updateKode'])
        ->name('keluar.update-kode');
    Route::put('/surat-keluar/{suratKeluar}', [SuratKeluarController::class, 'destroy'])->name('keluar.delete');
    Route::post('/surat-keluar/{suratKeluar}/upload-ttd', [SuratKeluarController::class, 'uploadTandaTangan'])->name('keluar.upload-ttd');
    Route::delete('/surat-keluar/{suratKeluar}/hapus-ttd', [SuratKeluarController::class, 'hapusTandaTangan'])->name('keluar.hapus-ttd');
    // Di routes/web.php
    Route::post('/surat/{suratKeluar}/generate', [SuratkeluarController::class, 'generateDanKirim'])
        ->name('surat.generate');
    Route::post('/surat/{suratKeluar}/generateOnly', [SuratkeluarController::class, 'generateOnly'])
        ->name('surat.generateOnly');

    Route::post('/surat-keluar/{suratKeluar}/revisi', [SuratKeluarController::class, 'revisi'])->name('keluar.revisi');


    Route::get('/surat-perintah/{suratKeluar}/edit', [SuratKeluarController::class, 'editPerintah'])->name('perintah.edit');
    Route::get('/surat-rekomendasi/{suratKeluar}/edit', [SuratKeluarController::class, 'editRekomendasi'])->name('rekomendasi.edit');
    Route::get('/surat-keterangan/{suratKeluar}/edit', [SuratKeluarController::class, 'editKeterangan'])->name('keterangan.edit');
    Route::get('/surat-undangan/{suratKeluar}/edit', [SuratKeluarController::class, 'editUndangan'])->name('undangan.edit');
    

    // Rute untuk update surat
    Route::put('/surat-perintah/{suratKeluar}', [SuratKeluarController::class, 'updatePerintah'])->name('perintah.update');
    Route::put('/surat-rekomendasi/{suratKeluar}', [SuratKeluarController::class, 'updateRekomendasi'])->name('rekomendasi.update');
    Route::put('/surat-keterangan/{suratKeluar}', [SuratKeluarController::class, 'updateKeterangan'])->name('keterangan.update');
    Route::put('/surat-undangan/{suratKeluar}', [SuratKeluarController::class, 'updateUndangan'])->name('undangan.update');
});


Route::middleware(['auth', 'superadmin'])->group(function () {
    Route::get('/hakakses', [HakaksesController::class, 'index'])->name('hakakses.index');
    Route::get('/hakakses/edit/{id}', [HakaksesController::class, 'edit'])->name('hakakses.edit');
    Route::put('/hakakses/update/{id}', [HakaksesController::class, 'update'])->name('hakakses.update');
    Route::delete('/hakakses/delete/{id}', [HakaksesController::class, 'destroy'])->name('hakakses.delete');
    Route::get('/hakakses/create', [HakaksesController::class, 'create'])->name('hakakses.create');
    Route::post('/hakakses/store', [HakaksesController::class, 'store'])->name('hakakses.store');

    //
    Route::get('/data-pegawai', [PegawaiController::class, 'index'])->name('data-pegawai.index');
    Route::get('/create-pegawai',[PegawaiController::class, 'create'])->name('data-pegawai.create');
    Route::post('/store-pegawai', [PegawaiController::class, 'store'])->name('data-pegawai.store');
    Route::get('/data-pegawai/{pegawai}/edit', [PegawaiController::class, 'edit'])->name('data-pegawai.edit');
    Route::put('/update-pegawai/{pegawai}', [PegawaiController::class, 'update'])->name('data-pegawai.update');
    Route::delete('/data-pegawai/delete/{pegawai}', [PegawaiController::class, 'destroy'])->name('data-pegawai.delete');

    //Kode Surat
    Route::get('/kode-klasifikasi', [KodeSuratController::class, 'index'])->name('kode-surat.index');
    Route::get('/create-kode', [KodeSuratController::class, 'create'])->name('kode-surat.create');
    Route::post('/store-kode', [KodeSuratController::class, 'store'])->name('kode-surat.store');
    Route::get('/kode-surat/{kodeSurat}/edit', [KodeSuratController::class, 'edit'])->name('kode-surat.edit');
    Route::put('/update-kode/{kodeSurat}', [KodeSuratController::class, 'update'])->name('kode-surat.update');
    Route::delete('/KodeSurat/delete/{id}', [KodeSuratController::class, 'destroy'])->name('kode-surat.delete');

    //Surat Masuk
    Route::get('/suat-masuk/create', [SuratMasukController::class, 'create'])->name('surat-masuk.create');
    Route::post('/surat-masuk/store', [SuratMasukController::class, 'store'])->name('surat-masuk.store');
    Route::get('/surat-masuk/{suratMasuk}/edit', [SuratMasukController::class, 'edit'])->name('surat-masuk.edit');
    Route::post('/surat-masuk/{suratMasuk}/update', [SuratMasukController::class, 'update'])->name('surat-masuk.update');
    Route::delete('surat-masuk/{suratMasuk}', [SuratMasukController::class, 'destroy'])->name('surat-masuk.delete');

    // tambah disposisi di surat masuk
    
    
});