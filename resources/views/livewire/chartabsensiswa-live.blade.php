<div class="grid auto-rows-min gap-4 md:grid-cols-3">
    @foreach ($dataArray as $key => $item)
    @php
        $kelas = [
            "X", "XI", "XII"
        ];
    @endphp
    <div class="p-4 relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
        <flux:callout.heading>Absensi Kelas {{ $kelas[$key] }}</flux:callout.heading>
        <flux:chart wire:model="data.{{ $key }}" class="w-full h-full" >
            <flux:chart.svg>
                <flux:chart.bar field="Jumlah" class="text-blue-500" radius="0" width="85%" />

                <flux:chart.axis axis="x" field="Status" tick-count="5000">
                    <flux:chart.axis.tick />
                    <flux:chart.axis.line />
                </flux:chart.axis>

                <flux:chart.axis axis="y" :format="['useGrouping' => true]" tick-prefix="" :tick-values="$this->generateTickValues($dataArray[$key])">
                    <flux:chart.axis.grid />
                    <flux:chart.axis.tick />
                </flux:chart.axis>

                <flux:chart.cursor type="area" />
            </flux:chart.svg>

            <flux:chart.tooltip>
                <flux:chart.tooltip.heading field="Status" />
                <flux:chart.tooltip.value field="Jumlah" label="Jumlah" :format="['useGrouping' => true]" prefix="" />
            </flux:chart.tooltip>
        </flux:chart>
    </div>
    @endforeach
   
</div>


