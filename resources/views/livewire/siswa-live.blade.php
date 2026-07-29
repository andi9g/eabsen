<div>

    
    <flux:modal name="modal-gambar" class="md:w-lg">
        <form wire:submit="updategambar" class="space-y-6">
            <div>
                <flux:heading size="lg">Gambar Siswa</flux:heading>
                <flux:text class="mt-2">Silahkan lengkapi form dibawah ini.</flux:text>
            </div>

            <center>
                @if (!empty($data['foto']))
                    <img src="/disk-s3/{{ $data['foto'] }}" alt="Foto Profil">

                    <div class="flex mt-2">
                        <flux:badge as="button" class="mx-auto" color="red" wire:click="hapusgambar">Hapus Gambar</flux:badge>
                    </div>
                @endif
            </center>
            <flux:file-upload wire:model="gambar" label="Upload file">
                <flux:file-upload.dropzone heading="Drop file here or click to browse" text="JPG, PNG, GIF up to 2MB" />
            </flux:file-upload>
        
            <div class="mt-3 flex flex-col gap-2">
                @if ($gambar)
                    <flux:file-item
                        :heading="$gambar->getClientOriginalName()"
                        :size="$gambar->getSize()"
                    >
                        <x-slot name="actions">
                            <flux:file-item.remove wire:click="removenamafungsi" aria-label="{{ 'Remove file: ' . $gambar->getClientOriginalName() }}" />
                        </x-slot>
                    </flux:file-item>
                @endif
            </div>
            
    
            <div class="flex">
                <flux:spacer />
    
                <flux:button type="submit" variant="primary">Update</flux:button>
            </div>
        </form>
    </flux:modal>
    
    <flux:modal name="buttontambahsiswa" class="md:w-lg">
        <form wire:submit="tambahdata" class="space-y-4">
            <div>
                <flux:heading size="lg">Form Data Siswa</flux:heading>
                <flux:text class="mt-2">Silahkan lengkapi form dibawah ini.</flux:text>
            </div>
            <flux:input wire:model="data.nisn" placeholder="masukan nisn" label="NISN" :disabled="$update"/>
            <flux:input wire:model="data.nis" placeholder="masukan nis" label="NIS"/>
            <flux:input wire:model="data.namasiswa" placeholder="masukan nama siswa" label="Nama Siswa"/>
            <flux:input wire:model="data.agama" placeholder="masukan agama" label="Agama"/>
            <flux:input wire:model="data.tempatlahir" placeholder="masukan tempat lahir" label="Tempat Lahir"/>
            <flux:input wire:model="data.tanggallahir" placeholder="masukan tanggal lahir" label="Tanggal Lahir" type="date"/>


            <flux:select label="Kelas" wire:model="data.idkelas">
                <flux:select.option value="">Pilih Kelas</flux:select.option>
                @foreach ($kelas as $item)
                    <flux:select.option :value="$item->idkelas">{{ $item->namakelas }}</flux:select.option>
                @endforeach
            </flux:select>
            <flux:select label="Jurusan" wire:model="data.idjurusan">
                <flux:select.option value="">Pilih Jurusan</flux:select.option>
                @foreach ($jurusan as $item)
                    <flux:select.option :value="$item->idjurusan">{{ $item->inisialjurusan }}</flux:select.option>
                @endforeach
            </flux:select>
            
            <flux:menu.radio.group wire:model="data.jk" >
                <flux:label>Jenis Kelamin</flux:label>
                <div class="flex flex-row space-x-5 mb-4">
                    <flux:radio value="L" label="Laki-Laki"/>
                    <flux:radio value="P" label="Perempuan"/>
                </div>
            </flux:menu.radio.group>

            <flux:input wire:model="data.alamat" placeholder="masukan alamat" label="Alamat"/>
            <flux:input wire:model="data.hp" placeholder="masukan no hp" label="No HP"/>
            <div class="flex">
                <flux:spacer />
    
                <flux:button type="submit" variant="primary">{{ $update?"UPDATE":"TAMBAH" }}</flux:button>
            </div>
        </form>
    </flux:modal>
    <div class="w-full md:w-[80%] mx-auto">
        <div class="flex flex-col md:flex-row md:items-center gap-2">
            {{-- bagian atas --}}
            <flux:button variant="primary" color="blue" class="" wire:click="buttontambahsiswa">Tambah Data Siswa</flux:button>
            <flux:button href="{{ route('import', []) }}" variant="primary" color="gray" class="">Import</flux:button>
            <div class="mt-3 md:mt-0 md:ml-auto w-full md:w-auto">
                <div class="grid grid-cols-1 gap-1 space-y-2">
                    {{-- bagian kiri dan bawah menggunakan colom --}}
                    <div class="grid grid-cols-2 gap-3">
                         <flux:select wire:model.live.debounce.500ms="idkelas">
                            <flux:select.option value="">Kelas</flux:select.option>
                            @foreach ($kelas as $item)
                                <flux:select.option :value="$item->idkelas">{{ $item->namakelas }}</flux:select.option>
                            @endforeach
                        </flux:select>
                         <flux:select wire:model.live.debounce.500ms="idjurusan">
                            <flux:select.option value="">Jurusan</flux:select.option>
                            @foreach ($jurusan as $item)
                                <flux:select.option :value="$item->idjurusan">{{ $item->inisialjurusan }}</flux:select.option>
                            @endforeach
                        </flux:select>
                    </div>
                    <flux:input icon="magnifying-glass" placeholder="Search orders" class="w-full" wire:model.live.debounce.500ms="search"/>
                </div>
            </div>
        </div>

        <flux:table :paginate="$siswa" class="mt-2">
        </flux:table>

        <flux:table>
            <flux:table.columns>
                <flux:table.column width="5px">No</flux:table.column>
                <flux:table.column>NIS/NISN</flux:table.column>
                <flux:table.column>Nama Siswa</flux:table.column>
                <flux:table.column>JK</flux:table.column>
                <flux:table.column>Rombel</flux:table.column>
                <flux:table.column>Foto</flux:table.column>
                <flux:table.column>action</flux:table.column>
            </flux:table.columns>
        
            @foreach ($siswa as $item)
                <flux:table.rows wire:key="item-{{ $item->idsiswa }}">
                    <flux:table.cell>{{ $loop->iteration + $siswa->firstItem() - 1  }}</flux:table.cell>
                    <flux:table.cell>{{ empty($item->nis)?"-":$item->nis }} / {{ $item->nisn }}</flux:table.cell>
                    <flux:table.cell>{{ $item->namasiswa }}</flux:table.cell>
                    <flux:table.cell>{{ $item->jk }}</flux:table.cell>
                    <flux:table.cell>{{ $item->kelas->namakelas." ".$item->jurusan->inisialjurusan }}</flux:table.cell>
                    <flux:table.cell>
                        @if (empty($item->foto))
                            <flux:badge as="button" variant="pill" icon="x-circle" color="red" wire:click="bukagambar({{ $item->idsiswa }}, '{{ $item->foto??'profil.png' }}')">Tambah Gambar </flux:badge>
                        @else
                            <flux:badge as="button" variant="pill" icon="check" color="green" wire:click="bukagambar({{ $item->idsiswa }}, '{{ $item->foto??'profil.png' }}')">Update Gambar </flux:badge>
                        @endif
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:badge as="button" variant="pill" icon="pencil-square" color="blue" wire:click="buttonupdatesiswa({{ $item->idsiswa }})">edit</flux:badge>
                        <flux:badge as="button" variant="pill" icon="trash" color="red" wire:click="buttonhapus({{ $item->idsiswa }})">Hapus</flux:badge>
                    </flux:table.cell>
                </flux:table.rows>
            @endforeach
        </flux:table>

    </div>
</div>
