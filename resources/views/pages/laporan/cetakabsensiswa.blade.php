<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $judul }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 9px;
        }
        h1 {
            text-align: left;
            margin: 0;
            padding: 0;
        }
        h2 {
            text-align: left;
            margin: 0;
            padding: 0;
        }
        h3 {
            text-align: center;
            margin: 15px 0px;
            padding: 0;
        }
        p {
            text-align: left;
            margin: 0;
            padding: 0;
        }
        .tabelku {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .tabelku th, .tabelku td {
            border: 1px solid #000;
            padding: 8px;
            font-size: 9pt;
        }
        .tabelku th {
            text-align: center;
            background-color: #f2f2f2;
        }
        .kop {
            border-top: 6px double #000;
            border-bottom: 2px solid #000;
        }
        .paddingku {
            padding: 10px;
        }
    </style>
</head>
<body>
    <table width="100%">
        <tr >
            <td width="75px">
                <img src="{{ Storage::disk('s3')->temporaryUrl(auth()->user()->detailuser->instansi->logo, now()->addMinutes(10)) }}" width="75px" alt="Gambar Produk">
            </td>
            <td align="left" class="paddingku">
                <h1>{{ auth()->user()->detailuser->instansi->namainstansi }}</h1>
                <h2>{{ auth()->user()->detailuser->instansi->npsn }}</h2>
                <p>{{ auth()->user()->detailuser->instansi->alamat }}</p>
            </td>
        </tr>
    </table>
    
    <div class="kop"></div>

    <center>
        <h3>LAPORAN ABSENSI SISWA</h3>
    </center>

    <table>
        <tr>
            <td>Rombel</td>
            <td>:</td>
            <td>{{ $siswa->first()->kelas->namakelas." ".$siswa->first()->jurusan->inisialjurusan }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>:</td>
            @php
                Carbon\Carbon::setLocale("id");
                if($tanggaltampil["start"] == $tanggaltampil["end"]) {
                    $tgl = Carbon\Carbon::parse($tanggaltampil["start"])->isoFormat("DD/MMM/YYYY");
                }else {
                    $tgl = Carbon\Carbon::parse($tanggaltampil["start"])->isoFormat("DD/MMM/YYYY")." s.d ".Carbon\Carbon::parse($tanggaltampil["end"])->isoFormat("DD/MMM/YYYY");
                }
            @endphp
            <td>{{ $tgl }}</td>
        </tr>
    </table>

    <table border="1" class="tabelku">
        <thead>
            <tr>
                <th width="5px" rowspan="2">No</th>
                <th rowspan="2">Nama Siswa</th>
                <th colspan="4">STATUS</th>
                <th rowspan="2">Terlambat</th>
                <th rowspan="2">Total Kehadiran</th>
            </tr>
            <tr>
                <th>H</th>
                <th>I</th>
                <th>S</th>
                <th>A</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($siswa as $item)
                <tr>
                    <td align="center">{{ $loop->iteration }}</td>
                    <td>{{ $item->namasiswa }}</td>
                    @php
                        $keterlambatan = $item->absensiswa()->whereIn("tanggal", $tanggal)->where("status", "H")->get();
                        $t = 0;
                        foreach ($keterlambatan as $key => $k) {
                            $jamseharusnya = strtotime("+$toleransi minutes", strtotime($k->tanggal." ".$jammasuk));
                            $jamabsen = strtotime($k->waktumasuk);
                            if($jamabsen > $jamseharusnya) {
                                $t = $t + 1;
                            } 
                        }
                        $h = $item->absensiswa()->whereIn("tanggal", $tanggal)->where("status", "H")->count();
                        $i = $item->absensiswa()->whereIn("tanggal", $tanggal)->where("status", "I")->count();
                        $s = $item->absensiswa()->whereIn("tanggal", $tanggal)->where("status", "S")->count();
                        $a = $item->absensiswa()->whereIn("tanggal", $tanggal)->where("status", "A")->count();
                        
                        $rumus = count($tanggal) - ($h + $i + $s + $a);
                        $a = $a + $rumus;
                    @endphp
                    <td align="center">{{ $h }}</td>
                    <td align="center">{{ $i }}</td>
                    <td align="center">{{ $s }}</td>
                    <td align="center">{{ $a }}</td>
                    <td align="center">{{ $t }}x</td>
                    <td align="center">{{ $h + $i + $s + $a }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
</body>
</html>