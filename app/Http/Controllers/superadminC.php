<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class superadminC extends Controller
{
    public function admin(Request $request)
    {
        return view('pages.admin', [
            "judul" => "Admin"
        ]);
    }
    public function pegawai(Request $request)
    {
        return view('pages.pegawai', [
            "judul" => "Pegawai"
        ]);
    }
    public function user(Request $request)
    {
        return view('pages.user', [
            "judul" => "User"
        ]);
    }
}
