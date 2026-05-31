<div>

     <flux:modal name="tomboltambahadmin" class="md:w-lg">
        <form wire:submit="tambahadmin" class="space-y-4">
            <div>
                <flux:heading size="lg">Tambah Admin</flux:heading>
                <flux:text class="mt-2">Silahkan lengkapi form dibawah ini.</flux:text>
            </div>
            
            <flux:input wire:model="name" label="Nama Admin" placeholder="Masukkan nama lengkap" required />
            <flux:input wire:model="email" label="Email" placeholder="Masukkan email" required />

            @if (!$idinstansi)
                <flux:separator text="INSTANSI"/>
                <flux:input wire:model="instansi" label="Nama Instansi" placeholder="Masukkan nama instansi" required />
                <flux:input wire:model="npsn" label="NPSN" type="number" placeholder="Masukkan nama npsn" required />                
            @endif

            

            <div class="flex">
                <flux:spacer />
    
                <flux:button type="submit" variant="primary">Tambah</flux:button>
            </div>
        </form>
    </flux:modal>

    <div class="w-full md:w-[80%] space-y-5 mx-auto">
        <div class="flex flex-col md:flex-row md:items-center">
            <flux:button wire:click="tomboltambahadmin" variant="primary" color="sky">Tambah Admin</flux:button>

            <div class="mt-3 md:mt-0 md:ml-auto w-full md:w-auto">
                <div class="grid grid-cols-1 gap-1">
                    <flux:input icon="magnifying-glass" class="w-fit ml-auto" placeholder="Search..." wire:model.live="search"/>
                </div>
            </div>
        
        </div>
    


        <flux:table :paginate="$user" class="text-xl">
        
        </flux:table>
        @if (count($user)==0)
            <flux:kanban.card class="text-center">
                Tidak ada data yang ditemukan
            </flux:kanban.card>
        @endif
        @foreach ($user as $item)
        <flux:callout icon="user">
            
            
            <div class="flex flex-col md:flex-row md:items-center">
                <div>
                    <flux:callout.heading class="text-[12pt]">
                        {{ $item->name }}
                    </flux:callout.heading>
                    <flux:callout.text>
                        {{ $item->email }}
                    </flux:callout.text>
                    <flux:callout.text>
                        {{ $item->detailuser->instansi->namainstansi??'' }}
                    </flux:callout.text>
                </div>
        
                <div class="mt-3 md:mt-0 md:ml-auto w-full md:w-auto">
                    <div class="grid grid-cols-2 gap-2">
                        <flux:badge as="button" variant="pill" color="red" icon="trash" button>
                            Hapus
                        </flux:badge>
        
                        <flux:badge as="button" variant="pill" color="blue" icon="pencil">
                            Edit
                        </flux:badge>
                    </div>
                </div>
            </div>
        </flux:callout>
      
        
        @endforeach
    </div>



    
    
   
</div>
