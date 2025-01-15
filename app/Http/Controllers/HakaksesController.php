<?php

namespace App\Http\Controllers;

use App\Models\hakakses;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class HakaksesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index(Request $request)
    // {
    //     $search = $request->get('pencarian');
    //     if ($search) {
    //         $data['hakakses'] = hakakses::where('id', 'like', "%{$search}%")
    //             ->orWhere('role', 'like', "%{$search}%")
    //             ->orWhere('name', 'like', "%{$search}%")
    //             ->get();
    //     } else {
    //         $data['hakakses'] = hakakses::all();
    //     }
    //     return view('layouts.hakakses.index', $data);
    // }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Hakakses::select('*');

            return DataTables::of($data)
            ->filter(function ($query) use ($request) {
                if ($request->has('pencarian')) {
                    $query->where('id', 'like', "%{$request->pencarian}%")
                        ->orWhere('role', 'like', "%{$request->pencarian}%")
                        ->orWhere('name', 'like', "%{$request->pencarian}%");
                }
            })
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '
                    <a href="' . route('hakakses.edit', $row->id) . '" class="btn btn-primary">Edit</a>
                    <button onclick="confirmDelete(\'' . route('hakakses.delete',
                        $row->id
                    ) . '\')" class="btn btn-danger">Delete</button>
                ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('layouts.hakakses.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'sometimes|in:user,admin,superadmin'
        ]);

        try {
            // Buat user baru
            $hakakses = new Hakakses();
            $hakakses->name = $validatedData['name'];
            $hakakses->email = $validatedData['email'];
            $hakakses->password = bcrypt($validatedData['password']);

            // Set role, default ke 'user' jika tidak ditentukan
            $hakakses->role = $request->input('role', 'user');

            $hakakses->save();

            // Redirect dengan pesan sukses
            return redirect()->route('hakakses.index')
            ->with('success', 'User berhasil ditambahkan');
        } catch (\Exception $e) {
            // Tangani error
            return redirect()->back()
                ->with('error', 'Gagal menambahkan user: ' . $e->getMessage())
                ->withInput();
        }
    }
    

    /**
     * Display the specified resource.
     */
    public function show(hakakses $hakakses)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        $hakakses = hakakses::find($id);
        return view('layouts.hakakses.edit', compact('hakakses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, hakakses $hakakses, $id)
    {
        //
        $hakakses = hakakses::find($id);
        $hakakses->role = $request->role;
        $hakakses->save();
    return redirect()->route('hakakses.index')->with('success', 'Update Berhasil');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // Cari user yang akan dihapus
            $hakakses = Hakakses::findOrFail($id);

            // Periksa apakah user adalah superadmin
            if ($hakakses->role == 'superadmin') {
                // Hitung jumlah superadmin
                $superadminCount = Hakakses::where('role', 'superadmin')->count();

                // Jika hanya tersisa 1 superadmin, tolak penghapusan
                if ($superadminCount <= 1) {
                    return redirect()->route('hakakses.index')
                        ->with('error', 'Tidak dapat menghapus satu-satunya superadmin');
                }
            }

            // Lakukan penghapusan
            $hakakses->delete();

            return redirect()->route('hakakses.index')
                ->with('status', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('hakakses.index')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
