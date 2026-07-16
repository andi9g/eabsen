<div>
    <flux:modal name="linkdaftar" class="md:w-lg space-y-3">
            <div>
                <flux:heading size="lg">Link Registrasi</flux:heading>
                <flux:text class="mt-2">Bagikan Link yang dibuat kepada pegawai untuk registrasi akun.</flux:text>
            </div>
            <div class="">
                @if ($link)
                    <flux:textarea>
                        {{ url("/link/daftar/$link->kode") }}
                    </flux:textarea>
                @endif
            </div>

            <div class="flex gap-3">
                
                @if (!$link)
                    <flux:button variant="primary" wire:click="buatlink">Buat Link</flux:button>
                @else
                    <flux:button variant="primary" color="red" wire:click="hapuslink">Hapus Link</flux:button>
                    <flux:button variant="primary" color="yellow" wire:click="generatelink">Generate Ulang</flux:button>
                    
                @endif
            </div>
    </flux:modal>
     <flux:modal name="tomboltambahpegawai" class="md:w-lg">
        <form wire:submit="tambahpegawai" class="space-y-4">
            <div>
                <flux:heading size="lg">Tambah pegawai</flux:heading>
                <flux:text class="mt-2">Silahkan lengkapi form dibawah ini.</flux:text>
            </div>
            
            <flux:input wire:model="name" label="Nama pegawai" placeholder="Masukkan nama lengkap" required />
            <flux:input wire:model="email" label="Email" placeholder="Masukkan email" required />

            

            <div class="flex">
                <flux:spacer />
    
                <flux:button type="submit" variant="primary">Tambah</flux:button>
            </div>
        </form>
    </flux:modal>

    <div class="w-full md:w-[80%] space-y-5 mx-auto">
        <div class="flex flex-col md:flex-row md:items-center gap-3">
            @if (!empty(Session::get("idinstansi")))
                <flux:button wire:click="tomboltambahpegawai" variant="primary" color="sky" icon="plus-circle">Tambah Pegawai</flux:button>
                <flux:button type="submit" variant="primary" color="yellow" icon="link" wire:click="daftar">Link Daftar</flux:button>
            @endif

            <div class="mt-3 md:mt-0 md:ml-auto w-full md:w-auto">
                <div class="grid grid-cols-1 gap-1">
                    <flux:input icon="magnifying-glass" class="w-fit ml-auto" placeholder="Search..." wire:model.live.debounce.500ms="search"/>
                </div>
            </div>
        
        </div>
    


        <flux:table :paginate="$pegawai" class="text-xl"/>

        <div class="flex">
            <div class="ml-auto">
                <flux:button type="submit" variant="primary" color="green" wire:click="updateposisi">Update Posisi</flux:button>

            </div>
        </div>
        @if (count($pegawai)==0)
            <flux:kanban.card class="text-center">
                Tidak ada data yang ditemukan
            </flux:kanban.card>
        @endif
        @foreach ($pegawai as $item)
        <flux:callout icon="user" wire:key="item-{{ $item->id }}">
            
            
            <div class="flex flex-col md:flex-row md:items-center">
                <div>
                    <flux:callout.heading class="text-[12pt]">
                        {{ $item->name }}
                    </flux:callout.heading>
                    <flux:callout.text>
                        {{ $item->email }}
                    </flux:callout.text>
                    <flux:callout.text>
                        {{ $item->detailuser->instansi->namainstansi??'' }}
                    </flux:callout.text>
                </div>
        
                <div class="mt-3 md:mt-0 md:ml-auto w-full md:w-auto space-y-3">
                    <flux:select wire:model.live="data.{{ $item->iduser }}">
                        @foreach ($dataPosisi as $dp)
                            <flux:select.option :value="$dp" class="capitalize">{{ $dp }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <div class="grid grid-cols-2 gap-2">
                        <flux:badge as="button" variant="pill" color="red" icon="trash" wire:click="hapuspegawai({{ $item->iduser }})">
                            Hapus
                        </flux:badge>
        
                        <flux:badge as="button" variant="pill" color="yellow" icon="key" wire:click="resetkey({{ $item->iduser }})">
                            Reset Key
                        </flux:badge>
                    </div>
                </div>
            </div>
        </flux:callout>
      
        
        @endforeach
        <flux:table :paginate="$pegawai" class="text-xl"/>
    </div>



    
    
   
</div>
