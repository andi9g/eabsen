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
        <tr valign="">
            <td width="75px">
                <img src="{{ Storage::disk('s3')->temporaryUrl(auth()->user()->detailuser->instansi->logo, now()->addMinutes(10)) }}" width="75px" alt="Gambar Produk">
            </td>
            <td align="left" class="paddingku">
                <h2>{{ auth()->user()->detailuser->instansi->namainstansi }}</h2>
                <h2>LAPORAN DATA SISWA</h2>
                <p>{{ auth()->user()->detailuser->instansi->alamat }}</p>
                
            </td>
        </tr>
    </table>
    
    <div class="kop"></div>

    <center>
        <h3>LAPORAN DATA SISWA</h3>
    </center>

   
    
</body>
</html>