<div>
    <div class="grid grid-cols-1 md:grid-cols-[1fr_2fr] gap-4 mb-5">
        <flux:callout color="blue" class="space-y-4 overflow-auto">
            <flux:card class="space-y-4" x-show="!$wire.tampil">
                @if (auth()->user()->akses->akses == 'superadmin')
                    <flux:select label="Pilih Sekolah" wire:model="datainstansi" wire:change="updateinstansi">
                        <flux:select.option value="">Pilih Sekolah</flux:select.option>
                        @foreach ($instansi as $item)
                            <flux:select.option :value="$item->idinstansi">{{ $item->namainstansi }}</flux:select.option>
                        @endforeach
                    </flux:select>
                @endif
                @if (!$tampil && !empty($idinstansi))
                    <flux:text size="xs">Silahkan buat desain kartu terlebih dahulu</flux:text>
                    <flux:button type="submit" variant="primary" color="blue" class="w-full" wire:click="buatdesain">Buat Desain Kartu</flux:button>
                @endif
            </flux:card>

            {{-- @if ($tampil) --}}
            <form action="" wire:submit="simpanDesain" class="space-y-4">
                <flux:separator text="HALAMAN DEPAN"/>
                <flux:card>
                    <div x-show="$wire.tampil" class="space-y-5" >
                         
                            <flux:radio.group label="Desain Kartu" wire:model.live.throttle.500ms="detaildesainkartu.desainkartu">
                                <flux:radio value="solid" color="red" label="Warna Solid" />
                                <flux:radio value="gambar" label="Gambar" />
                            </flux:radio.group>
                             
                            <div x-show="$wire.detaildesainkartu.desainkartu == 'gambar'">
                                <flux:file-upload wire:model="photo" label="Gambar Background">
                                    <flux:file-upload.dropzone
                                        heading="Drop files or click to browse"
                                        text="JPG, PNG, GIF up to 3MB"
                                        with-progress
                                        inline
                                    />
                                </flux:file-upload>
                            </div>
                            <div x-show="$wire.detaildesainkartu.desainkartu == 'solid'">
                                <flux:color-picker label="Warna Depan" wire:model.live.throttle.500ms="detaildesainkartu.warnadepan" type="button" />
                            </div>
                             <flux:color-picker label="Warna Text Depan" wire:model.live.throttle.500ms="detaildesainkartu.warnatextdepan" type="button" />
                             <flux:input wire:model.live.throttle.500ms="detaildesainkartu.tebalborder" label="Tebal Border Profil" type="number" kbd="Pixel" />
                             <flux:color-picker label="Warna Border" wire:model.live.throttle.500ms="detaildesainkartu.warnaborder" type="button" />
                             <flux:slider min="0" max="50" label="Bingkai Profil" wire:model.live.throttle.500ms="detaildesainkartu.radiusborder"/>
                            
                             
                             <flux:pillbox variant="combobox" multiple max="4" placeholder="Choose skills..." wire:model.live="pillbox" label="Informasi Umum">
                                <flux:pillbox.option value="jurusan" wire:key="jurusan">jurusan</flux:pillbox.option>
                                <flux:pillbox.option value="instansi" wire:key="instansi">instansi</flux:pillbox.option>
                                <flux:pillbox.option value="alamat" wire:key="alamat">alamat</flux:pillbox.option>
                                <flux:pillbox.option value="kelamin" wire:key="kelamin">kelamin</flux:pillbox.option>   
                                <flux:pillbox.option value="agama" wire:key="agama">agama</flux:pillbox.option>
                                <flux:pillbox.option value="TTL" wire:key="TTL">TTL</flux:pillbox.option>
                            </flux:pillbox>

                             <flux:color-picker label="Warna Tabel 1" wire:model.live.throttle.500ms="detaildesainkartu.genap" type="button" format="rgba" />
                             <flux:color-picker label="Warna Tabel 2" wire:model.live.throttle.500ms="detaildesainkartu.ganjil" type="button" format="rgba" />
         
                    </div>
                </flux:card>
    
                
                <flux:separator text="HALAMAN BELAKANG"/>
                <flux:card>
                    <div x-show="$wire.tampil">
                         <flux:card color="blue" class="space-y-5">
                             
                            <div x-show="$wire.detaildesainkartu.desainkartu == 'gambar'">
                                <flux:file-upload wire:model="photo2" label="Gambar Background">
                                    <flux:file-upload.dropzone
                                        heading="Drop files or click to browse"
                                        text="JPG, PNG, GIF up to 3MB"
                                        with-progress
                                        inline
                                    />
                                </flux:file-upload>
                            </div>
                            <div x-show="$wire.detaildesainkartu.desainkartu == 'solid'">
                                <flux:color-picker label="Warna Belakang" wire:model.live.throttle.500ms="detaildesainkartu.warnabelakang" type="button" />
                            </div>
                             
                             <flux:color-picker label="Warna Text Belakang" wire:model.live.throttle.500ms="detaildesainkartu.warnatextbelakang" type="button" />
    
                             <flux:input label="Judul" wire:model.live.throttle.500ms="deskripsi.judul" />
                             <div class="min-w-0 w-full">
                                 <flux:editor toolbar="bullet ordered | bold italic | align" wire:model.live.throttle.500ms="deskripsi.deskripsi" class="w-full" label="Isi Belakang" value=""/>
                             </div>
                         </flux:card>
         
                    </div>
                </flux:card>

                <div class="flex">
                    <flux:button type="submit" variant="primary" color="green" class="ml-auto w-full">Update Desain</flux:button>
                    
                </div>

            </form>
            {{-- @endif --}}

        </flux:callout>

        <div x-show="$wire.tampil" >
            <flux:card class="overflow-auto">
                <center>
                    <div class="user-wrapper mycard">
                
                        <div class="cutting-box">
                            
                            <table class="card-table">
                                <tr>
                                    <td>
                                        <div class="card card-front" style="
                                            color:{!! $detaildesainkartu['warnatextdepan']??'#ffffff' !!};
                                            @if($detaildesainkartu['desainkartu'] == 'gambar')
                                                background-image: url(
                                                @if(empty($photo?->temporaryUrl()))
                                                    {{ Storage::disk('s3')->temporaryUrl($detaildesainkartu['gambardepan']??'profil.png', now()->addMinutes(10)) }}
                                                @else
                                                {{ $photo?->temporaryUrl() }}
                                                @endif
                                                 );
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
                                                    <img src="
                                                    {{ Storage::disk('s3')->temporaryUrl('profil.png', now()->addMinutes(10)) }}
                                                     " class="profile-img" alt="Foto"
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
                                                <div class="user-name">Nama Lengkap</div>
                                                <div class="user-nisn" style="color: {!! $detaildesainkartu['warnatextdepan']??'#ffffff' !!}">1234567890</div>
                                                <div class="classuntukrapikan" style="margin-top: 15px; text-align: center;">
                                                    <table style="width: 85%; margin: 0 auto; border-collapse: collapse; font-size: 9px; color: {!! $detaildesainkartu['warnatextdepan']??'#ffffff' !!}; background-color: {{ $detaildesainkartu['genap'] }}; border-radius: 4px; overflow: hidden;">
                                                        @foreach ($pillbox as $item)
                                                        <tr valign="top" @if ($loop->iteration %2 == 1)
                                                            style="background-color: {{ $detaildesainkartu['ganjil'] }};"
                                                        @endif>
                                                            <th style="padding: 5px 8px; text-align: left; font-weight: bold; width:5px;">{{ ucfirst($item) }}</th>
                                                            <td style="padding: 5px 8px; text-align: left;">none</td>
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
                                        <div class="card card-back" style="@if($detaildesainkartu['desainkartu'] == 'gambar')
                                                background-image: url(
                                                @if(empty($photo2?->temporaryUrl()))
                                                    {{ Storage::disk('s3')->temporaryUrl($detaildesainkartu['gambarbelakang']??'profil.png', now()->addMinutes(10)) }}
                                                @else
                                                {{ $photo2?->temporaryUrl() }}
                                                @endif
                                                 );
                                                background-size: cover;
                                                background-position: center;
                                                background-repeat: no-repeat;
                                            @else
                                                background-color: {!! $detaildesainkartu['warnabelakang']??'#2b6cb0' !!} !important;
                                            @endif
                                        ">
                                            <div class="card-back-content" style="color: {!! $detaildesainkartu['warnatextbelakang'] !!}">
                                                <div class="terms-title">{!! $deskripsi["judul"] !!}</div>
                                                {{-- <ol class="terms-list"> --}}
                                                    <div class="" style="margin-top: -10px">
                                                        {!! $deskripsi["deskripsi"] !!}
                                                    </div>
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
    
                </center>
            </flux:card>
        </div>
    </div>
