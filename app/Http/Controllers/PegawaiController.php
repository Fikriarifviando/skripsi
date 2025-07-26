<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\Pegawai;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;


class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Pegawai::with('jabatan'); // Menggunakan relasi 'jabatan'

            return DataTables::of($data)
                ->filter(function ($query) use ($request) {
                    if ($request->has('pencarian')) {
                        $query->where(function ($q) use ($request) {
                            $q->where('nama', 'like', "%{$request->pencarian}%")
                                ->orWhere('nip', 'like', "%{$request->pencarian}%")
                                ->orWhere('email', 'like', "%{$request->pencarian}%")
                                // Pencarian berdasarkan nama jabatan melalui relasi
                                ->orWhereHas('jabatan', function ($j) use ($request) {
                                    $j->where('nama', 'like', "%{$request->pencarian}%");
                                });
                        });
                    }
                })
                ->addIndexColumn()
                ->addColumn('jabatan_nama', function ($row) {
                    // Menampilkan nama jabatan dari relasi
                    return $row->jabatan ? $row->jabatan->nama : '-';
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '
                <a href="' . route('data-pegawai.edit', $row->id) . '" class="btn btn-primary">Edit</a>
                <button onclick="confirmDelete(\'' . route('data-pegawai.delete', $row->id) . '\')" class="btn btn-danger">Delete</button>
                ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('layouts.hakakses.pegawai.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil semua data jabatan untuk ditampilkan di dropdown
        $jabatans = Jabatan::all();
        return view('layouts.hakakses.pegawai.create', compact('jabatans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:pegawais,email',
            'jabatan_id' => 'required|exists:jabatans,id', // Ganti 'jabatan' dengan 'jabatan_id'
            'nip' => 'required|string|min:10|max:20|unique:pegawais,nip|regex:/^[0-9]+$/'
        ]);

        try {
            Pegawai::create($validatedData);
            return redirect()->route('data-pegawai.index')->with('success', 'Pegawai berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan Pegawai: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Pegawai $pegawai)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pegawai $pegawai)
    {
        $jabatans = Jabatan::all();
        return view('layouts.hakakses.pegawai.edit', compact('pegawai', 'jabatans'));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pegawai $pegawai)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:pegawais,email,' . $pegawai->id,
            'jabatan_id' => 'required|exists:jabatans,id',
            'nip' => 'required|string|min:10|max:20|unique:pegawais,nip,' . $pegawai->id . '|regex:/^[0-9]+$/'
        ]);

        try {
            $pegawai->update($validatedData);
            return redirect()->route('data-pegawai.index')->with('success', 'Pegawai berhasil diupdate');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengupdate Pegawai: ' . $e->getMessage())->withInput();
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pegawai $pegawai)
    {
        try {
            $pegawai->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            }

            return redirect()->route('data-pegawai.index')
            ->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus data: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('data-pegawai.index')
            ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
