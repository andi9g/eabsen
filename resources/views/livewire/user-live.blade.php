<div>

    

    <div class="w-full md:w-[80%] space-y-5 mx-auto">
        <div class="flex flex-col md:flex-row md:items-center">

            <div class="mt-3 md:mt-0 md:ml-auto w-full md:w-auto">
                <div class="grid grid-cols-1 gap-1">
                    <flux:input icon="magnifying-glass" class="w-fit ml-auto" placeholder="Search..." wire:model.live.debounce.500ms="search"/>
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
        <flux:callout icon="user" wire:key="item-{{ $item->id }}">
            
            
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
                    <div class="grid grid-cols-1 gap-1">
                        <flux:badge as="button" variant="pill" color="red" icon="trash" wire:click="hapususer({{ $item->iduser }})">
                            Hapus
                        </flux:badge>
                    </div>
                </div>
            </div>
        </flux:callout>
      
        
        @endforeach
    </div>



    
    
   
</div>
