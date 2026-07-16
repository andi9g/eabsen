<div>
    
   
     <flux:modal name="buttontambahkartu" class="md:w-lg">
        <form wire:submit="tambahkartu" class="space-y-6">
            <div>
                <flux:heading size="lg">Registerasi Kartu Elektronik</flux:heading>
                <flux:text class="mt-2">Silahkan lengkapi form dibawah ini.</flux:text>
            </div>

            <div class="">
                
                <flux:input wire:model="value.nisn" readonly variant="filled" label="INFORMASI DASAR" class="[&_*]:rounded-none"/>
                <flux:input wire:model="value.namasiswa" readonly variant="filled" class="[&_*]:rounded-none"/>
                <flux:input wire:model="value.namaperangkat" readonly variant="filled" class="[&_*]:rounded-none bg-blue-100 dark:bg-zinc-800 font-bold"/>

            </div>

            <div class="">
                <flux:label>Kode UUID</flux:label>
                <flux:text size="md" color="green" class="mb-2">Silahkan scan kartu pada alat registerasi dan tekan tombol <b>GET UUID</b></flux:text>
                <flux:input.group >
                     <flux:input wire:model="value.uuid" placeholder="Scan Now" class="font-bold" />
                    <flux:button as="button" icon="magnifying-glass" variant="primary" color="green" wire:click="getUUID()" class="font-bold"><b>GET UUID</b></flux:button>
                </flux:input.group>

                <br>
                @if ($status == "belumterdaftar")
                    <flux:badge variant="pill" icon="check-badge" color="green">Dapat digunakan</flux:badge>
                @elseif($status == "sudahterdaftar")
                    <flux:badge variant="pill" icon="x-circle" color="red">Telah Terdaftar</flux:badge>
                @elseif($status == "kosong")
                    <flux:badge variant="pill" icon="question-mark-circle" color="zinc">Kode UUID Tidak ditemukan!</flux:badge>
                @endif

                
                
            </div>
    
            <div class="flex">
                <flux:spacer />
                @if ($status == "belumterdaftar")
                <flux:button type="submit" variant="primary" color="">Tambah Kartu</flux:button>
                @endif
            </div>
        </form>
    </flux:modal>
    <div class="w-full md:w-[80%] mx-auto">
        <div class="space-y-4">
            <div class="w-full md:w-[60%] mx-auto">
                
                <flux:text size="md"  color="sky" align="center" class="uppercase"><b>ALAT REGISTRASI</b></flux:text>
                
                <flux:select wire:model.live="idperangkat" id="alat-registerasi" wire:change="pilihperangkat">
                    <flux:select.option value="">Alat Registerasi Dilepaskan</flux:select.option>
                    @foreach ($perangkat as $item)
                        <flux:select.option value="{{ $item->idperangkat }}">Alat {{ $loop->iteration." [ ".$item->kodeperangkat." ]" }}</flux:select.option>
                    @endforeach
               </flux:select>
            </div>
            <flux:separator />
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                
                <flux:select wire:model.live="idkelas">
                    <flux:select.option value="">Semua kelas</flux:select.option>
                    @foreach ($kelas as $item)
                        <flux:select.option value="{{ $item->idkelas }}">{{ $item->namakelas }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:select wire:model.live="idjurusan">
                    <flux:select.option value="">Semua jurusan</flux:select.option>
                    @foreach ($jurusan as $item)
                        <flux:select.option value="{{ $item->idjurusan }}">{{ $item->namajurusan }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:input wire:model.live.debounce.500ms="search" placeholder="Search..." />
            </div>
        </div>

        <flux:table :paginate="$siswa">
            <flux:table.columns>
                <flux:table.column width="5px">No</flux:table.column>
                <flux:table.column>NISN</flux:table.column>
                <flux:table.column>Nama Siswa</flux:table.column>
                <flux:table.column>Rombel</flux:table.column>
                <flux:table.column>Status</flux:table.column>
                <flux:table.column>Action</flux:table.column>
            </flux:table.columns>
        
            @foreach ($siswa as $item)
                <flux:table.rows wire:key="item-{{ $item->idsiswa }}">
                    <flux:table.cell>{{ $loop->iteration + $siswa->firstItem() - 1  }}</flux:table.cell>
                    <flux:table.cell >{{ $item->nisn }}</flux:table.cell>
                    <flux:table.cell class="font-bold">{{ $item->namasiswa }}</flux:table.cell>
                    <flux:table.cell>{{ $item->kelas->namakelas." ".$item->jurusan->inisialjurusan }}</flux:table.cell>
                    <flux:table.cell>
                        @if ($item->kartu()->exists())
                            <flux:badge variant="pill" icon="check-badge" size="sm" color="green">Terdaftar</flux:badge>
                        @else
                            <flux:badge variant="pill" icon="x-circle" size="sm" color="red">Belum Terdaftar</flux:badge>
                        @endif
                    </flux:table.cell>
                    <flux:table.cell>
                        @if (!empty($idperangkat) && !$item->kartu()->exists())
                        <flux:button type="submit" variant="primary" color="blue" icon="credit-card" size="sm" class="w-full" wire:click="buttontambahkartu({{ $item->idsiswa }})">REGISTERASI</flux:button>
                        @elseif($item->kartu()->exists())
                        <flux:badge as="button" variant="pill" color="red" icon="trash" size="sm" class="w-fit" wire:click="hapuskartu({{ $item->idsiswa }})">Hapus Kartu</flux:badge>

                        @else
                        <label onclick="document.getElementById('alat-registerasi').showPicker()">
                            <flux:button as="button" variant="filled" color="blue" class="w-full" size="sm">PILIH PERANGKAT</flux:button> 
                        </label>
                        @endif
                    </flux:table.cell>
                </flux:table.rows>
            @endforeach
        </flux:table>
       
    </div>

</div>
