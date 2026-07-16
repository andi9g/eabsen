<div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="space-y-4">
            <flux:card>
                <flux:heading size="lg">Jam Operasional</flux:heading>
                <flux:text>Silahkan lengkapi form dibawah ini.</flux:text>
            </flux:card>
    
            <flux:card class="">
                <form class="space-y-4" wire:submit="formjamoperasional">
                    <flux:time-picker time-format="24-hour" label="Jam Masuk" type="input" wire:model="data.jammasuk"/>
                    <flux:time-picker time-format="24-hour" label="Jam Pulang" type="input" wire:model="data.jampulang"/>
                    <flux:input type="number" kbd="Menit" label="Keterlambatan (Menit)" wire:model="data.keterlambatan"/>
                    <div class="flex">
                        <flux:button type="submit" variant="primary" color="blue" class="ml-auto">UPDATE</flux:button>
                    </div>
                </form>
            </flux:card>
        </div>
        <div class="space-y-4">
            <flux:card>
                <flux:heading size="lg">Hari Libur</flux:heading>
                <flux:text>Silahkan lengkapi form dibawah ini.</flux:text>
            </flux:card>
    
            <flux:card class="">
                <form class="space-y-4" wire:submit="harilibur">
                   <flux:pillbox wire:model="data.harilibur" multiple placeholder="Pilih Hari Libur" label="Pilih Hari">
                    @foreach ($datahari as $item)
                        <flux:pillbox.option :value="$item->idhari">{{ $item->namahari }}</flux:pillbox.option>
                    @endforeach
                    </flux:pillbox>
                    <div class="flex">
                        <flux:button type="submit" variant="primary" color="blue" class="ml-auto">UPDATE</flux:button>
                    </div>
                </form>
            </flux:card>
        </div>
    </div>
   
</div>
