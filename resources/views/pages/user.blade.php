<x-layouts::app :title="$judul">
    <flux:heading size="xl" level="2">{{ $judul }}</flux:heading>
        <flux:breadcrumbs class="mt-2 mb-4 text-base">
            <flux:breadcrumbs.item href="{{ route('dashboard', []) }}">Home</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>User</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    <flux:separator variant="subtle"  class="mb-4"/>

    
    @livewire("user-live")
   
    
</x-layouts::app>
