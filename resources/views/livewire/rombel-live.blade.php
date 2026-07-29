<div>
     <flux:modal name="buttontambahkelas" class="md:w-lg">
        <form wire:submit="formkelas" class="space-y-6">
            <div>
                <flux:heading size="lg">Form Tambah Kelas</flux:heading>
                <flux:text class="mt-2">Silahkan lengkapi form dibawah ini.</flux:text>
            </div>
                <flux:input label="Nama Kelas" placeholder="masukan nama jurusan" wire:model="data.namakelas"/>    

            <div class="flex">
                <flux:spacer />
    
                <flux:button type="submit" variant="primary">{{ $updatekelas?"Update":"Tambah" }}</flux:button>
            </div>
        </form>
    </flux:modal>
     <flux:modal name="buttontambahjurusan" class="md:w-lg">
        <form wire:submit="formjurusan" class="space-y-6">
            <div>
                <flux:heading size="lg">Form Tambah Jurusan</flux:heading>
                <flux:text class="mt-2">Silahkan lengkapi form dibawah ini.</flux:text>
            </div>
                <flux:input label="Nama Jurusan" placeholder="masukan program keahlian" wire:model="data.namajurusan"/>    
                <flux:input label="Inisial Jurusan" placeholder="masukan inisial jurusan" wire:model="data.inisialjurusan"/>    

            <div class="flex">
                <flux:spacer />
    
                <flux:button type="submit" variant="primary">{{ $updatejurusan?"Update":"Tambah" }}</flux:button>
            </div>
        </form>
    </flux:modal>

    <div class="w-full md:w-[80%] mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <flux:card>
                <div class="flex flex-col md:flex-row md:items-center">
                    <div class="">
                        <flux:button variant="primary" color="blue" wire:click="buttontambahkelas">Tambah kelas</flux:button>
                        <flux:button href="{{ route('import', []) }}" variant="primary" color="gray">IMPORT</flux:button>
                    </div>
                    
                   
                    <div class="mt-3 md:mt-0 md:ml-auto w-full md:w-auto">
                        <div class="grid grid-cols-1 gap-1">
                            {{-- bagian kiri dan bawah menggunakan colom --}}
                            <flux:input icon="magnifying-glass" placeholder="Search orders" class="ml-auto" wire:model.live.debounce.500ms="searchkelas"/>
                        </div>
                    </div>
                </div>
    
                <flux:table :paginate="$kelas">
                    <flux:table.columns>
                        <flux:table.column width="5px">No</flux:table.column>
                        <flux:table.column>Nama Kelas</flux:table.column>
                        <flux:table.column>Action</flux:table.column>
                    </flux:table.columns>
                
                    @foreach ($kelas as $item)
                        <flux:table.rows>
                            <flux:table.cell>{{ $loop->iteration + $kelas->firstItem() - 1  }}</flux:table.cell>
                            <flux:table.cell>{{ $item->namakelas }}</flux:table.cell>
                            <flux:table.cell>
                                <flux:badge as="button" variant="pill" icon="pencil-square" color="blue" wire:click="buttonupdatekelas({{ $item->idkelas }})">edit</flux:badge>
                                <flux:badge as="button" variant="pill" icon="trash" color="red" wire:click="hapuskelas({{ $item->idkelas }})">Hapus</flux:badge>
                            </flux:table.cell>
                        </flux:table.rows>
                    @endforeach
                </flux:table>
    
            </flux:card>




            <flux:card>
                <div class="flex flex-col md:flex-row md:items-center">
                    <div class="">
                        <flux:button variant="primary" color="blue" wire:click="buttontambahjurusan">Tambah Jurusan</flux:button>
                        <flux:button href="{{ route('import', []) }}" variant="primary" color="gray">IMPORT</flux:button>
                    </div>
                    
                   
                    <div class="mt-3 md:mt-0 md:ml-auto w-full md:w-auto">
                        <div class="grid grid-cols-1 gap-1">
                            {{-- bagian kiri dan bawah menggunakan colom --}}
                            <flux:input icon="magnifying-glass" placeholder="Search orders" class="ml-auto" wire:model.live.debounce.500ms="searchjurusan"/>
                        </div>
                    </div>
                </div>
    
                <flux:table :paginate="$jurusan">
                    <flux:table.columns>
                        <flux:table.column width="5px">No</flux:table.column>
                        <flux:table.column>Nama Jurusan</flux:table.column>
                        <flux:table.column>Inisial</flux:table.column>
                        <flux:table.column>Action</flux:table.column>
                    </flux:table.columns>
                
                    @foreach ($jurusan as $item)
                        <flux:table.rows>
                            <flux:table.cell>{{ $loop->iteration + $jurusan->firstItem() - 1  }}</flux:table.cell>
                            <flux:table.cell>{{ $item->namajurusan }}</flux:table.cell>
                            <flux:table.cell>{{ $item->inisialjurusan }}</flux:table.cell>
                            <flux:table.cell>
                                <flux:badge as="button" variant="pill" icon="pencil-square" color="blue" wire:click="buttonupdatejurusan({{ $item->idjurusan }})">edit</flux:badge>
                                <flux:badge as="button" variant="pill" icon="trash" color="red" wire:click="hapusjurusan({{ $item->idjurusan }})">edit</flux:badge>
                            </flux:table.cell>
                        </flux:table.rows>
                    @endforeach
                </flux:table>
            </flux:card>
        </div>
    </div>
</div>
