<div>
    <div class="w-full md:w-[80%] mx-auto space-y-3">
        <flux:card>
            <div class="grid grid-cols-1 md:grid-cols-[2fr_1fr_1fr] gap-3">
                <div class="grid grid-cols-2 gap-3">
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
                </div>
                <flux:date-picker wire:model.live="tanggal" type="date" wire:change="pilihtanggal" />
                <flux:input wire:model.live.debounce.500ms="search" placeholder="Pencarian..." />
            </div>
        </flux:card>

        <flux:table :paginate="$siswa">
        </flux:table>

        <div x-show="$wire.idkelas && $wire.idjurusan">
            <div class="flex">
                <div class="ml-auto">
                    <flux:radio.group wire:model="pilihan" wire:change="kehadiran" variant="segmented">
                        <flux:radio value="" icon="arrow-path"/>
                        <flux:radio value="a" label="A"/>
                        <flux:radio value="h" label="H"/>
                    </flux:radio.group>

                </div>
            </div>
        </div>
        @foreach ($siswa as $item)
        @php
            // dd($item->absensiswa->first()?->status ?? 'a' );
            $status =  $dataUpdate[$item->idsiswa]??$item->absensiswa->where("tanggal", $tanggal)->first()?->status ?? 'a';
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
        @endphp
            <flux:kanban.card wire:key="data-{{ $item->idsiswa }}">
                <div class="flex flex-col md:flex-row md:items-center">
                    <div class="grid grid-cols-1 gap-1">
                        <flux:heading>{{ $item->namasiswa }}</flux:heading>
                        <flux:text>{{ $item->kelas->namakelas." ".$item->jurusan->inisialjurusan  }} - {{ $item->nisn }}</flux:text>
                        @if ($status == "h" && $terlambat)
                            <flux:badge size="sm" variant="pill" color="red" icon="clock" class="w-fit">
                                Terlambat
                            </flux:badge>
                        @endif
                    </div>
                
                    <div class="mt-1 md:mt-0 md:ml-auto w-full md:w-auto">
                        <div class="grid grid-cols-1 gap-1">
                            <div 
                                wire:key="status-{{ $item->idsiswa }}-{{ $status }}"
                                class="mt-3 md:mt-0 md:ml-auto w-full md:w-auto" 
                                x-data="{ status: '{{ $status }}' }"
                            >
                                <div class="grid grid-cols-1 gap-1">
                                    
                                    <!-- Radio Group Segmented Flux UI -->
                                    <flux:radio.group 
                                        variant="segmented" 
                                        x-model="status"
                                        wire:change="changeState({{ $item->idsiswa }}, $event.target.value)"
                                        class="!rounded-full p-1 border border-zinc-300 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800"
                                    >
                                        <!-- Opsi Izin ('i') -->
                                        <flux:radio 
                                            value="i" 
                                            label="Izin" 
                                            class="!rounded-full px-4 py-1.5 transition-all font-medium text-xs text-center justify-center cursor-pointer data-[checked]:!bg-sky-500 data-[checked]:!text-white" 
                                        />
                            
                                        <!-- Opsi Sakit ('s') -->
                                        <flux:radio 
                                            value="s" 
                                            label="Sakit" 
                                            class="!rounded-full px-4 py-1.5 transition-all font-medium text-xs text-center justify-center cursor-pointer data-[checked]:!bg-yellow-500 data-[checked]:!text-white" 
                                        />
                            
                                        <!-- Opsi Alfa ('a') -->
                                        <flux:radio 
                                            value="a" 
                                            label="Alfa" 
                                            class="!rounded-full px-4 py-1.5 transition-all font-medium text-xs text-center justify-center cursor-pointer data-[checked]:!bg-rose-500 data-[checked]:!text-white" 
                                        />
                            
                                        <!-- Opsi Hadir ('h') -->
                                        <flux:radio 
                                            value="h" 
                                            label="Hadir" 
                                            class="!rounded-full px-4 py-1.5 transition-all font-medium text-xs text-center justify-center cursor-pointer data-[checked]:!bg-emerald-500 data-[checked]:!text-white" 
                                        />
                                    </flux:radio.group>
                            
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </flux:kanban.card>
        @endforeach

    </div>
    <br>
    <br>

    {{-- @if (!empty($dataUpdate)) --}}
    <flux:button 
        x-show='$wire.dataUpdate && Object.keys($wire.dataUpdate).length > 0'
         id="tombol-absen-pojok"
         icon="clipboard-document-check" 
         variant="primary"
         class="!fixed !bottom-[11%] !right-6 md:!bottom-10 md:!right-[10%] !top-auto !left-auto !z-50 
             !rounded-full !px-5 !py-3 !font-medium !text-sm !tracking-wider !text-white
             !bg-emerald-600/75 !backdrop-blur-xl !border !border-emerald-400/40
             !shadow-[0_8px_32px_0_rgba(16,185,129,0.37)]
             hover:!bg-emerald-500/85 hover:!border-emerald-300/60
             hover:!shadow-[0_12px_40px_0_rgba(16,185,129,0.55)] 
             hover:!scale-105 active:!scale-95
             !transition-all !duration-300 !ease-out" 
         wire:click="saveChanges"
     >
         Simpan Perubahan
     </flux:button>
    {{-- @endif --}}
</div>
