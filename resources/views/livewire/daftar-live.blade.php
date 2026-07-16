<div class="space-y-4">
    <flux:card>
        <div class="flex gap-4">
            <div class="">
                <img src="{{ Storage::disk('s3')->temporaryUrl($instansi->logo, now()->addMinutes(10)) }}" alt="logo" width="50px">
            </div>
            <div class="grid grid-cols-1 gap-0">
                    <flux:heading size="xl" level="2">{{ $instansi->namainstansi }}</flux:heading>
                    <flux:heading size="lg" level="2">{{ $instansi->alamat }}</flux:heading>
                    <flux:text class="text-base">Daftar Akun</flux:text>
            </div>
        </div>
    </flux:card>

    <flux:card>
        <form wire:submit="tambahakun" class="space-y-5">
            <flux:input label="Nama Lengkap" wire:model="data.name" placeholder="masukan nama lengkap"/>
            <flux:input label="Email" wire:model="data.email" placeholder="masukan email" type="email"/>
            <flux:input label="Password" wire:model="data.password" placeholder="masukan password" type="password"/>
            <flux:input label="Ulangi Password" wire:model="data.ulangipassword" placeholder="ulangi password" type="password"/>

            <div class="flex">
                <flux:button type="submit" variant="primary" color="blue" class="ml-auto">Tambah Akun</flux:button>
            </div>
        </form>
    </flux:card>
</div>
