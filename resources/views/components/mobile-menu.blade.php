
@php
    $role = auth()->user()?->akses->akses??"user";
@endphp
<div
    class="
        fixed bottom-0 left-0 right-0 z-50
        lg:hidden
        bg-white dark:bg-zinc-900
        border-t border-zinc-200 dark:border-zinc-800
    "
>
    <div class="overflow-x-auto scrollbar-none">
        <div class="flex min-w-max justify-center px-2 py-2 gap-2">

            @foreach($menus as $menu)
                @if(isset($menu['roles']) && !in_array($role, $menu['roles']))
                    @continue
                @endif
                
                @if(isset($menu['children']))
                    @php
                        $activeParent = collect($menu['children'])->contains(
                            fn ($child) => request()->routeIs($child['route'])
                        );
                    @endphp
                    
                    <flux:dropdown position="top">

                        <button
                            @class([
                                '
                                    flex flex-col items-center justify-center
                                    min-w-[80px]
                                    max-w-[80px]
                                    h-full
                                    px-3 py-2
                                    text-xs
                                    gap-1
                                    rounded-[20%]
                                    transition
                                ',

                                'text-accent bg-accent/10' => $activeParent,
                                'text-zinc-500 dark:text-zinc-400' => ! $activeParent,
                            ])
                        >
                            <flux:icon
                                :name="$menu['icon']"
                                class="size-5"
                            />

                            <span class="text-[10px]">
                                {{ $menu['label'] }}
                            </span>
                        </button>

                        <flux:menu>

                            @foreach($menu['children'] as $child)

                                @php
                                    $active = request()->routeIs($child['route']);
                                @endphp
                                @if(isset($child['roles']) && !in_array($role, $child['roles']))
                                    @continue
                                @endif

                                <flux:menu.item
                                    href="{{ route($child['route']) }}"
                                     @class([
                                        'text-accent bg-accent/10' => $active,
                                        'text-zinc-500 dark:text-zinc-400' => ! $active,
                                    ])
                                >
                                    {{ $child['label'] }}
                                </flux:menu.item>

                            @endforeach

                        </flux:menu>

                    </flux:dropdown>

                @else
                    @php
                        $active = request()->routeIs($menu['route']);
                    @endphp
                    <a href="{{ route($menu['route']) }}">
                        <button href=""
                                @class([
                                    '
                                        flex flex-col items-center justify-center
                                        min-w-[80px]
                                        max-w-[80px]
                                        h-full
                                        px-3 py-2
                                        text-xs
                                        gap-1
                                        rounded-[20%]
                                        transition
                                    ',
    
                                    'text-accent bg-accent/10' => $active,
                                    'text-zinc-500 dark:text-zinc-400' => ! $active,
                                ])
                            >
                            <flux:icon
                                :name="$menu['icon']"
                                class="size-5"
                            />
    
                            <span class="text-[10px]">
                                {{ $menu['label'] }}
                            </span>
                        </button>

                    </a>

                @endif

            @endforeach

        </div>
    </div>
</div>