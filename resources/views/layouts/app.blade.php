<x-layouts::app.sidebar :title="$title ?? null">
    <flux:main class="pb-25">
        {{ $slot }}
    </flux:main>
</x-layouts::app.sidebar>
