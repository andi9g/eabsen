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
                                            color:{!! $detaildesainkartu['warnatextdepan']??'#ffffff' !!};
                                            @if($detaildesainkartu['desainkartu'] == 'gambar')
                                                background-image: url({{ Storage::disk('s3')->temporaryUrl($detaildesainkartu['gambardepan']??'profil.png', now()->addMinutes(10)) }});
                                                background-size: cover;
                                                background-position: center;
                                                background-repeat: no-repeat;
                                            @else
                                                background-color: {!! $detaildesainkartu['warnadepan']??'#1a365d' !!} !important;
                                            @endif
                                        ">
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
                                                        border: {{ $detaildesainkartu['tebalborder'] }}px solid #ffffff;
                                                        object-fit: cover;
                                                        object-position: top center;
                                                    ">
                                                </center>
                                            </div>
                                            <div class="card-body" style="color: {!! $detaildesainkartu['warnatextdepan']??'#ffffff' !!}">
                                                <div class="user-name">{{ ucwords(strtolower($data['namasiswa']??'')) }}</div>
                                                <div class="user-nisn">{{ $data['nisn'] }}</div>
                                                <div class="classuntukrapikan" style="margin-top: 15px; text-align: center;">
                                                    <table style="width: 85%; margin: 0 auto; border-collapse: collapse; font-size: 9px; color: {!! $detaildesainkartu['warnatextdepan']??'#ffffff' !!}; background-color: rgba(255, 255, 255, 0.05); border-radius: 4px; overflow: hidden;">
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
                                                            style="background-color: rgba(255, 255, 255, 0.1);"
                                                        @endif>
                                                            <th style="padding: 5px 8px; text-align: left; font-weight: bold; width:5px;">{{ ucfirst($item) }}</th>
                                                            <td style="padding: 5px 8px; text-align: left;">{{ $pill }}</td>
                                                        </tr>
                                                            
                                                        @endforeach
        
                                                    </table>
                                                </div>
                                                
                                            </div>
                                            <div class="card-footer">
                                                www.domainanda.com
                                            </div>
                                        </div>
                                    </td>
        
                                    <td>
                                        <div class="card card-back" style="@if($detaildesainkartu['desainkartu'] == 'gambar')
                                                background-image: url({{ Storage::disk('s3')->temporaryUrl($detaildesainkartu['gambarbelakang']??'profil.png', now()->addMinutes(10)) }});
                                                background-size: cover;
                                                background-position: center;
                                                background-repeat: no-repeat;
                                            @else
                                                background-color: {!! $detaildesainkartu['warnabelakang']??'#2b6cb0' !!} !important;
                                            @endif
                                        ">
                                            <div class="card-back-content">
                                                <div class="terms-title">{!! $deskripsi["judul"] !!}</div>
                                                {{-- <ol class="terms-list"> --}}
                                                    {!! $deskripsi["deskripsi"] !!}
                                                {{-- </ol> --}}
                                            </div>
                                            <div class="card-footer">
                                                www.domainanda.com
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