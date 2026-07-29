<?php

namespace App\Http\Controllers;

use App\Models\siswaM;
use App\Models\absensiswaM;
use App\Models\kelasM;
use App\Models\jurusanM;
use App\Models\walikelasM;
use App\Models\pengaturanM;
use App\Models\hariliburM;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Barryvdh\DomPDF\Facade\Pdf;

class pegawaiC extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function absensiswa(Request $request)
    {
        return view("pages.absensiswa", [
            "judul" => "Absen Siswa"
        ]);
    }
    public function cetakabsensiswa(Request $request)
    {
        $akses = auth()->user()->akses->akses ?? "user";
        $error = true;
        if($akses == "pegawai") {
            $error = walikelasM::where([
                "idinstansi" => session("idinstansi"),
                "idsemester" => session("semester"),
            ])->doesntExist();
        }elseif($akses == "admin" || $akses == "kepsek" || $akses == "tu") {
            $error = false;
        }

        if($error) {
            return redirect('dashboard')->with("error", "Maaf anda tidak memiliki akses!.");
        }
        return view("pages.laporanabsensiswa", [
            "judul" => "Laporan Absen Siswa"
        ]);
    }
    public function cetaklaporanabsensiswa(Request $request)
    {
        $request->validate([
            'kelas' => 'required',
            'jurusan' => 'required',
            'tanggal' => 'required|array',
        ],[
            "required" => "Field wajib di isi.",
            "array" => "Tanggal tidak falid",
        ]);

        $idinstansi = session("idinstansi");
        $idsemester = session("semester");

        
        $tanggal = CarbonPeriod::create($request->tanggal["start"], $request->tanggal["end"]);
        $hariBlacklist = hariliburM::with("hari")->where("idinstansi", $idinstansi)->get()->pluck("hari.namahari")->toArray();

        Carbon::setLocale("id");
        $tanggal = $tanggal->addFilter(function ($date) use ($hariBlacklist) {
            $namaHari = Carbon::parse($date)->isoFormat('dddd'); 
            return !in_array($namaHari, $hariBlacklist);
        });
        $tanggal = $tanggal->map(fn($date) => $date->format('Y-m-d'));
        $jurusan = $request->jurusan;
        $kelas = $request->kelas;
        $start = Carbon::parse($request->tanggal["start"]);
        $end   = Carbon::parse($request->tanggal["end"]);

        if ($start->isSameMonth($end) && $start->isSameYear($end)) {
            $hasil = $start->translatedFormat('d') . ' - ' . $end->translatedFormat('d F Y');
        } else {
            $hasil = $start->translatedFormat('d F') . ' - ' . $end->translatedFormat('d F Y');
        }

        
        $pengaturan = pengaturanM::where("idinstansi", $idinstansi)->first()??[];
        $jammasuk = $pengaturan->jammasuk??"08:00:00";
        $toleransi = $pengaturan->keterlambatan??15;
        $siswa = siswaM::where([
            "idinstansi" => $idinstansi,
            "idkelas" => $kelas,
            "idjurusan" => $jurusan,
        ])->orderBy("namasiswa", "asc")->get();
        $rombel = $siswa->first()->kelas->namakelas." ".$siswa->first()->jurusan->inisialjurusan; 
        $judul = "Absen ".$rombel." | ".$hasil;
        
        $pdf = Pdf::loadView("pages.laporan.cetakabsensiswa", [
            "judul" => $judul,
            "siswa" => $siswa,
            "idinstansi" => $idinstansi,
            "jammasuk" => $jammasuk,
            "toleransi" => $toleransi,
            "tanggaltampil" => $request->tanggal,
            "tanggal" => iterator_to_array($tanggal),
            "kelas" => kelasM::where("idkelas", $kelas)->first(),
            "jurusan" => jurusanM::where("idjurusan", $jurusan)->first(),
        ])->setOption([
            'isRemoteEnabled' => true,
        ]);
        
        return $pdf->stream($judul.".pdf");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function daftar($kode)
    {
        return view("pages.daftar", [
            "kode" => $kode,
        ]);
    }

    public function datadiri()
    {
        return view("pages.datadiri", [
            "judul" => "Data Diri",
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(siswaM $siswaM)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(siswaM $siswaM)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, siswaM $siswaM)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(siswaM $siswaM)
    {
        //
    }
}
