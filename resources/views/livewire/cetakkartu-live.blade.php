<div>
    <div class="w-full md:w-[80%] mx-auto space-y-5">
        @if ($akses == 'superadmin')
        <flux:card>
            <flux:select label="Pilih Sekolah" wire:model.live="idinstansi" >
                <flux:select.option value="">Silahkan Pilih Sekolah</flux:select.option>
                @foreach ($instansi as $item)
                    <flux:select.option :value="$item->idinstansi">[{{ $item->npsn }}] - {{ $item->namainstansi }}</flux:select.option>
                @endforeach
            </flux:select>
        </flux:card>
        @endif

        @if (!empty($idinstansi))
            <flux:card>
                <form action="" wire:submit="filtercetak" class="space-y-4">
                    <flux:select label="Pilih Kelas" wire:model="data.idkelas">
                        <flux:select.option value="">Pilih Kelas</flux:select.option>
                        @foreach ($kelas as $item)
                            <flux:select.option :value="$item->idkelas">{{ $item->namakelas }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:select label="Pilih Jurusan" wire:model="data.idjurusan">
                        <flux:select.option value="">Pilih Jurusan</flux:select.option>
                        @foreach ($jurusan as $item)
                            <flux:select.option :value="$item->idjurusan">{{ $item->inisialjurusan }}</flux:select.option>
                        @endforeach
                    </flux:select>

                    <flux:button type="submit" variant="primary" color="blue" class="w-full">Proses</flux:button>

                </form>
            </flux:card>
        @endif

        @if (!empty($paginate))
            <flux:card class="space-y-4">
                <flux:select label="Pilih Halaman" wire:model.live="data.page">
                    <flux:select.option value="">Pilih Halaman</flux:select.option>
                    @for ($i=1; $i <= $paginate; $i++)
                        <flux:select.option :value="$i">Halaman ke {{ $i }}</flux:select.option>
                    @endfor
                </flux:select>

                @if (!empty($data['page']))
                    <div class="flex">
                        <flux:button href="{{ route('cetak.kartu', $data) }}" type="submit" variant="primary" color="green" class="w-fit ml-auto">CETAK KARTU</flux:button>
                    </div>
                @endif
            </flux:card>

        @endif

       
    </div>
</div>
