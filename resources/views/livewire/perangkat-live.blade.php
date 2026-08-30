<div>
    
    <flux:modal name="detailperangkat" class="md:w-lg">
        <form class="space-y-4">
            <div>
                <flux:heading size="lg">Detail Perangkat</flux:heading>
                <flux:text class="mt-2" >Silahkan masukan identitas berikut kedalam perangkat {{ $value["target"]??'' }}</flux:text>
                <flux:text color="sky">Pastikan memasukan huruf besar atau kecil dengan sesuai.</flux:text>
                <flux:separator class="my-4"/>
            </div>
            
            <flux:input wire:model="value.fungsiperangkat" readonly label="Fungsi"/>
            <flux:input wire:model="value.action" readonly label="Aksi"/>
            <flux:input wire:model="value.kodeperangkat" readonly label="Kode Perangkat"/>
            <flux:input wire:model="value.target" readonly label="Target"/>
            <flux:input wire:model="value.api" readonly label="API"/>

            <div class="flex">
                <flux:spacer />
            </div>
        </form>
    </flux:modal>

    <div class="w-full md:w-[80%] mx-auto space-y-4">
        <flux:tab.group >
            <flux:tabs wire:model="tab" :scrollable="true" wire:click="pindah">
                <flux:tab name="pengelola" class="capitalize">Alat Registrasi</flux:tab>
                <flux:tab name="absensiswa" class="capitalize">Alat Absen</flux:tab>
            </flux:tabs>

            <flux:tab.panel name="pengelola" class="space-y-4">
                
                <div class="flex flex-col md:flex-row md:items-center">
                    <flux:button type="submit" variant="primary" color="blue" wire:click="buttontambahalatregisterasi">Tambah Alat Registrasi</flux:button>
                
                    <div class="mt-3 md:mt-0 md:ml-auto w-full md:w-auto">
                        <div class="grid grid-cols-1 gap-1">
                            {{-- bagian kiri dan bawah menggunakan colom --}}
                            <flux:input icon="magnifying-glass" placeholder="Search orders" class="ml-auto" wire:model.live.debounce.500ms="search"/>
                        </div>
                    </div>
                </div>


                @foreach ($data as $item)
                <flux:callout wire:key="item-{{ $item->idperangkat }}">
                    <div class="flex flex-col md:flex-row md:items-center">
                        <div>
                            <flux:callout.heading class="text-[12pt]">
                                Alat Registerasi {{ $loop->iteration + $data->firstItem() - 1  }}
                            </flux:callout.heading>
                            <flux:text class="text-[12pt]" >
                                Kode : {{ $item->kodeperangkat }} 
                            </flux:text>
                            <div class="mt-2">
                                @if (!empty($item->deviceid))
                                    <flux:badge variant="pill" color="green" icon="check-circle" size="sm">Terdaftar</flux:badge>
                                @else
                                    <flux:badge variant="pill" color="red" icon="x-circle" size="sm">Belum Terdaftar</flux:badge>
                                @endif

                            </div>
                        </div>
                
                        <div class="mt-3 md:mt-0 md:ml-auto w-full md:w-auto">
                            <div class="grid grid-cols-2 gap-4">
                                <flux:badge as="button" variant="pill" color="red" icon="trash" wire:click="buttonhapus({{ $item->idperangkat }})">
                                    Hapus
                                </flux:badge>
                
                                <flux:badge as="button" variant="pill" color="sky" icon="eye" wire:click="buttondetail({{ $item->idperangkat }})">
                                    Lihat Detail
                                </flux:badge>
                            </div>
                        </div>
                    </div>
                </flux:callout>
                
                @endforeach
            </flux:tab.panel>


            <flux:tab.panel name="absensiswa" class="space-y-4">
                <div class="flex flex-col md:flex-row md:items-center">
                    <flux:button type="submit" variant="primary" color="blue" wire:click="buttontambahalatregisterasi">Tambah Alat Absen</flux:button>
                
                    <div class="mt-3 md:mt-0 md:ml-auto w-full md:w-auto">
                        <div class="grid grid-cols-1 gap-1">
                            {{-- bagian kiri dan bawah menggunakan colom --}}
                            <flux:input icon="magnifying-glass" placeholder="Search orders" class="ml-auto" wire:model.live.debounce.500ms="search"/>
                        </div>
                    </div>
                </div>


                @foreach ($data as $item)
                <flux:callout wire:key="item-{{ $item->idperangkat }}">
                    <div class="flex flex-col md:flex-row md:items-center">
                        <div>
                            <flux:callout.heading class="text-[12pt]">
                                ALAT ABSEN SISWA {{ $loop->iteration + $data->firstItem() - 1  }}
                            </flux:callout.heading>
                            <flux:callout.heading class="text-[12pt]" icon="command-line">
                                ( {{ $item->kodeperangkat }} )
                            </flux:callout.heading>
                        </div>
                
                        <div class="mt-3 md:mt-0 md:ml-auto w-full md:w-auto">
                            <div class="grid grid-cols-2 gap-4">
                                <flux:badge as="button" variant="pill" color="red" icon="trash" wire:click="buttonhapus({{ $item->idperangkat }})">
                                    Hapus
                                </flux:badge>
                
                                <flux:badge as="button" variant="pill" color="sky" icon="eye" wire:click="buttondetail({{ $item->idperangkat }})">
                                    Lihat Detail
                                </flux:badge>
                            </div>
                        </div>
                    </div>
                </flux:callout>
                
                @endforeach
            </flux:tab.panel>
        </flux:tab.group>

    </div>
</div>
