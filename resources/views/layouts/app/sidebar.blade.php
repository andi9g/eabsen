@php
    $avatar = auth()->user()->socialite->avatar??'profil.png';
    if($avatar == 'profil.png') {
        $avatar = url('/disk-s3/'.$avatar, []);
    }
    $logo = auth()->user()->detailuser?->instansi?->logo??'';
    if(!empty($logo)) {
        $logo = url('/disk-s3/'.$logo, []);
    }
    
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @stack("cssku")
        @include('partials.head')
        <!-- Panggil route manifest Laravel -->
        
        <link rel="icon" href="{{ url('/disk-s3/'.auth()->user()->detailuser?->instansi?->logo??'profil.png') }}">
        <link rel="apple-touch-icon" href="{{ url('/disk-s3/pwa/'.auth()->user()->detailuser?->instansi?->logo??'profil.png') }}">
        <link rel="manifest" href="{{ url('manifest.json?idinstansi='.Session::get('idinstansi'), []) }}">
        <meta name="theme-color" content="#2563eb">
    </head>
    <body class="min-h-screen bg-zinc-100 dark:bg-zinc-800">
         <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('SW Registered!', reg))
                    .catch(err => console.log('SW Registration Failed!', err));
                });
            }
        </script>

        <flux:sidebar sticky collapsible class="bg-zinc-200 dark:bg-zinc-900 border-r border-zinc-300 dark:border-zinc-700">
            <flux:sidebar.header>
                <flux:sidebar.brand
                    href="#"
                    logo="{{ $logo }}"
                    logo:dark="{{ $logo }}"
                    name="Absen Digital"
                />
                <flux:sidebar.collapse class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <x-sidebar-menu :menus="config('menu')"/>         
            </flux:sidebar.nav>

            <flux:sidebar.spacer />
            <flux:dropdown position="top" align="start" class="max-lg:hidden">
                <flux:sidebar.profile avatar="{{ $avatar }}" :name="auth()->user()->name" />
                <flux:menu>
                   <flux:sidebar.nav>
                        <flux:sidebar.item icon="cog-6-tooth" href="{{ route('settings', []) }}" :current="request()->is('settings*')">Settings</flux:sidebar.item>
                        <flux:sidebar.item icon="user" href="{{ route('datadiri', []) }}" :current="request()->routeIs('datadiri')">Data Diri</flux:sidebar.item>
                    </flux:sidebar.nav>
                    <flux:menu.separator />
                    <form action="{{ route('logout', []) }}" method="POST">
                        @csrf
                    <flux:menu.item type="submit" icon="arrow-right-start-on-rectangle">Logout</flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>


    <div class="lg:hidden">
        <x-mobile-menu :menus="config('menu')"/>
    </div>

    <flux:header class="lg:hidden">
        <flux:button x-data x-on:click="$flux.dark = ! $flux.dark" icon="moon" variant="subtle" aria-label="Toggle dark mode" />
        
        
        <flux:spacer />
        <flux:dropdown position="top" align="start">
            <flux:profile avatar="{{ $avatar }}" />
            <flux:menu>
                <flux:sidebar.nav>
                        <flux:sidebar.item icon="cog-6-tooth" href="{{ route('settings', []) }}" :current="request()->is('settings*')">Settings</flux:sidebar.item>
                        <flux:sidebar.item icon="user" href="{{ route('datadiri', []) }}" :current="request()->routeIs('datadiri')">Data Diri</flux:sidebar.item>
                    </flux:sidebar.nav>
                <flux:menu.separator />
                <form action="{{ route('logout', []) }}" method="POST">
                    @csrf
                    <flux:menu.item type="submit" icon="arrow-right-start-on-rectangle">Logout</flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    @livewire("alert-live")

        {{-- <div class="pb-20 lg:pb-0"> --}}
            {{ $slot }}
        {{-- </div> --}}
       
        

        @fluxScripts
        @persist('toast')
            <flux:toast />
        @endpersist

        @stack("alert2")
        @stack('jsku')
    </body>
</html>
