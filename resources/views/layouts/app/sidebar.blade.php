<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-zinc-100 dark:bg-zinc-800">
        <flux:sidebar sticky collapsible class="bg-zinc-200 dark:bg-zinc-900 border-r border-zinc-300 dark:border-zinc-700">
        <flux:sidebar.header>
            <flux:sidebar.brand
                href="#"
                logo="https://fluxui.dev/img/demo/logo.png"
                logo:dark="https://fluxui.dev/img/demo/dark-mode-logo.png"
                name="Acme Inc."
            />
            <flux:sidebar.collapse class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
        </flux:sidebar.header>
        <flux:sidebar.nav>
            <flux:sidebar.item icon="home" href="{{ route('dashboard', []) }}" :current="request()->routeIs('dashboard')">Home</flux:sidebar.item>
            
            <flux:sidebar.item icon="document-text" href="#">Documents</flux:sidebar.item>
            <flux:sidebar.item icon="calendar" href="#">Calendar</flux:sidebar.item>
            {{-- //group --}}
            <flux:sidebar.group expandable :expanded="(request()->routeIs('admin') || request()->routeIs('pegawai') || request()->routeIs('user'))" icon="users" heading="ACCOUNT" class="grid">
                <flux:sidebar.item badge="12" href="{{ route('admin') }}" :current="request()->routeIs('admin')">Admin</flux:sidebar.item>
                <flux:sidebar.item badge="12" href="{{ route('pegawai') }}" :current="request()->routeIs('pegawai')">Pegawai</flux:sidebar.item>
                <flux:sidebar.item badge="12" href="{{ route('user') }}" :current="request()->routeIs('user')">User</flux:sidebar.item>
            </flux:sidebar.group>

            <flux:sidebar.group expandable :expanded="false" icon="star" heading="Pengaturan" class="grid">
                <flux:sidebar.item href="#">Marketing site</flux:sidebar.item>
                <flux:sidebar.item href="#">Android app</flux:sidebar.item>
                <flux:sidebar.item href="#">Brand guidelines</flux:sidebar.item>
            </flux:sidebar.group>
        </flux:sidebar.nav>
        <flux:sidebar.spacer />
        <flux:sidebar.nav>
            <flux:sidebar.item icon="cog-6-tooth" href="{{ route('settings', []) }}">Settings</flux:sidebar.item>
            <flux:sidebar.item icon="information-circle" href="#">Help</flux:sidebar.item>
        </flux:sidebar.nav>
        <flux:dropdown position="top" align="start" class="max-lg:hidden">
            <flux:sidebar.profile avatar="https://fluxui.dev/img/demo/user.png" name="Olivia Martin" />
            <flux:menu>
                <flux:menu.radio.group>
                    <flux:menu.radio checked>Olivia Martin</flux:menu.radio>
                    <flux:menu.radio>Truly Delta</flux:menu.radio>
                </flux:menu.radio.group>
                <flux:menu.separator />
                <form action="{{ route('logout', []) }}" method="POST">
                    @csrf
                <flux:menu.item type="submit" icon="arrow-right-start-on-rectangle">Logout</flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:sidebar>
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
        <flux:spacer />
        <flux:dropdown position="top" align="start">
            <flux:profile avatar="/img/demo/user.png" />
            <flux:menu>
                <flux:menu.radio.group>
                    <flux:menu.radio checked>Olivia Martin</flux:menu.radio>
                    <flux:menu.radio>Truly Delta</flux:menu.radio>
                </flux:menu.radio.group>
                <flux:menu.separator />
                <form action="{{ route('logout', []) }}" method="POST">
                    @csrf
                    <flux:menu.item type="submit" icon="arrow-right-start-on-rectangle">Logout</flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    @livewire("alert-live")

        {{ $slot }}

        

        @fluxScripts
        @persist('toast')
            <flux:toast />
        @endpersist

        @stack("alert2")
    </body>
</html>
