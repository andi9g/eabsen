<div>
    <div class="w-full md:w-[80%] mx-auto space-y-3">
        <flux:card>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <flux:select wire:model.live="idkelas">
                    <option value="">Semua Kelas</option>
                    @foreach($this->kelas as $k)
                        <option value="{{ $k->idkelas }}">{{ $k->namakelas }}</option>
                    @endforeach
                </flux:select>
                <flux:select wire:model.live="idjurusan">
                    <option value="">Semua Jurusan</option>
                    @foreach($this->jurusan as $j)
                        <option value="{{ $j->idjurusan }}">{{ $j->namajurusan }}</option>
                    @endforeach
                </flux:select>
                <flux:date-picker wire:model.live="tanggal" type="date" wire:change="pilihtanggal" />
                <flux:input wire:model.live.debounce.500ms="search" placeholder="Pencarian..." />
            </div>
        </flux:card>

        <flux:table :paginate="$siswa">
        </flux:table>
        <form class="space-y-3" wire:submit="saveChanges">
        
            <div class="flex">
                <flux:select wire:model.live.throttle.300ms="tampil" class="w-fit">
                    @foreach ($datatampil as $key => $dt)
                        <flux:select.option :value="$key">{{ $dt }}</flux:select.option>
                    @endforeach
                </flux:select>
                <div class="ml-auto">
                    <flux:button type="submit" variant="primary" color="blue" class="w-full" icon="bookmark-square">SIMPAN PERUBAHAN</flux:button>
                </div>
            </div>
            @foreach ($siswa as $item)
            @php
                // dd($item->absensiswa->first()?->status ?? 'a' );
                $status = $data[$item->idsiswa] ?? 'a';
                $terlambat = false;
                
                // dd($status);
                if($status == "h") {
                    $jamseharusnya = strtotime("+2 minutes", strtotime($tanggal." ".$jammasuk));
                    $jamabsen = strtotime($item->absensiswa->where("tanggal", $tanggal)->first()->waktumasuk??"");
                    if($jamabsen > $jamseharusnya) {
                        $terlambat = true;
                    }
                    // dd($jamseharusnya);
                }

                $bg = match ($status) {
                    'h' => 'rgb(159 255 152 / 1)',
                    'i', 's' => 'rgb(255 245 136 / 1)',
                    default => 'rgb(255 158 158 / 1)',
                };
                
                $text = match ($status) {
                    'i', 's' => '#1d1f03',
                    default => '#1d1f03',
                };
                $data["84"] = "s";
            @endphp
                <flux:kanban.card wire:key="data-{{ uniqid() }}">
                    <div class="flex items-center">
                        <div class="flex-col">
                            <flux:heading>{{ $item->namasiswa }}
                                @if ($status == "h" && $terlambat)
                                <flux:badge size="sm" variant="pill" color="red" icon="clock">
                                    Terlambat
                                </flux:badge>
                            @endif
                            </flux:heading>
                            
                        </div>
                        <div class="ml-auto flex-row space-y-2">
                            <flux:select size="sm" wire:change="changeState({{ $item->idsiswa }}, $event.target.value)"  style="background-color: {{ $bg }}; color: {{ $text }}; font-weight: bold;">
                                
                                
                                >
                                <flux:select.option value="a" :selected="$status=='a'">Alpha</flux:select.option>
                                <flux:select.option value="h" :selected="$status=='h'">Hadir</flux:select.option>
                                <flux:select.option value="i" :selected="$status=='i'">Izin</flux:select.option>
                                <flux:select.option value="s" :selected="$status=='s'">Sakit</flux:select.option>
                            </flux:select>
                        </div>
                    </div>
                </flux:kanban.card>
            @endforeach
        </form>

    </div>
</div>
