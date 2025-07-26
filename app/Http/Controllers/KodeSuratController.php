<?php

namespace App\Http\Controllers;

use App\Models\KodeSurat;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class KodeSuratController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = KodeSurat::select('*');
            return DataTables::of($data)
                ->filter(function ($query) use ($request) {
                    if ($request->has('pencarian')) {
                        $query->where(function ($q) use ($request) {
                            $q->where('kode_klasifikasi', 'like', "%{$request->pencarian}%")
                            ->orWhere('nama_kode', 'like', "%{$request->pencarian}%");
                        });
                    }
                })
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '
                    <a href="' . route('kode-surat.edit', $row->id) . '" class="btn btn-primary">Edit</a>
                    
                ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('layouts.hakakses.kode-surat.index-kode');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('layouts.hakakses.kode-surat.create-kode');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kode_klasifikasi' => 'required|string|max:10|unique:kode_surats,kode_klasifikasi',
            'nama_kode' => 'required|string|max:50',
        ]);

        try {
            KodeSurat::create($validatedData);
            return redirect()->route('kode-surat.index')->with('success', 'Kode Klasifikasi berhasil ditambahkan');
        } catch (\Exception $e) {
            // Tangani error
            return redirect()->back()->with('error', 'Gagal menambahkan Kode Klasifikasi: ' . $e->getMessage())->withInput();
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(KodeSurat $kodeSurat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( KodeSurat $kodeSurat)
    {
        return view('layouts.hakakses.kode-surat.edit-kode', compact('kodeSurat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KodeSurat $kodeSurat)
    {

        $request->validate([
            'kodeKlasifikasi' => 'required|string|max:10|unique:kode_surats,kode_klasifikasi,' . $kodeSurat->id,
            'namaKode' => 'required|string|max:50'
        ]);

        $kodeSurat->update([
            'kode_klasifikasi' => $request->kodeKlasifikasi,
            'nama_kode' => $request->namaKode
        ]);
        return redirect()->route('kode-surat.index')->with('success', 'Kode Surat berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy($id)
    {
        // try {
        //     // Cari user yang akan dihapus
        //     $kodeSurat = KodeSurat::findOrFail($id);
        //     // Lakukan penghapusan
        //     $kodeSurat->delete();

        //     return redirect()->route('kode-surat.index')
        //     ->with('status', 'Data berhasil dihapus');
        // } catch (\Exception $e) {
        //     return redirect()->route('kode-surat.index')
        //     ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        // }
    }
}
