<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\instansiM;
use Session;

class PwaController extends Controller
{
    public function manifest(Request $request)
    {
        $instansi = instansiM::findOrFail($request->idinstansi);
        $login = url('/login');
        $icon192 = "/disk-s3/pwa/192/$instansi->logo";
        $icon512 = "/disk-s3/pwa/512/$instansi->logo";

        $manifest = [
                "name"=> "Absensi Digital - $instansi->namainstansi",
                "short_name"=> "Absensi Digital",
                "start_url"=> $login,
                "display"=> "standalone",
                "background_color"=> "#ffffff",
                "theme_color"=> "#16a34a",
                "icons"=> [
                    [
                        "src"=> $icon192,
                        "sizes"=> "192x192",
                        "type"=> "image/png"
                    ],
                    [
                        "src"=> $icon512,
                        "sizes"=> "512x512",
                        "type"=> "image/png"
                    ]
                ]
        ];

        return response()->json($manifest)
            ->header('Content-Type', 'application/manifest+json; charset=utf-8');
    }

    public function download()
    {
        return view("pages.download", [
            "judul" => "Aplikasi Absensi Digital Mobile",
        ]);
    }
}
