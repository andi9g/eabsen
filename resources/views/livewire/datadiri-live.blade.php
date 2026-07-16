<div>
    <div class="w-full md:w-[50%] mx-auto space-y-4">
        <flux:card>
            <flux:heading size="lg">Form Data Diri</flux:heading>
            <flux:text>Lengkapi data diri dibawah ini.</flux:text>
        </flux:card>

        <flux:card>
            <form wire:submit="ubahdatadiri" class="space-y-4">
                <flux:input label="Nama Lengkap" wire:model="data.name"/>
                <flux:input label="NIP" wire:model="data.nip"/>
                <flux:input label="Alamat" wire:model="data.alamat"/>

                <div class="flex">
                    <div class="ml-auto">
                        <flux:button type="submit" variant="primary" color="blue" class="">UPDATE DATA DIRI</flux:button>
                    </div>
                </div>
            </form>

        </flux:card>
    </div>
</div>
