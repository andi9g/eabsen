<div>
    <flux:modal name="buttonformsemester" class="md:w-lg">
        <form wire:submit="formsemester" class="space-y-6">
            <div>
                <flux:heading size="lg">Form Semester</flux:heading>
                <flux:text class="mt-2">Silahkan lengkapi form dibawah ini.</flux:text>
            </div>
                <flux:select label="Semester" wire:model="data.semester">
                    <flux:select.option value="">Pilih Semester</flux:select.option>
                    <flux:select.option value="ganjil">Ganjil</flux:select.option>
                    <flux:select.option value="genap">Genap</flux:select.option>
                </flux:select>
                
                <flux:select label="Tahun Ajaran" wire:model="data.tahunajaran">
                    <flux:select.option value="">Pilih Tahun Ajaran</flux:select.option>
                    @foreach ($tahunajaran as $item)
                        <flux:select.option :value="$item">{{ $item }}</flux:select.option>
                    @endforeach
                </flux:select>
            <div class="flex">
                <flux:spacer />
    
                <flux:button type="submit" variant="primary">{{ $update?"Update":"Tambah" }}</flux:button>
            </div>
        </form>
    </flux:modal>
    <div class="grid grid-cols-1 md:grid-cols-[1fr_3fr] gap-5">
        <div class="detail ">
            <flux:card class="space-y-4">
                <flux:heading size="lg">Semester Sekarang :</flux:heading>
                <flux:kanban.card>
                    <flux:callout.heading class="text-xl uppercase">Semester {{ $semesteraktif->semester->semester??"Belum ada Semester" }}</flux:callout.heading>
                    <flux:callout.heading>{{ $semesteraktif->semester->tahunajaran??"Belum ada TP" }}</flux:callout.heading>
                </flux:kanban.card>
            </flux:card>
        </div>
        <div class="tabel">
            <flux:card class="space-y-4">
                <flux:heading size="lg">Tabel Semester</flux:heading>

                <div class="act">
                    <div class="flex flex-col md:flex-row md:items-center">
                        {{-- bagian atas --}}
                        <flux:button type="submit" variant="primary" color="blue" wire:click="buttonformsemester">Tambah Semester</flux:button>
                        <div class="mt-3 md:mt-0 md:ml-auto w-full md:w-auto">
                            <div class="grid grid-cols-1 gap-1">
                                {{-- bagian kiri dan bawah menggunakan colom --}}
                                <flux:input icon="magnifying-glass" placeholder="Search orders" wire:model.live.debounce.500ms="search"/>      
                            </div>
                        </div>
                    </div>
                </div>

                <flux:table :paginate="$semester">
                    <flux:table.columns>
                        <flux:table.column width="5px">No</flux:table.column>
                        <flux:table.column>Tahun Ajaran</flux:table.column>
                        <flux:table.column>Semester</flux:table.column>
                        <flux:table.column>Status</flux:table.column>
                        <flux:table.column>Action</flux:table.column>
                    </flux:table.columns>
                
                    @foreach ($semester as $item)
                        <flux:table.rows wire:key="item-{{ $item->idsemester }}">
                            <flux:table.cell>{{ $loop->iteration + $semester->firstItem() - 1  }}</flux:table.cell>
                            <flux:table.cell>{{ $item->tahunajaran }}</flux:table.cell>
                            <flux:table.cell>{{ $item->semester }}</flux:table.cell>
                            <flux:table.cell>
                                <flux:switch wire:key="{{ uniqid() }}" :checked="$item->semesteraktif()->exists()" wire:change="ubahaktif({{ $item->idsemester }}, $event.target.checked)"/>
                            </flux:table.cell>
                            <flux:table.cell>
                                <flux:badge as="button" variant="pill" icon="pencil-square" color="blue" wire:click="buttonupdatesemester({{ $item->idsemester }})">edit</flux:badge>
                                <flux:badge as="button" variant="pill" icon="trash" color="red" wire:click="hapussemester({{ $item->idsemester }})">edit</flux:badge>
                            </flux:table.cell>
                        </flux:table.rows>
                    @endforeach
                </flux:table>
            </flux:card>
        </div>

    </div>
</div>
