<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @stack("cssku")
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-zinc-100 dark:bg-zinc-800 p-5">
        <div class="w-full md:w-[50%] mx-auto">
            @livewire("daftar-live", [
                "kode" => $kode,
            ])
        </div>

        @fluxScripts
        @persist('toast')
            <flux:toast />
        @endpersist

        @stack("alert2")
    </body>
</html>
