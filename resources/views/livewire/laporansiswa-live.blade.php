<div>
    <div class="w-full md:w-[50%] gap-5 mx-auto">
        <form action="{{ route('laporansiswa.cetak', []) }}" method="get">
            <flux:card class="space-y-4">
                <flux:heading size="lg">Form Cetak Data Siswa
                    <flux:text>Silahkan lengkapi form dibawah ini</flux:text>
                </flux:heading>
                <flux:separator />
    
                <div class="grid grid-cols-2 gap-4">
                    <flux:select label="Kelas" name="kelas">
                        <flux:select.option value="">Semua Kelas</flux:select.option>
                        @foreach ($this->kelas as $item)
                            <flux:select.option :value="$item->idkelas">{{ $item->namakelas }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:select label="Jurusan" name="jurusan">
                        <flux:select.option value="">Semua jurusan</flux:select.option>
                        @foreach ($this->jurusan as $item)
                            <flux:select.option :value="$item->idjurusan">{{ $item->inisialjurusan }}</flux:select.option>
                        @endforeach
                    </flux:select>
                </div>
                <div class="flex">
                    <flux:button type="submit" variant="primary" color="blue" class="w-fit ml-auto">Cetak Laporan</flux:button>
                </div>
            </flux:card>

        </form>
    </div>
</div>
