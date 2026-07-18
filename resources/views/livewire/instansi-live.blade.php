<div>
    <div class="w-full md:w-[80%] mx-auto space-y-4">
        <flux:card class="">
                <div class="">
                    <flux:heading size="xl">INSTANSI</flux:heading>
                    <flux:text>Silahkan lengkapi identitas instansi.</flux:text>
                </div>
            </flux:card>
        <div class="grid grid-cols-1 md:grid-cols-[1fr_3fr] gap-5">
            <flux:card class="space-y-3">
                <img src="{{ url('/disk-s3/' . rawurlencode($data['logo'])) }}" alt="Logo Sekolah"/>
                <form action="" wire:submit="updatelogo" class="space-y-3">
                    <flux:file-upload wire:model="photos" >
                        <flux:file-upload.dropzone wire:model="photos"
                            heading="Drop files or click to browse"
                            text="JPG, PNG, GIF up to 10MB"
                            with-progress
                            inline
                        />
                    </flux:file-upload>
                    @if (!empty($photos))
                    <div class="mt-3">
                        <flux:button type="submit" variant="primary" color="blue" class="w-full">UPDATE LOGO</flux:button>
                    </div>
                    @endif

                </form>
            </flux:card>
            
            <flux:card >
                <form wire:submit="updateinstansi" class="space-y-5">
                    <flux:input label="NPSN" placeholder="masukan npsn" wire:model="data.npsn"/>
                    <flux:input label="Nama Instansi" placeholder="masukan nama instansi" wire:model="data.namainstansi"/>
                    <flux:input label="Kab/Kota" placeholder="masukan kab/kota" wire:model="data.kota"/>
                    <flux:input label="Alamat" placeholder="masukan alamat" wire:model="data.alamat"/>
        
                    <div class="flex">
                        <flux:button type="submit" variant="primary" color="blue" class="ml-auto">UPDATE</flux:button>
                    </div>
    
                </form>
            </flux:card>
        </div>
    </div>
</div>
