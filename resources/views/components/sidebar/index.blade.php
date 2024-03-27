<ul class="space-y-2">
    @foreach(config('lux.sidebar') as $item)
        @if(! $item->shouldShow()) @continue @endif

        @if($item->isGroup())
            <x-lux::sidebar.group 
                label="{{ $item->label }}"
                :icon="$item->tablerIcon"
                :active="$item->isActive()"
            >
                @foreach($item->items as $child)
                    <x-lux::sidebar.item
                        :icon="$child->tablerIcon"
                        :link="route($child->routeName)"
                        :active="$child->isActive()"
                    >
                        {{ $child->label }}
                    </x-lux::sidebar.item>
                @endforeach
            </x-lux::sidebar.group>
        @else
            <x-lux::sidebar.item
                :icon="$item->tablerIcon"
                :link="route($item->routeName)"
                :active="$item->isActive()"
            >
                {{ $item->label }}
            </x-lux::sidebar.item>        
        @endif
    @endforeach
</ul>
