<div>
    <div class="w-full md:w-[60%] mx-auto space-y-5">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <flux:callout color="sky">
                <div class="flex flex-wrap space-y-3">
                    <flux:callout.heading>Total Siswa : {{ $siswa }}</flux:callout.heading>
    
                    <div class="ml-auto">
                        <a href="{{ route('siswa', []) }}">
                            <flux:badge variant="pill" as="button" color="yellow" size="sm" icon="eye">Lihat Data</flux:badge>
                        </a>
                    </div>
                </div>
            </flux:callout>
            <flux:callout color="sky">
                <div class="flex flex-wrap space-y-3">
                    <flux:callout.heading>Total Kelas : {{ $kelas }}</flux:callout.heading>
    
                    <div class="ml-auto">
                        <a href="{{ route('rombel', []) }}">
                            <flux:badge variant="pill" as="button" color="yellow" size="sm" icon="eye">Lihat Data</flux:badge>
                        </a>
                    </div>
                </div>
            </flux:callout>
            <flux:callout color="sky">
                <div class="flex flex-wrap space-y-3">
                    <flux:callout.heading>Total Jurusan : {{ $jurusan }}</flux:callout.heading>
    
                    <div class="ml-auto">
                        <a href="{{ route('rombel', []) }}">
                            <flux:badge variant="pill" as="button" color="yellow" size="sm" icon="eye">Lihat Data</flux:badge>
                        </a>
                    </div>
                </div>
            </flux:callout>
        </div>
        <flux:card size="lg" class="space-y-3">
            <flux:heading size="lg">FORM IMPORT DATA SISWA</flux:heading>
            <flux:separator />

            <div class="text-center">
                <flux:text>
                    Pastikan format Excel <strong>sesuai</strong> Template yang tersedia. Template dapat diunduh melalui tautan <strong>dibawah ini</strong>. Setelah mengisi data pada template, silakan unggah kembali file tersebut untuk memproses import data siswa.
                </flux:text>
                <a href="">
                    <flux:badge variant="pill" color="orange" size="sm">Unduh Template</flux:badge>
                </a>
            </div>

            <flux:separator text="FORM IMPORT"/>

            <form wire:submit="importfile" class="space-y-3">
                <flux:file-upload wire:model="file" label="Upload file">
                    <flux:file-upload.dropzone heading="Drop file here or click to browse" text="XLSX, XLS up to 2MB" />
                </flux:file-upload>
            
                <div class="mt-3 flex flex-col gap-2">
                    @if ($file)
                        <flux:file-item
                            :heading="$file->getClientOriginalName()"
                            :size="$file->getSize()"
                        >
                            <x-slot name="actions">
                                <flux:file-item.remove wire:click="removeimportfile" aria-label="{{ 'Remove file: ' . $file->getClientOriginalName() }}" />
                            </x-slot>
                        </flux:file-item>
                    @endif
                </div>
            
            <div class="flex">
                <div class="">
                    <flux:text size="sm" for="switch" color="green">
                        @if ($switch)
                            Merubah data lama dan tambah data baru
                        @else
                            Cukup menambah data baru dan tidak merusak data yang sudah ada
                        @endif
                    </flux:text>
                    <flux:switch wire:model.live="switch" align="right" />
                </div>
                <flux:button 
                    type="submit" 
                    variant="primary" 
                    color="blue"
                    class="ml-auto"
                >
                    UPDATE
                </flux:button>
            </div>
            </form>
        </flux:card>

            

    </div>
</div>
