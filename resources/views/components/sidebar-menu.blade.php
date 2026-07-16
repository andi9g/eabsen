@php
    $role = auth()->user()?->akses->akses??"user";
@endphp
@foreach($menus as $menu)
    @if(isset($menu['roles']) && !in_array($role, $menu['roles']))
        @continue
    @endif
    @if(isset($menu['children']))
         @php

            $visibleChildren = collect($menu['children'])
                ->filter(function ($child) use ($role) {

                    return !isset($child['roles'])
                        || in_array($role, $child['roles']);
                });

            $expanded = collect($menu['children'])
                ->contains(function ($child) {

                    return isset($child['route'])
                        && request()->routeIs($child['route']);
                });

        @endphp
        @if($visibleChildren->count())

            <flux:sidebar.group
                expandable
                :expanded="$expanded"
                icon="{{ $menu['icon'] ?? '' }}"
                heading="{{ $menu['label'] }}"
                class="grid"
            >

                <x-sidebar-menu
                    :menus="$visibleChildren"
                />

            </flux:sidebar.group>

        @endif

    @else

        <flux:sidebar.item
            icon="{{ $menu['icon'] ?? '' }}"
            href="{{ route($menu['route']) }}"
            :current="request()->routeIs($menu['route'])"
        >
            {{ $menu['label'] }}
        </flux:sidebar.item>
    @endif

@endforeach