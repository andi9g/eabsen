<div>
    <div class="w-full md:w-[50%] mx-auto">
        <form action="{{ route('laporanabsensiswa.cetak') }}" method="get" target="_blank">
            
            <flux:card class="space-y-3">
                <flux:heading size="xl">Form Laporan Absensi Siswa</flux:heading>
                <flux:select name="kelas" label="Kelas" wire:model="data.kelas">
                    <flux:select.option value="">Pilih Kelas</flux:select.option>
                    @foreach ($kelas as $key => $item)
                        <flux:select.option :value="$key">{{ $item }}</flux:select.option>
                    @endforeach
                </flux:select>
                <flux:select name="jurusan" label="Jurusan" wire:model="data.jurusan">
                    <flux:select.option value="">Pilih Jurusan</flux:select.option>
                        @foreach ($jurusan as $key => $item)
                            <flux:select.option :value="$key">{{ $item }}</flux:select.option>
                        @endforeach
                </flux:select>
                {{-- <flux:input wire:model="data.tanggal"/> --}}
                <flux:date-picker name="tanggal" mode="range" wire:model="data.tanggal" max-range="31" label="Tanggal Cetak"/>
                <flux:text size="sm">Maksimal 31 Hari</flux:text>

                <div class="flex">
                    <flux:button type="submit" variant="primary" color="blue" class="ml-auto">Cetak</flux:button>
                </div>
            </flux:card>

        </form>
    </div>
</div>
