<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\siswaM;
use App\Models\desainkartuM;
use Barryvdh\DomPDF\Facade\Pdf;

class superadminC extends Controller
{
    public function admin(Request $request)
    {
        return view('pages.admin', [
            "judul" => "Admin"
        ]);
    }
    public function cetakkartu(Request $request)
    {
        return view('pages.cetakkartu', [
            "judul" => "Cetak Kartu"
        ]);
    }
    public function cetakkartupdf(Request $request, $idkelas, $idjurusan, $idinstansi)
    {
        
        $idinstansi = session()->get('idinstansi');
        if(empty($idinstansi)) {
            $idinstansi = $request->idinstansi;
        }

        $siswa = siswaM::
        where("idkelas", $idkelas)
        ->where("idjurusan", $idjurusan)
        ->where("idinstansi", $request->idinstansi)
        ->paginate(20);

        $detaildesainkartu = ['desainkartu' => 'solid',
            'warnadepan' => '#1a365d',
            'warnabelakang' => '#2b6cb0',
            'warnatextdepan' => '#ffffff',
            'warnatextbelakang' => '#ffffff',
            'warnaborder' => '#ffffff',
            'tebalborder' => 3,
            'radiusborder' => 50,
            'gambardepan' => 'profil.png',
            'gambarbelakang' => 'profil.png',
        ];


        $deskripsi = [
            "judul" => "KETENTUAN KARTU",
            "deskripsi" => "Kartu ini adalah milik organisasi dan tidak dapat dipindahtangankan.
Jika menemukan kartu ini, harap kembalikan ke kantor sekretariat terdekat.
Kartu wajib dibawa saat menghadiri kegiatan resmi.",
        ];

        $pillbox = [
            "jurusan",
            "instansi",
            "alamat",
        ];


        $desainkartu = desainkartuM::where("idinstansi", $idinstansi);
        if($desainkartu->count() > 0) {
            $detail = $desainkartu->first()->detaildesainkartu()?->first()?->toArray();
            $detaildesainkartu = array_merge($detaildesainkartu, $detail);
            $deskripsi = $desainkartu->first()->deskripsikartu()->select("judul", "deskripsi")?->first()?->toArray();
            $deskripsi = array_merge($deskripsi, $deskripsi);
            $desain = $desainkartu->first();

            $desain = $desainkartu->first()->datadesainkartu();
            if($desain->count() > 0) {
               $pillbox = $desain->first()->pluck('identitas')->toArray();
            }
        }

        $pdf = Pdf::loadView('pages.laporan.cetakkartu', [
            'siswa' => $siswa,
            'detaildesainkartu' => $detaildesainkartu,
            'pillbox' => $pillbox,
            'deskripsi' => $deskripsi,
        ])->setOption('isRemoteEnabled', true);

        return $pdf->stream("Laporan Halaman $request->page.pdf");
    }

    
    
}
