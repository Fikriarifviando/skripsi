<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use App\Models\KodeSurat;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class SuratMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = SuratMasuk::with('kodeSurat')
                ->select('surat_masuks.*')
                ->orderBy('created_at', 'desc');

            return DataTables::of($data)
                ->filter(function ($query) use ($request) {
                    if ($request->has('pencarian')) {
                        $query->where(function ($q) use ($request) {
                            $q->where('no_surat', 'like', "%{$request->pencarian}%")
                                ->orWhere('perihal', 'like', "%{$request->pencarian}%")
                                ->orWhere('asal_surat', 'like', "%{$request->pencarian}%")
                                ->orWhereHas('kodeSurat', function ($q) use ($request) {
                                    $q->where('kode_klasifikasi', 'like', "%{$request->pencarian}%")
                                        ->orWhere('nama_kode', 'like', "%{$request->pencarian}%");
                                });
                        });
                    }
                })
                ->addIndexColumn()
                ->addColumn('status_disposisi', function ($row) {
                    if ($row->status_disposisi === 'belum_ditentukan') {
                        return '<span class="badge badge-warning">Belum Ditentukan</span>';
                    } elseif ($row->status_disposisi === 'tidak_perlu_disposisi') {
                        return '<span class="badge badge-secondary">Tidak Perlu Disposisi</span>';
                    } elseif ($row->status_disposisi === 'sudah_disposisi') {
                        return '<span class="badge badge-success">Sudah Disposisi</span>';
                    }
                })
                ->addColumn('kode_klasifikasi', function ($row) {
                    return $row->kodeSurat->kode_klasifikasi . ' - ' . $row->kodeSurat->nama_kode;
                })
                ->addColumn('action', function ($row) {
                    if(Auth::user()->role == 'superadmin'){
                        return '<div class="btn-group" role="group">
                                    <a href="' . route('surat-masuk.show', $row) . '" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Show
                                    </a>
                                    
                                    <a href="' . route('surat-masuk.edit', $row) . '" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <button onclick="confirmDelete(\'' . route('surat-masuk.delete', $row) . '\')" 
                                            class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </div>';

                    } else {
                        return '<div class="btn-group" role="group">
                                    <a href="' . route('surat-masuk.show', $row) . '" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Show
                                    </a>
                                </div>';

                    }
                })
                ->rawColumns(['file', 'action','status_disposisi'])
                ->make(true);
        }

        return view('menu.masuks.surat-masuk');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kodeSurat = KodeSurat::all();
        return view('menu.masuks.create', compact('kodeSurat'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'asal_surat' => 'required|string|max:150',
            'nomor_surat' => 'required|string|max:150',
            'perihal' => 'required|string',
            'kode_klasifikasi' => 'required|exists:kode_surats,id',
            'tanggal' => 'required|date|before_or_equal:' . now()->format('Y-m-d'),
            'file_surat' => 'required|file|mimes:pdf|max:2048', // maksimal 2MB
        ]);

        try {
            if ($request->hasFile('file_surat')) {
                $file = $request->file('file_surat');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('surat-masuk', $fileName, 'public');
                SuratMasuk::create([
                    'user_id' => Auth::id(),
                    'kode_surat_id' => $validated['kode_klasifikasi'],
                    'no_surat' => $validated['nomor_surat'],
                    'perihal' => $validated['perihal'],
                    'tgl_terima' => $validated['tanggal'],
                    'asal_surat' => $validated['asal_surat'],
                    'path_file' => $filePath,
                    'nama_file' => $fileName,
                ]);

                return redirect()
                    ->route('surat-masuk.index')
                    ->with('success', 'Surat masuk berhasil ditambahkan!');
            }
        } catch (\Exception $e) {
            // Hapus file jika upload gagal
            if (isset($filePath) && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SuratMasuk $suratMasuk)
    {
        $kodeSurat = KodeSurat::all();
        $pegawais = Pegawai::all();
        return view('menu.masuks.detail', compact('suratMasuk', 'kodeSurat', 'pegawais'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SuratMasuk $suratMasuk)
    {
        $kodeSurat = KodeSurat::all();
        return view('menu.masuks.edit', compact('suratMasuk', 'kodeSurat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SuratMasuk $suratMasuk)
    {
        $request->validate([
            'asal_surat' => 'required',
            'nomor_surat' => 'required',
            'perihal' => 'required',
            'kode_klasifikasi' => 'required|exists:kode_surats,id',
            'tanggal' => 'required|date|before_or_equal:today',
            'file_surat' => 'nullable|mimes:pdf,doc,docx|max:10240',
        ]);

        $data = [
            'asal_surat' => $request->asal_surat,
            'no_surat' => $request->nomor_surat,
            'perihal' => $request->perihal,
            'kode_surat_id' => $request->kode_klasifikasi, 
            'tgl_terima' => $request->tanggal,
        ];
        $suratMasuk->update($data);

        if ($request->hasFile('file_surat')) {
            if ($suratMasuk->path_file && Storage::exists($suratMasuk->path_file)) {
                Storage::delete($suratMasuk->path_file);
            }

            $file = $request->file('file_surat');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('public/surat_masuk', $fileName);
            $suratMasuk->update([
                'nama_file' => $fileName,
                'path_file' => $filePath
            ]);
        }
        return redirect()->route('surat-masuk.index')
            ->with('success', 'Surat masuk berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SuratMasuk $suratMasuk)  // Menggunakan Route Model Binding
    {
        // Hapus file dari storage
        if (Storage::disk('public')->exists($suratMasuk->path_file)) {
            Storage::disk('public')->delete($suratMasuk->path_file);
        }

        // Hapus record dari database
        $suratMasuk->delete();

        return redirect()->route('surat-masuk.index')
            ->with('success', 'Surat masuk berhasil dihapus');
    }
    public function updateStatus(Request $request, SuratMasuk $suratMasuk)
    {
        // Validasi input berdasarkan nilai yang digunakan di view
        $request->validate([
            'status_disposisi' => 'required|in:belum_ditentukan,sudah_disposisi,tidak_perlu_disposisi'
        ]);

        // Update status disposisi
        $suratMasuk->status_disposisi = $request->status_disposisi;
        $suratMasuk->save();

        // Kembalikan response JSON untuk AJAX
        return response()->json([
            'success' => true,
            'message' => 'Status disposisi berhasil diperbarui.'
        ]);
    }

    public function disposisi(Request $request, SuratMasuk $suratMasuk)
    {
        $validated = $request->validate([
            'pegawai_ids' => 'required|array',
            'pegawai_ids.*' => 'exists:pegawais,id',
            'catatan' => 'nullable|string'
        ]);
        // Simpan ke tabel pivot (surat_masuk_pegawai)
        $suratMasuk->pegawais()->sync($validated['pegawai_ids']);
        foreach ($validated['pegawai_ids'] as $pegawaiId) {
            $suratMasuk->pegawais()->updateExistingPivot($pegawaiId, [
                'catatan' => $validated['catatan']
            ]);
        }
        $suratMasuk->update(['status_disposisi' => 'sudah_disposisi']);
        return redirect()->route('surat-masuk.show', $suratMasuk)->with('success', 'Disposisi berhasil disimpan');
    }


}
