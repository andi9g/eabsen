<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class adminC extends Controller
{
    public function perangkat()
    {
        return view("pages.perangkat", [
            "judul" => "Perangkat Absen"
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
    public function walikelas(Request $request)
    {
        return view('pages.walikelas', [
            "judul" => "Wali Kelas"
        ]);
    }
    public function registerasi()
    {
        return view("pages.registerasi", [
            "judul" => "Registerasi Kartu"
        ]);
    }
    public function import()
    {
        return view("pages.import", [
            "judul" => "Import Data Siswa"
        ]);
    }
    public function siswa()
    {
        return view("pages.siswa", [
            "judul" => "Data Siswa"
        ]);
    }
    public function rombel()
    {
        return view("pages.rombel", [
            "judul" => "Data Rombel"
        ]);
    }
    public function semester()
    {
        return view("pages.semester", [
            "judul" => "Data Semester"
        ]);
    }
    public function instansi()
    {
        return view("pages.instansi", [
            "judul" => "Data Instansi"
        ]);
    }
    public function jamoperasional()
    {
        return view("pages.jamoperasional", [
            "judul" => "Jam Operasional"
        ]);
    }
    public function desainkartu()
    {
        return view("pages.desainkartu", [
            "judul" => "Desain Kartu"
        ]);
    }
}