</div>

@push("cssku")
   
    <style>
        /* Pembungkus per User (Page Break otomatis jika lebih dari satu user) */
        .user-wrapper {
            display: inline-block; /* Membuatnya bisa berjajar ke samping */
            vertical-align: top;   /* Memastikan baris kartu sejajar rata atas */
            margin: 10px;          /* Jarak antar box user (atas, bawah, kanan, kiri) */
            page-break-inside: avoid; /* Mencegah kartu terpotong di tengah halaman */
        }
        
        /* Hilangkan page break untuk user terakhir agar tidak ada halaman kosong di akhir */
        .user-wrapper:last-child {
            page-break-after: avoid;
        }

        /* Box Luar dengan garis putus-putus untuk area potong */
        .cutting-box {
            border: 2px dashed #999999;
            padding: 10px;      
            border-radius: 8px;
        }

        /* Pastikan ukuran tabel di dalamnya pas */
        .card-table {
            border-collapse: separate;
            border-spacing: 10px 0; /* Jarak antara kartu depan & belakang sedikit diperkecil */
        }

        /* Standar Ukuran Kartu RFID Vertikal: 5.4cm x 8.6cm */
        .card {
            width: 5.4cm;
            height: 8.6cm;
            position: relative;
            border-radius: 8px; /* Sudut rounded sedikit */
            overflow: hidden;
            -webkit-border-radius: 8px; /* Fallback dompdf older version */
            border: 1px solid #d6d6d6; /* Tipis saja untuk batas potong pas kartu */
        }

        /* Desain Background Kartu Depan */
        .card-front {
            /* Ganti dengan URL gambar background Anda atau warna gradient */
            
            /* Contoh jika pakai gambar background: 
               background-image: url('{{ public_path("images/bg-front.png") }}'); 
               background-size: cover; 
            */
            color: #ffffff;
        }

        /* Desain Background Kartu Belakang */
        .card-back {
            /* Contoh jika pakai gambar background: 
               background-image: url('{{ public_path("images/bg-back.png") }}'); 
               background-size: cover; 
            */
            color: #ffffff;
        }

        /* --- ISI KONTEN KARTU DEPAN --- */
        .card-header {
            text-align: center;
            padding-top: 15px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .profile-wrapper {
            text-align: center;
            margin-top: 23px;
            margin-bottom: 10px;
        }

        /* Foto profil bulat */
        .profile-img {
            object-fit: cover;
            object-position: top center;
        }

        .card-body {
            text-align: center;
            margin-top: 10px;
            padding: 0 10px;
        }

        .user-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 2px;
        }

        .user-nisn {
            font-size: 8.5pt;
            color: #e2e8f0;
        }

        /* --- ISI KONTEN KARTU BELAKANG --- */
        .card-back-content {
            padding: 20px 15px;
            font-size: 9px;
            line-height: 1.4;
        }

        .terms-title {
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 10px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.3);
            padding-bottom: 5px;
        }

        .terms-list {
            margin: 0;
            padding-left: 15px;
        }

        .card-footer {
            position: absolute;
            bottom: 15px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #cbd5e0;
        }

        .tabel-tengah {
            display: flex;
        }

        ul {
            list-style-type: disc;
        }
        ol {
            list-style-type: decimal;
        }
        li {
            margin-left: 20px;
        }
        .mycard {
            transition: transform 0.3s ease;
        }
        .mycard:hover {
            transform: scale(1.1); /* Membesarkan card sebesar 5% */
        }
    </style>
@endpush
