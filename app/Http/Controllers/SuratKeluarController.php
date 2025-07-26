<?php

namespace App\Http\Controllers;

use App\Mail\MailSuratKeluar;
use Illuminate\Support\Facades\Storage;
use App\Models\Pegawai;
use App\Models\KodeSurat;
use App\Models\SuratKeluar;
use App\Services\EmailService;
use App\Services\SuratGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;




class SuratKeluarController extends Controller
{

    protected $suratGeneratorService;
    protected $emailService;

    public function __construct(SuratGeneratorService $suratGeneratorService, EmailService $emailService)
    {
        $this->suratGeneratorService = $suratGeneratorService;
        $this->emailService = $emailService;
    }
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SuratKeluar::with('kodeSurat')
                ->select('surat_keluars.*')
                ->where('status_nomor', 'aktif')
                ->orderBy('created_at', 'desc');
            return DataTables::of($data)
                ->filter(function ($query) use ($request) {
                    if ($request->has('pencarian')) {
                        $query->where(function ($q) use ($request) {
                            $q->where('no_surat_keluar', 'like', "%{$request->pencarian}%")
                                ->orWhere('metadata', 'like', "%{$request->pencarian}%")
                                ->orWhereHas('kodeSurat', function ($q) use ($request) {
                                    $q->where('kode_klasifikasi', 'like', "%{$request->pencarian}%")
                                        ->orWhere('nama_kode', 'like', "%{$request->pencarian}%");
                                });
                        });
                    }
                    $allRecords = $query->get();
                    $filteredIds = $allRecords->filter(function ($item) use ($request) {
                        $metadata = json_decode($item->metadata, true);
                        if (!$metadata) return false;

                        // Filter untuk tidak menampilkan surat pengumuman
                        if (isset($metadata['perihal']) && isset($metadata['pembuka'])) {
                            return false; // Menghilangkan surat pengumuman
                        }

                        switch ($request->jenis_surat) {
                            case 'perintah':
                                return isset($metadata['guru_ditugaskan']);
                            case 'rekomendasi':
                                return isset($metadata['informasi_acara']);
                            case 'keterangan':
                                return isset($metadata['keperluan']);
                            case 'undangan':
                                return isset($metadata['acara']) && isset($metadata['kepada']);
                            case 'lainnya':
                                return !isset($metadata['informasi_acara']) &&
                                    !isset($metadata['keperluan']) &&
                                    !isset($metadata['guru_ditugaskan']) &&
                                    !(isset($metadata['acara']) && isset($metadata['kepada'])) &&
                                    !(isset($metadata['perihal']) && isset($metadata['pembuka']));
                            default:
                                // Untuk tampilan default, semua jenis surat kecuali pengumuman
                                return !(isset($metadata['perihal']) && isset($metadata['pembuka']));
                        }
                    })->pluck('id')->toArray();
                    $query->whereIn('id', $filteredIds);
                })
                ->addIndexColumn()
                ->addColumn('kode_klasifikasi', function ($row) {
                    if ($row->kodeSurat) {
                        return $row->kodeSurat->kode_klasifikasi . ' - ' . $row->kodeSurat->nama_kode;
                    }
                    return '-';
                })
                ->addColumn('status', function ($row) {
                    if ($row->status === 'draft') {
                        return '<span class="badge badge-warning">Draft</span>';
                    } elseif ($row->status === 'diproses') {
                        return '<span class="badge badge-secondary">Diproses</span>';
                    } elseif ($row->status === 'selesai') {
                        return '<span class="badge badge-success">Selesai</span>';
                    } elseif ($row->status === 'pending') {
                        return '<span class="badge badge-info">Pending</span>';
                    } elseif ($row->status === 'revisi') {
                        return '<span class="badge badge-danger">Revisi</span>';
                    }
                })
                ->addColumn('metadata', function ($row) {
                    if ($row->metadata) {
                        try {
                            $metadata = json_decode($row->metadata, true);
                            $result = '';
                            if (isset($metadata['guru_ditugaskan']) && is_array($metadata['guru_ditugaskan'])) {
                                $result .= '<strong>Guru ditugaskan:</strong><br>';
                                // Display up to 3 teachers
                                $counter = 0;
                                foreach ($metadata['guru_ditugaskan'] as $guru) {
                                    if ($counter < 3) {
                                        if (is_array($guru) && isset($guru['nama'])) {
                                            $result .= $guru['nama'] . '<br>';
                                        } else {
                                            $result .= 'Guru #' . ($counter + 1) . '<br>';
                                        }
                                        $counter++;
                                    } else {
                                        break;
                                    }
                                }
                                if (count($metadata['guru_ditugaskan']) > 3) {
                                    $result .= '<em>dan lainnya</em><br>';
                                }
                                return $result;
                            }
                            if (is_array($metadata)) {
                                if (isset($metadata['informasi_pribadi']) && is_array($metadata['informasi_pribadi'])) {
                                    $result .= '<strong>Informasi Pribadi:</strong><br>';
                                    $info = $metadata['informasi_pribadi'];

                                    // Ambil maksimal 3 field dari informasi_pribadi
                                    $counter = 0;
                                    foreach ($info as $key => $value) {
                                        if ($counter < 3) {
                                            $result .= ucfirst(str_replace('_', ' ', $key)) . ': ' . $value . '<br>';
                                            $counter++;
                                        } else {
                                            break;
                                        }
                                    }

                                    // Jika masih ada field lainnya
                                    if (count($info) > 3) {
                                        $result .= '<em>dan lainnya</em><br>';
                                    }

                                    $otherCategories = array_filter(array_keys($metadata), function ($cat) {
                                        return $cat !== 'informasi_pribadi';
                                    });

                                    if (count($otherCategories) > 0) {
                                        $result .= '<em>Kategori lain tersedia</em>';
                                    }
                                } else {
                                    // Untuk data flat, tampilkan 3 item pertama
                                    $slicedData = array_slice($metadata, 0, 3);
                                    foreach ($slicedData as $key => $value) {
                                        if (!is_array($value)) {
                                            $result .= ucfirst(str_replace('_', ' ', $key)) . ': ' . $value . '<br>';
                                        } else {
                                            $result .= ucfirst(str_replace('_', ' ', $key)) . ': <em>[data kompleks]</em><br>';
                                        }
                                    }

                                    if (count($metadata) > 3) {
                                        $result .= '<em>dan lainnya</em>';
                                    }
                                }
                            }

                            return $result ?: '-';
                        } catch (\Exception $e) {
                            return '<em>Format metadata tidak valid</em>';
                        }
                    }
                    return '-';
                })
                ->addColumn('jenis_surat', function ($row) {
                    if ($row->metadata) {
                        try {
                            $metadata = json_decode($row->metadata, true);
                            if (isset($metadata['guru_ditugaskan'])) {
                                return '<span class="badge badge-dark">Surat Perintah</span>';
                            } elseif (isset($metadata['informasi_acara'])) {
                                return '<span class="badge badge-info">Surat Rekomendasi</span>';
                            } elseif (isset($metadata['keperluan'])) {
                                return '<span class="badge badge-primary">Surat Keterangan</span>';
                            } elseif (isset($metadata['acara']) && isset($metadata['kepada'])) {
                                return '<span class="badge badge-warning">Surat Undangan</span>';
                            } elseif (isset($metadata['perihal']) && isset($metadata['pembuka'])) {
                                return '<span class="badge badge-success">Surat Pengumuman</span>';
                            } else {
                                return '<span class="badge badge-light">Lainnya</span>';
                            }
                        } catch (\Exception $e) {
                            return '<span class="badge badge-secondary">Error</span>';
                        }
                    }
                    return '<span class="badge badge-secondary">Lainnya</span>';
                })
                ->addColumn('action', function ($row) {
                    if (Auth::user()->role == 'superadmin') {
                        return '<div class="btn-group" role="group">
                            <a href="' . route('keluar.show', $row) . '" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Show
                            </a>
                            <button onclick="confirmDelete(\'' . route('keluar.delete', $row) . '\')" 
                                class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                        </div>';
                    } else {
                        return '<div class="btn-group" role="group">
                            <a href="' . route('keluar.show', $row) . '" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> Show
                            </a>
                        </div>';
                    }
                })
                ->rawColumns(['action', 'status', 'metadata', 'kode_klasifikasi', 'jenis_surat'])
                ->make(true);
        }

        return view('menu.keluar.master');
    }

    public function perintah()
    {
        $kodeSurat = KodeSurat::all();
        $pegawai = Pegawai::all();
        return view('menu.keluar.surat-keluar-perintah', compact('kodeSurat', 'pegawai'));
    }

    public function rekomendasi()
    {
        $kodeSurat = KodeSurat::all();
        return view('menu.keluar.rekomendasi', compact('kodeSurat'));
    }
    public function keterangan()
    {
        $kodeSurat = KodeSurat::all();
        return view('menu.keluar.keterangan', compact('kodeSurat'));
    }

    public function undangan()
    {
        $kodeSurat = KodeSurat::all();
        return view('menu.keluar.undangan', compact('kodeSurat'));
    }
    public function pengumuman()
    {
        $kodeSurat = KodeSurat::all();
        return view('menu.keluar.pengumuman', compact('kodeSurat'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'nis_nisn' => 'required',
            'kelas' => 'required|string|max:255',
            'alamat' => 'required|string',
            'email' => 'required|email',
            'nama_acara' => 'required|string',
            'penyelenggara' => 'required|string',
            'tanggal_acara' => 'required|string',
            'kode_klasifikasi' => 'required|exists:kode_surats,id',
        ]);
        $kodeUniq = $this->generateKodeUnik();
        $metadata = [
            'informasi_pribadi' => [
                'nama' => $validatedData['nama'],
                'jenis_kelamin' => $validatedData['jenis_kelamin'],
                'tempat_lahir' => $validatedData['tempat_lahir'],
                'tanggal_lahir' => $validatedData['tanggal_lahir'],
            ],
            'informasi_akademik' => [
                'nis_nisn' => $validatedData['nis_nisn'],
                'kelas' => $validatedData['kelas'],
            ],
            'informasi_kontak' => [
                'alamat' => $validatedData['alamat'],
                'email' => $validatedData['email'],
            ],
            'informasi_acara' => [
                'nama_acara' => $validatedData['nama_acara'],
                'penyelenggara' => $validatedData['penyelenggara'],
                'tanggal_acara' => $validatedData['tanggal_acara'],
            ],
            'kode_uniq' => $kodeUniq,
            'tanggal_registrasi' => now()->toDateTimeString(),
        ];

        $tahun = date('Y');
        $lastNumber = SuratKeluar::whereYear('created_at', $tahun)
            ->max('nomor_agenda');
        $nomorAgenda = $lastNumber ? $lastNumber + 1 : 1;
        $formattedNumber = str_pad($nomorAgenda, 4, '0', STR_PAD_LEFT);

        $kodeSurat = KodeSurat::findOrFail($validatedData['kode_klasifikasi']);
        $kodeKlasifikasi = $kodeSurat->kode_klasifikasi;
        $nomorSurat = $kodeKlasifikasi . '/' . $formattedNumber . '/436.7.1/' . $tahun;

        SuratKeluar::create([
            'kode_surat_id' => $validatedData['kode_klasifikasi'],
            'nomor_agenda' => $nomorAgenda,
            'no_surat_keluar' => $nomorSurat,
            'kode_pencarian' => $kodeUniq,
            'user_id' => Auth::id(),
            'metadata' => json_encode($metadata)
        ]);

        try {
            Mail::to($validatedData['email'])->send(new MailSuratKeluar($kodeUniq));
            Log::info('Email terkirim ke: ' . $validatedData['email']);
            $emailStatus = true;
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email: ' . $e->getMessage());
            $emailStatus = false;
        }
        Log::info('Status email: ' . ($emailStatus ? 'true' : 'false'));
        return response()->json([
            'success' => true,
            'kode_uniq' => $kodeUniq,
            'email_status' => (bool)$emailStatus,
            'message' => $emailStatus
                ? 'Siswa berhasil didaftarkan.'
                : 'Siswa berhasil didaftarkan, tetapi email tidak terkirim. Silakan hubungi admin.'
        ]);
    }

    public function keteranganStore(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'nis_nisn' => 'required',
            'kelas' => 'required|string|max:255',
            'alamat' => 'required|string',
            'email' => 'required|email',
            'keperluan' => 'required|string',
            'kode_klasifikasi' => 'required|exists:kode_surats,id',
        ]);

        // Generate kode unik
        $kodeUniq = $this->generateKodeUnik();

        $metadata = [
            'informasi_pribadi' => [
                'nama' => $validatedData['nama'],
                'jenis_kelamin' => $validatedData['jenis_kelamin'],
                'tempat_lahir' => $validatedData['tempat_lahir'],
                'tanggal_lahir' => $validatedData['tanggal_lahir'],
            ],
            'informasi_akademik' => [
                'nis_nisn' => $validatedData['nis_nisn'],
                'kelas' => $validatedData['kelas'],
            ],
            'informasi_kontak' => [
                'alamat' => $validatedData['alamat'],
                'email' => $validatedData['email'],
            ],
            'kode_uniq' => $kodeUniq,
            'keperluan' => $validatedData['keperluan'],
            'tanggal_registrasi' => now()->toDateTimeString(),
        ];

        $tahun = date('Y');
        $lastNumber = SuratKeluar::whereYear('created_at', $tahun)
            ->max('nomor_agenda');
        $nomorAgenda = $lastNumber ? $lastNumber + 1 : 1;
        $formattedNumber = str_pad($nomorAgenda, 4, '0', STR_PAD_LEFT);

        $kodeSurat = KodeSurat::findOrFail($validatedData['kode_klasifikasi']);
        $kodeKlasifikasi = $kodeSurat->kode_klasifikasi;
        $nomorSurat = $kodeKlasifikasi . '/' . $formattedNumber . '/436.7.1/' . $tahun;
        // Buat record 
        SuratKeluar::create([
            'kode_surat_id' => $validatedData['kode_klasifikasi'],
            'nomor_agenda' => $nomorAgenda,
            'no_surat_keluar' => $nomorSurat,
            'kode_pencarian' => $kodeUniq,
            'user_id' => Auth::id(),
            'metadata' => json_encode($metadata)
        ]);

        try {
            Mail::to($validatedData['email'])->send(new MailSuratKeluar($kodeUniq));
            Log::info('Email terkirim ke: ' . $validatedData['email']);
            $emailStatus = true;
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email: ' . $e->getMessage());
            $emailStatus = false;
        }


        Log::info('Status email: ' . ($emailStatus ? 'true' : 'false'));

        return response()->json([
            'success' => true,
            'kode_uniq' => $kodeUniq,
            'email_status' => (bool)$emailStatus,
            'message' => $emailStatus
                ? 'Siswa berhasil didaftarkan.'
                : 'Siswa berhasil didaftarkan, tetapi email tidak terkirim. Silakan hubungi admin.'
        ]);
    }


    protected function generateKodeUnik()
    {
        $prefix = now()->format('Y-m');
        $randomPart = Str::random(4);
        $kodeUnik = strtoupper($prefix . '-' . $randomPart);

        while (SuratKeluar::where('metadata->kode_uniq', $kodeUnik)->exists()) {
            $randomPart = Str::random(4);
            $kodeUnik = strtoupper($prefix . '-' . $randomPart);
        }

        return $kodeUnik;
    }

    public function pengumumanStore(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'perihal' => 'required|string|max:255',
            'pembuka' => 'required|string',
            'body' => 'required|string',
            'kode_klasifikasi' => 'required|exists:kode_surats,id', // Tambahkan validasi untuk kode surat
        ]);

        $metadata = json_encode([
            'perihal' => $validatedData['perihal'],
            'pembuka' => $validatedData['pembuka'],
            'body' => $validatedData['body'],
        ]);


        $tahun = date('Y');
        $lastNumber = SuratKeluar::whereYear('created_at', $tahun)
            ->max('nomor_agenda');
        $nomorAgenda = $lastNumber ? $lastNumber + 1 : 1;
        $formattedNumber = str_pad($nomorAgenda, 4, '0', STR_PAD_LEFT);

        $kodeSurat = KodeSurat::findOrFail($validatedData['kode_klasifikasi']);
        $kodeKlasifikasi = $kodeSurat->kode_klasifikasi;
        $nomorSurat = $kodeKlasifikasi . '/' . $formattedNumber . '/436.7.1/' . $tahun;

        SuratKeluar::create([
            'user_id' => Auth::id(),
            'kode_surat_id' => $validatedData['kode_klasifikasi'],
            'nomor_agenda' => $nomorAgenda,
            'no_surat_keluar' => $nomorSurat,
            'metadata' => $metadata,
        ]);

        return redirect()->back()
            ->with('success', 'Surat pengumuman berhasil disimpan.');
    }

    public function undanganStore(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'kepada' => 'required|string|max:255',
            'kota' => 'required|string|max:255',
            'tempat' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'acara' => 'required|string',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i',
            'alamat' => 'required|string|max:255',
            'kode_klasifikasi' => 'required|exists:kode_surats,id',
        ]);

        $tahun = date('Y');
        $lastNumber = SuratKeluar::whereYear('created_at', $tahun)
            ->max('nomor_agenda');
        $nomorAgenda = $lastNumber ? $lastNumber + 1 : 1;
        $formattedNumber = str_pad($nomorAgenda, 4, '0', STR_PAD_LEFT);

        $kodeSurat = KodeSurat::findOrFail($validatedData['kode_klasifikasi']);
        $kodeKlasifikasi = $kodeSurat->kode_klasifikasi;
        $nomorSurat = $kodeKlasifikasi . '/' . $formattedNumber . '/436.7.1/' . $tahun;

        $metadata = json_encode([
            'kepada' => $validatedData['kepada'],
            'kota' => $validatedData['kota'],
            'tempat' => $validatedData['tempat'],
            'tanggal' => $validatedData['tanggal'],
            'acara' => $validatedData['acara'],
            'waktu_mulai' => $validatedData['waktu_mulai'],
            'waktu_selesai' => $validatedData['waktu_selesai'],
            'alamat' => $validatedData['alamat'],
        ]);


        SuratKeluar::create([
            'user_id' => Auth::id(),
            'kode_surat_id' => $validatedData['kode_klasifikasi'],
            'nomor_agenda' => $nomorAgenda,
            'no_surat_keluar' => $nomorSurat,
            'metadata' => $metadata,
        ]);

        return redirect()->route('keluar.master')
            ->with('success', 'Surat undangan berhasil disimpan.');
    }


    /**
     * Display the specified resource.
     */
    public function show(SuratKeluar $suratKeluar)
    {
        $kodeSurat = KodeSurat::all();
        return view('menu.keluar.detail', compact('suratKeluar', 'kodeSurat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SuratKeluar $suratKeluar)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateKode(Request $request, SuratKeluar $suratKeluar)
    {
        // Validasi input
        $validatedData = $request->validate([
            'kode_klasifikasi' => 'required|exists:kode_surats,id',
        ]);

        $tahun = date('Y');
        $lastNumber = SuratKeluar::whereYear('created_at', $tahun)
            ->max('nomor_agenda');
        $nomorAgenda = $lastNumber ? $lastNumber + 1 : 1;
        $formattedNumber = str_pad($nomorAgenda, 4, '0', STR_PAD_LEFT);

        $kodeSurat = KodeSurat::findOrFail($validatedData['kode_klasifikasi']);
        $kodeKlasifikasi = $kodeSurat->kode_klasifikasi;
        $nomorSurat = $kodeKlasifikasi . '/' . $formattedNumber . '/436.7.1/' . $tahun;

        // Update data
        $suratKeluar->update([
            'kode_surat_id' => $validatedData['kode_klasifikasi'],
            'nomor_agenda' => $nomorAgenda,
            'no_surat_keluar' => $nomorSurat, // Pastikan nama kolom yang diupdate sesuai dengan database
        ]);

        return redirect()->route('keluar.show', $suratKeluar)
            ->with('success', 'Kode dan nomor surat berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SuratKeluar $suratKeluar)
    {
        // Cari nomor agenda terakhir dari database
        $latestAgenda = SuratKeluar::orderBy('nomor_agenda', 'desc')->first();

        // Hapus file dari storage jika ada
        if ($suratKeluar->file_path && Storage::exists('public/' . $suratKeluar->file_path)) {
            Storage::delete('public/' . $suratKeluar->file_path);
        }

        // Jika surat yang dihapus adalah surat dengan nomor agenda terakhir
        if ($latestAgenda && $latestAgenda->id == $suratKeluar->id) {
            // Hapus surat secara permanen
            $suratKeluar->delete();
            $message = 'Surat berhasil dihapus';
        } else {
            // Jika bukan yang terakhir, nonaktifkan saja
            $suratKeluar->update(['status_nomor' => 'nonaktif']);
            $message = 'Data Berhasil dihapus';
        }

        return redirect()->route('keluar.master')
            ->with('success', $message);
    }

    public function uploadTandaTangan(Request $request, SuratKeluar $suratKeluar)
    {
        // Validasi input
        $request->validate([
            'approval_type' => 'required|in:with_stamp,without_stamp',
        ]);

        try {
            $approvalType = $request->input('approval_type');

            // Tentukan sumber file berdasarkan jenis approval
            $sourceImagePath = $approvalType === 'with_stamp'
                ? public_path('img/stempel_ttd.png')
                : public_path('img/ttd_only.png');

            // Nama file: time() + nama file default (karena tidak ada upload dari user)
            $fileName = time() . '_tanda_tangan.png';

            // Path penyimpanan di folder 'tanda-tangan' dalam storage/public
            $filePath = 'tanda-tangan/' . $fileName;
            $targetPath = storage_path('app/public/' . $filePath);

            // Pastikan folder 'tanda-tangan' ada
            if (!file_exists(dirname($targetPath))) {
                mkdir(dirname($targetPath), 0755, true);
            }

            // Hapus file lama jika ada
            if ($suratKeluar->gambar_ttd && Storage::disk('public')->exists($suratKeluar->gambar_ttd)) {
                Storage::disk('public')->delete($suratKeluar->gambar_ttd);
            }

            // Salin file dari /public/img/... ke storage/app/public/tanda-tangan/
            copy($sourceImagePath, $targetPath);

            // Simpan path relatif ke database
            $suratKeluar->update([
                'gambar_ttd' => $filePath,
            ]);

            return redirect()->route('keluar.show', $suratKeluar)
                ->with('success', 'Tanda tangan berhasil disetujui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function hapusTandaTangan(SuratKeluar $suratKeluar)
    {
        try {
            // Hapus file jika ada
            if ($suratKeluar->gambar_ttd && Storage::disk('public')->exists($suratKeluar->gambar_ttd)) {
                Storage::disk('public')->delete($suratKeluar->gambar_ttd);
            }

            // Update database
            $suratKeluar->update([
                'gambar_ttd' => null,
            ]);

            return redirect()->back()->with('success', 'Tanda tangan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function perintahStore(Request $request)
    {
        $validatedData = $request->validate([
            'kode_klasifikasi' => 'required|exists:kode_surats,id',
            'guru_ids' => 'required|array',
            'guru_ids.*' => 'exists:pegawais,id', // Ubah menjadi pegawais sesuai model
            'hari' => 'required|string',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'tugas' => 'required|string',
            'tempat' => 'required|string',
            'alamat' => 'required|string',
        ]);

        $selectedGurus = [];
        foreach ($request->guru_ids as $guruId) {
            $guru = Pegawai::find($guruId);
            if ($guru) {
                $selectedGurus[] = [
                    'id' => $guru->id,
                    'nama' => $guru->nama,
                    'nip' => $guru->nip,
                    'email' => $guru->email,
                    'jabatan' => [
                        'nama' => $guru->jabatan->nama ?? 'Guru',
                    ],
                ];
            }
        }

        $metadata = [
            'guru_ditugaskan' => $selectedGurus,
            'hari' => $validatedData['hari'],
            'tanggal' => $validatedData['tanggal'],
            'waktu_mulai' => $validatedData['waktu_mulai'],
            'tugas' => $validatedData['tugas'],
            'tempat' => $validatedData['tempat'],
            'alamat' => $validatedData['alamat'],
        ];

        // Generate nomor surat
        $tahun = date('Y');
        $lastNumber = SuratKeluar::whereYear('created_at', $tahun)
            ->max('nomor_agenda');
        $nomorAgenda = $lastNumber ? $lastNumber + 1 : 1;
        $formattedNumber = str_pad($nomorAgenda, 4, '0', STR_PAD_LEFT);

        $kodeSurat = KodeSurat::findOrFail($validatedData['kode_klasifikasi']);
        $kodeKlasifikasi = $kodeSurat->kode_klasifikasi;
        $nomorSurat = $kodeKlasifikasi . '/' . $formattedNumber . '/436.7.1/' . $tahun;

        SuratKeluar::create([
            'user_id' => Auth::id(),
            'kode_surat_id' => $validatedData['kode_klasifikasi'],
            'nomor_agenda' => $nomorAgenda,
            'no_surat_keluar' => $nomorSurat,
            'metadata' => json_encode($metadata),
            'status' => 'draft', // Sesuaikan dengan status yang diperlukan
            'status_nomor' => 'aktif', // Pastikan status_nomor diset ke 'aktif'
        ]);

        return redirect()->route('keluar.master')
            ->with('success', 'Surat perintah berhasil disimpan.');
    }

    public function generateDanKirim(SuratKeluar $suratKeluar)
    {
        try {
            // Cek apakah surat dalam status draft
            // if ($suratKeluar->status !== 'seleasi') {
            //     return redirect()->back()
            //         ->with('error', 'Hanya surat dengan status darft yang dapat diproses!');
            // }

            // Generate surat menggunakan service
            $pdfInfo = $this->suratGeneratorService->generateSurat($suratKeluar);

            // Kirim surat menggunakan service
            $emailSent = $this->emailService->kirimSurat($suratKeluar, $pdfInfo);

            if ($emailSent) {
                // Update status surat menjadi selesai
                $suratKeluar->update([
                    'status' => 'selesai',
                ]);

                return redirect()->route('keluar.master')
                    ->with('success', 'Surat berhasil digenerate, dikirim, dan status diperbarui!');
            } else {
                // PDF berhasil dibuat tapi email gagal terkirim
                return redirect()->back()
                    ->with('warning', 'Surat berhasil digenerate tetapi gagal dikirim. Status surat: published');
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    public function generateOnly(SuratKeluar $suratKeluar)
    {
        try {
            // Pastikan surat masih berstatus draft
            if ($suratKeluar->status !== 'draft') {
                return redirect()->back()
                    ->with('error', 'Hanya surat dengan status draft yang dapat digenerate!');
            }

            // Generate surat dan simpan PDF
            $pdfInfo = $this->suratGeneratorService->generateSurat($suratKeluar);

            if (!$pdfInfo || !isset($pdfInfo['path'])) {
                return redirect()->back()
                    ->with('error', 'Surat gagal digenerate. File PDF tidak ditemukan.');
            }

            // Update status surat menjadi selesai
            $suratKeluar->update([
                'status' => 'selesai',
                'nama_file' => basename($pdfInfo['path']) // opsional: jika kamu simpan nama file
            ]);

            return redirect()->route('keluar.master')
                ->with('success', 'Surat berhasil digenerate dan status diperbarui!');
        } catch (\Exception $e) {
            // Tangkap error dan kembalikan pesan ke user
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat generate surat: ' . $e->getMessage());
        }
    }

    public function revisi(Request $request, SuratKeluar $suratKeluar)
    {
        $request->validate([
            'komentar' => 'required|string'
        ]);

        $suratKeluar->status = 'revisi';
        $suratKeluar->komentar = $request->komentar;
        $suratKeluar->save();

        return redirect()->back()->with('success', 'Surat telah ditandai untuk revisi');
    }

    public function editPerintah(SuratKeluar $suratKeluar)
    {
        // Pastikan surat yang dibuka adalah surat perintah
        if ($suratKeluar->jenis_surat !== 'perintah') {
            return redirect()->route('keluar.show', $suratKeluar)
                ->with('error', 'Tipe surat tidak sesuai.');
        }

        $kodeSurat = KodeSurat::all();
        $pegawai = Pegawai::all();
        $metadata = json_decode($suratKeluar->metadata, true) ?? [];

        // Ambil data guru untuk dropdown
        $allGuru = Pegawai::orderBy('nama')->get();

        // Ambil ID guru yang sudah dipilih sebelumnya
        $selectedGuruIds = [];
        if (isset($metadata['guru_ditugaskan']) && is_array($metadata['guru_ditugaskan'])) {
            foreach ($metadata['guru_ditugaskan'] as $guru) {
                if (isset($guru['id'])) {
                    $selectedGuruIds[] = $guru['id'];
                }
            }
        }

        return view('menu.keluar.edit.perintah', compact('suratKeluar', 'metadata', 'allGuru', 'selectedGuruIds', 'kodeSurat','pegawai'));
    }

    public function editRekomendasi(SuratKeluar $suratKeluar)
    {
        // Pastikan surat yang dibuka adalah surat rekomendasi
        if ($suratKeluar->jenis_surat !== 'rekomendasi') {
            return redirect()->route('keluar.show', $suratKeluar)
                ->with('error', 'Tipe surat tidak sesuai.');
        }
        $kodeSurat = KodeSurat::all();
        $metadata = json_decode($suratKeluar->metadata, true) ?? [];

        return view('menu.keluar.edit.rekomendasi', compact('suratKeluar', 'metadata','kodeSurat'));
    }


    public function editKeterangan(SuratKeluar $suratKeluar)
    {
        // Pastikan surat yang dibuka adalah surat keterangan
        if ($suratKeluar->jenis_surat !== 'keterangan') {
            return redirect()->route('keluar.show', $suratKeluar)
                ->with('error', 'Tipe surat tidak sesuai.');
        }

        $kodeSurat = KodeSurat::all();
        $metadata = json_decode($suratKeluar->metadata, true) ?? [];

        return view('menu.keluar.edit.keterangan', compact('suratKeluar', 'metadata', 'kodeSurat'));
    }


    public function editUndangan(SuratKeluar $suratKeluar)
    {
        // Pastikan surat yang dibuka adalah surat undangan
        if ($suratKeluar->jenis_surat !== 'undangan') {
            return redirect()->route('keluar.show', $suratKeluar)
                ->with('error', 'Tipe surat tidak sesuai.');
        }

        $kodeSurat = KodeSurat::all();
        $metadata = json_decode($suratKeluar->metadata, true) ?? [];

        return view('menu.keluar.edit.undangan', compact('suratKeluar', 'metadata', 'kodeSurat'));
    }

    public function updateRekomendasi(Request $request, SuratKeluar $suratKeluar)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'nis_nisn' => 'required',
            'kelas' => 'required|string|max:255',
            'alamat' => 'required|string',
            'email' => 'required|email',
            'nama_acara' => 'required|string',
            'penyelenggara' => 'required|string',
            'tanggal_acara' => 'required|string',
            'kode_klasifikasi' => 'required|exists:kode_surats,id',
        ]);

        // Siapkan metadata baru (struktur konsisten dengan store())
        $metadata = [
            'informasi_pribadi' => [
                'nama' => $validatedData['nama'],
                'jenis_kelamin' => $validatedData['jenis_kelamin'],
                'tempat_lahir' => $validatedData['tempat_lahir'],
                'tanggal_lahir' => $validatedData['tanggal_lahir'],
            ],
            'informasi_akademik' => [
                'nis_nisn' => $validatedData['nis_nisn'],
                'kelas' => $validatedData['kelas'],
            ],
            'informasi_kontak' => [
                'alamat' => $validatedData['alamat'],
                'email' => $validatedData['email'],
            ],
            'informasi_acara' => [
                'nama_acara' => $validatedData['nama_acara'],
                'penyelenggara' => $validatedData['penyelenggara'],
                'tanggal_acara' => $validatedData['tanggal_acara'],
            ],
            'tanggal_update' => now()->toDateTimeString(),
        ];

        // Cek apakah kode_surat_id berubah
        if ($suratKeluar->kode_surat_id != $validatedData['kode_klasifikasi']) {
            $suratKeluar->kode_surat_id = $validatedData['kode_klasifikasi'];

            // Update nomor surat berdasarkan klasifikasi baru
            $tahun = date('Y');
            $formattedNumber = str_pad($suratKeluar->nomor_agenda, 4, '0', STR_PAD_LEFT);

            $kodeSurat = KodeSurat::findOrFail($validatedData['kode_klasifikasi']);
            $kodeKlasifikasi = $kodeSurat->kode_klasifikasi;
            $nomorSurat = $kodeKlasifikasi . '/' . $formattedNumber . '/436.7.1/' . $tahun;

            $suratKeluar->no_surat_keluar = $nomorSurat;
        }

        // Simpan metadata dan status
        $suratKeluar->metadata = json_encode($metadata);
        $suratKeluar->status = 'pending';
        $suratKeluar->komentar = null;
        $suratKeluar->save();

        return redirect()->route('keluar.master')->with('success', 'Surat berhasil diperbarui');
    }


    public function updateKeterangan(Request $request, SuratKeluar $suratKeluar)
    {

        // Validasi input
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'nis_nisn' => 'required',
            'kelas' => 'required|string|max:255',
            'alamat' => 'required|string',
            'email' => 'required|email',
            'keperluan' => 'required|string',
            'kode_klasifikasi' => 'required|exists:kode_surats,id',
        ]);

        // Decode existing metadata
        $metadata = json_decode($suratKeluar->metadata, true);

        // Update metadata with new values
        $metadata['informasi_pribadi'] = [
            'nama' => $validatedData['nama'],
            'jenis_kelamin' => $validatedData['jenis_kelamin'],
            'tempat_lahir' => $validatedData['tempat_lahir'],
            'tanggal_lahir' => $validatedData['tanggal_lahir'],
        ];

        $metadata['informasi_akademik'] = [
            'nis_nisn' => $validatedData['nis_nisn'],
            'kelas' => $validatedData['kelas'],
        ];

        $metadata['informasi_kontak'] = [
            'alamat' => $validatedData['alamat'],
            'email' => $validatedData['email'],
        ];

        $metadata['keperluan'] = $validatedData['keperluan'];
        $metadata['tanggal_update'] = now()->toDateTimeString();

        // Handle potential change of kode klasifikasi
        if ($suratKeluar->kode_surat_id != $validatedData['kode_klasifikasi']) {
            // Update kode_surat_id
            $suratKeluar->kode_surat_id = $validatedData['kode_klasifikasi'];

            // Update nomor surat if kode klasifikasi changes
            $tahun = date('Y');
            $formattedNumber = str_pad($suratKeluar->nomor_agenda, 4, '0', STR_PAD_LEFT);

            $kodeSurat = KodeSurat::findOrFail($validatedData['kode_klasifikasi']);
            $kodeKlasifikasi = $kodeSurat->kode_klasifikasi;
            $nomorSurat = $kodeKlasifikasi . '/' . $formattedNumber . '/436.7.1/' . $tahun;

            $suratKeluar->no_surat_keluar = $nomorSurat;
        }

        // Update the record
        $suratKeluar->metadata = json_encode($metadata);
        $suratKeluar->status = 'pending';
        $suratKeluar->komentar = null;
        $suratKeluar->save();

        // Log the action
        Log::info('Surat keterangan updated: ' . $suratKeluar->id . ' by user: ' . Auth::id());

        return response()->json([
            'success' => true,
            'message' => 'Data surat keterangan berhasil diperbarui.'
        ]);
    }

    public function updateUndangan(Request $request, SuratKeluar $suratKeluar)
    {
        // Validasi input
        $validatedData = $request->validate([
            'kepada' => 'required|string|max:255',
            'kota' => 'required|string|max:255',
            'tempat' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'acara' => 'required|string',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after_or_equal:waktu_mulai',
            'alamat' => 'required|string|max:255',
            'kode_klasifikasi' => 'required|exists:kode_surats,id',
        ]);

        // Persiapkan metadata
        $metadata = [
            'kepada' => $validatedData['kepada'],
            'kota' => $validatedData['kota'],
            'tempat' => $validatedData['tempat'],
            'tanggal' => $validatedData['tanggal'],
            'acara' => $validatedData['acara'],
            'waktu_mulai' => $validatedData['waktu_mulai'],
            'waktu_selesai' => $validatedData['waktu_selesai'],
            'alamat' => $validatedData['alamat'],
        ];

        // Cek apakah kode_surat_id berubah
        if ($suratKeluar->kode_surat_id != $validatedData['kode_klasifikasi']) {
            $suratKeluar->kode_surat_id = $validatedData['kode_klasifikasi'];

            // Update nomor surat berdasarkan klasifikasi baru
            $tahun = date('Y');
            $formattedNumber = str_pad($suratKeluar->nomor_agenda, 4, '0', STR_PAD_LEFT);
            $kodeSurat = KodeSurat::findOrFail($validatedData['kode_klasifikasi']);
            $kodeKlasifikasi = $kodeSurat->kode_klasifikasi;
            $nomorSurat = $kodeKlasifikasi . '/' . $formattedNumber . '/436.7.1/' . $tahun;
            $suratKeluar->no_surat_keluar = $nomorSurat;
        }

        // Simpan metadata dan perbarui surat
        $suratKeluar->metadata = json_encode($metadata);
        $suratKeluar->updated_at = now();
        $suratKeluar->status = 'pending';
        $suratKeluar->komentar = null;
        $suratKeluar->save();

        return redirect()->route('keluar.master')
            ->with('success', 'Surat undangan berhasil diperbarui.');
    }

    public function updatePerintah(Request $request, SuratKeluar $suratKeluar)
    {
        // Validasi input
        $validatedData = $request->validate([
            'kode_klasifikasi' => 'required|exists:kode_surats,id',
            'guru_ids' => 'required|array',
            'guru_ids.*' => 'exists:pegawais,id',
            'hari' => 'required|string',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'tugas' => 'required|string',
            'tempat' => 'required|string',
            'alamat' => 'required|string',
        ]);

        // Persiapkan data guru
        $selectedGurus = [];
        foreach ($request->guru_ids as $guruId) {
            $guru = Pegawai::find($guruId);
            if ($guru) {
                $selectedGurus[] = [
                    'id' => $guru->id,
                    'nama' => $guru->nama,
                    'nip' => $guru->nip,
                    'email' => $guru->email,
                    'jabatan' => [
                        'nama' => $guru->jabatan->nama ?? 'Guru',
                    ],
                ];
            }
        }

        // Persiapkan metadata
        $metadata = [
            'guru_ditugaskan' => $selectedGurus,
            'hari' => $validatedData['hari'],
            'tanggal' => $validatedData['tanggal'],
            'waktu_mulai' => $validatedData['waktu_mulai'],
            'tugas' => $validatedData['tugas'],
            'tempat' => $validatedData['tempat'],
            'alamat' => $validatedData['alamat'],
        ];

        // Cek apakah kode_surat_id berubah
        if ($suratKeluar->kode_surat_id != $validatedData['kode_klasifikasi']) {
            $suratKeluar->kode_surat_id = $validatedData['kode_klasifikasi'];

            // Update nomor surat berdasarkan klasifikasi baru
            $tahun = date('Y');
            $formattedNumber = str_pad($suratKeluar->nomor_agenda, 4, '0', STR_PAD_LEFT);
            $kodeSurat = KodeSurat::findOrFail($validatedData['kode_klasifikasi']);
            $kodeKlasifikasi = $kodeSurat->kode_klasifikasi;
            $nomorSurat = $kodeKlasifikasi . '/' . $formattedNumber . '/436.7.1/' . $tahun;
            $suratKeluar->no_surat_keluar = $nomorSurat;
        }

        // Simpan metadata dan perbarui surat
        $suratKeluar->metadata = json_encode($metadata);
        $suratKeluar->updated_at = now();
        $suratKeluar->status = 'pending';
        $suratKeluar->komentar = null;
        $suratKeluar->save();

        return redirect()->route('keluar.master')
            ->with('success', 'Surat perintah berhasil diperbarui.');
    }
}
