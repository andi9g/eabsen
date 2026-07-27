<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Kartu RFID</title>
    <link rel="stylesheet" href="{{ public_path("desainkartu/desain.css") }}">
</head>
<body>

    @foreach($siswa as $item)
    @php
        $kelamin = ($item->jk == "L")?"Laki-Laki":"Perempuan";
        $data = [
            "instansi" => $item->instansi->namainstansi??'',
            "alamatinstansi" => $item->instansi->alamat??'',
            "logo" => $item->instansi->logo??'',
            "foto" => $item->foto??'',
            "namasiswa" => $item->namasiswa??'',
            "nisn" => $item->nisn??'',
            "alamat" => $item->alamat??'',
            "agama" => $item->agama??'',
            "jurusan" => $item->jurusan->inisialjurusan??'',
            "jk" => $kelamin??'',
        ];

        
        // dd($data);
    @endphp
    <div class="user-wrapper mycard">
                
                        <div class="cutting-box">
                            
                            <table class="card-table">
                                <tr>
                                    <td>
                                        <div class="card card-front" style="
                                            position: relative; /* Wajib agar gambar absolut tidak keluar dari card */
                                            overflow: hidden;   /* Memastikan gambar tidak tumpah jika ukurannya lebih besar */
                                            color: {!! $detaildesainkartu['warnatextdepan'] ?? '#ffffff' !!};
                                            @if($detaildesainkartu['desainkartu'] != 'gambar')
                                                background-color: {!! $detaildesainkartu['warnadepan'] ?? '#1a365d' !!} !important;
                                            @endif
                                        ">
                                            {{-- JIKA DESAIN ADALAH GAMBAR, RENDER SEBAGAI IMG ABSOLUT --}}
                                            @if($detaildesainkartu['desainkartu'] == 'gambar')
                                                <img src="{{ Storage::disk('s3')->temporaryUrl($detaildesainkartu['gambardepan'] ?? 'profil.png', now()->addMinutes(10)) }}" 
                                                    style="
                                                        position: absolute; 
                                                        top: 0; 
                                                        left: 0; 
                                                        width: 100%; 
                                                        height: 100%; 
                                                        object-fit: cover; /* Efeknya sama seperti background-size: cover */
                                                        z-index: -1;       /* Memastikan gambar berada di bawah teks */
                                                    ">
                                            @endif

                                            {{-- <div class="card-header">
                                                {{ $data["instansi"] }}
                                            </div> --}}
                                            <div class="profile-wrapper">
                                                <center>
                                                    <img 
                                                    @if (empty($data['foto']))
                                                        src="{{ Storage::disk('s3')->temporaryUrl('profil.png', now()->addMinutes(10)) }}"
                                                        @else
                                                        src="{{ Storage::disk('s3')->temporaryUrl($data['foto'], now()->addMinutes(10)) }}"
                                                    @endif
                                                    
                                                    style="
                                                        width: 100px;
                                                        height: 100px;
                                                        border-radius: {{ $detaildesainkartu['radiusborder'] }}px;
                                                        -webkit-border-radius: {{ $detaildesainkartu['radiusborder'] }}px;
                                                        border: {{ $detaildesainkartu['tebalborder'] }}px solid {!! $detaildesainkartu['warnaborder'] !!};
                                                        object-fit: cover;
                                                        object-position: top center;
                                                    ">
                                                </center>
                                            </div>
                                            <div class="card-body" style="color: {!! $detaildesainkartu['warnatextdepan']??'#ffffff' !!}">
                                                <div class="user-name">{{ ucwords(strtolower($data['namasiswa']??'')) }}</div>
                                                <div class="user-nisn" style="color: {!! $detaildesainkartu['warnatextdepan']??'#ffffff' !!}">{{ $data['nisn'] }}</div>
                                                <div class="classuntukrapikan" style="margin-top: 15px; text-align: center;">
                                                    <table style="width: 85%; margin: 0 auto; border-collapse: collapse; font-size: 9px; color: {!! $detaildesainkartu['warnatextdepan']??'#ffffff' !!}; {{ $detaildesainkartu['genap'] }}; border-radius: 4px; overflow: hidden;">
                                                        @foreach ($pillbox as $item)
                                                        @if ($item == "jurusan")
                                                            @php
                                                                $pill = $data["jurusan"];
                                                            @endphp
                                                        @elseif($item == "instansi")
                                                            @php
                                                                $pill = $data["instansi"];
                                                            @endphp
                                                        @elseif($item == "alamat")
                                                            @php
                                                                $pill = $data["alamat"];
                                                            @endphp
                                                        @elseif($item == "kelamin")
                                                            @php
                                                                $pill = $data["jk"];
                                                            @endphp
                                                        @elseif($item == "agama")
                                                            @php
                                                                $pill = $data["agama"];
                                                            @endphp
                                                        @endif
                                                        <tr valign="top" @if ($loop->iteration %2 == 1)
                                                            style="background-color: {{ $detaildesainkartu['ganjil'] }};"
                                                        @endif>
                                                            <th style="padding: 5px 8px; text-align: left; font-weight: bold; width:5px;">{{ ucfirst($item) }}</th>
                                                            <td style="padding: 5px 8px; text-align: left;">{{ $pill }}</td>
                                                        </tr>
                                                            
                                                        @endforeach
        
                                                    </table>
                                                </div>
                                                
                                            </div>
                                            <div class="card-footer">
                                                {{-- www.domainanda.com --}}
                                            </div>
                                        </div>
                                    </td>
        
                                    <td>
                                        <div class="card card-back" style="
                                            position: relative; /* Wajib agar gambar tetap di dalam area kartu */
                                            overflow: hidden;   /* Memotong gambar jika melebihi ukuran kartu */
                                            @if($detaildesainkartu['desainkartu'] != 'gambar')
                                                background-color: {!! $detaildesainkartu['warnabelakang'] ?? '#2b6cb0' !!} !important;
                                            @endif
                                        ">
                                            {{-- JIKA DESAIN ADALAH GAMBAR, RENDER SEBAGAI IMG ABSOLUT --}}
                                            @if($detaildesainkartu['desainkartu'] == 'gambar')
                                                <img src="{{ Storage::disk('s3')->temporaryUrl($detaildesainkartu['gambarbelakang'] ?? 'profil.png', now()->addMinutes(10)) }}" 
                                                    style="
                                                        position: absolute; 
                                                        top: 0; 
                                                        left: 0; 
                                                        width: 100%; 
                                                        height: 100%; 
                                                        object-fit: cover; /* Pengganti background-size: cover */
                                                        z-index: -1;       /* Menaruh gambar di posisi paling belakang */
                                                    ">
                                            @endif
                                           <div class="card-back-content" style="color: {!! $detaildesainkartu['warnatextbelakang'] !!}">
                                                <div class="terms-title">{!! $deskripsi["judul"] !!}</div>
                                                {{-- <ol class="terms-list"> --}}
                                                    {!! $deskripsi["deskripsi"] !!}
                                                {{-- </ol> --}}
                                            </div>
                                            <div class="card-footer">
                                                {{-- www.domainanda.com --}}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
        
                        </div>
        
                    </div>
    @endforeach

</body>
</html>