<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function blank()
    {
        return view('layouts.blank-page');
    }

    public function suratMasuk()
    {
        return view('menu.surat-masuk');
    }

    public function suratdisposisi()
    {
        return view('menu.surat-disposisi');
    }
    public function suratkeluarPerintah()
    {
        return view('menu.surat-keluar-perintah');
    }
}
