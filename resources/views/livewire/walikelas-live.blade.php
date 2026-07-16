<div>
    
    <flux:modal name="formwalikelas" class="md:w-lg">
        <form wire:submit="formwalikelas" class="space-y-6">
            <div>
                <flux:heading size="lg">Form Wali Kelas</flux:heading>
                <flux:text class="mt-2">Silahkan lengkapi form dibawah ini.</flux:text>
            </div>
            
            <flux:select label="Pilih Pegawai" variant="listbox" placeholder="Pilih Pegawai" searchable wire:model="data.iduser">
                @foreach ($pegawai as $item)
                    <flux:select.option :value="$item->iduser">{{ $item->name }}</flux:select.option>
                @endforeach
            </flux:select>
            <flux:select label="Pilih Kelas" wire:model="data.idkelas">
                <flux:select.option value="">Pilih Kelas</flux:select.option>
                @foreach ($kelas as $item)
                    <flux:select.option :value="$item->idkelas">{{ $item->namakelas }}</flux:select.option>
                @endforeach
            </flux:select>
            <flux:select label="Pilih Jurusan" wire:model="data.idjurusan">
                <flux:select.option value="">Pilih Kelas</flux:select.option>
                @foreach ($jurusan as $item)
                    <flux:select.option :value="$item->idjurusan">{{ $item->inisialjurusan }}</flux:select.option>
                @endforeach
            </flux:select>

            <div class="flex">
                <flux:spacer />
    
                <flux:button type="submit" variant="primary">{{ $update?"UPDATE":"TAMBAH" }}</flux:button>
            </div>
        </form>
    </flux:modal>
    <div class="w-full md:w-[80%] mx-auto space-y-4">

        <div class="flex flex-col md:flex-row md:items-center">
            {{-- bagian atas --}}
            <flux:button type="submit" variant="primary" color="blue"  wire:click="buttonformwalikelas">Tambah Walikelas</flux:button>
            <div class="mt-3 md:mt-0 md:ml-auto w-full md:w-auto">
                <div class="grid grid-cols-1 gap-1">
                    {{-- bagian kiri dan bawah menggunakan colom --}}
                    <flux:input icon="magnifying-glass" placeholder="Search orders" wire:model.live.debounce.500ms="search"/>      
                </div>
            </div>
        </div>

        @foreach ($walikelas as $item)
        <flux:callout>
            <div class="flex flex-col md:flex-row md:items-center">
                <div>
                    <flux:callout.heading class="text-[12pt]">
                        {{ $item->user->name }}
                    </flux:callout.heading>
                    <flux:badge variant="primary" color="yellow" size="lg">Walikelas : {{ $item->kelas->namakelas }} {{ $item->jurusan->inisialjurusan }}</flux:badge>
                </div>
        
                <div class="mt-3 md:mt-0 md:ml-auto w-full md:w-auto">
                    <div class="grid grid-cols-2 gap-2">
                        <flux:badge as="button" variant="pill" color="red" icon="trash" wire:click="buttonhapus({{ $item->idwalikelas }})">
                            Hapus
                        </flux:badge>
        
                        <flux:badge as="button" variant="pill" color="blue" icon="pencil" wire:click="buttonupdate({{ $item->idwalikelas }})">
                            Edit
                        </flux:badge>
                    </div>
                </div>
            </div>
        </flux:callout>
            
        @endforeach
    </div>
</div>
