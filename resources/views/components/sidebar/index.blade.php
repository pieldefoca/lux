<ul class="space-y-2">
    @foreach(config('lux.sidebar') as $item)
        @if($item->isGroup())
            <x-lux::sidebar.group 
                label="{{ $item->label }}"
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
    {{-- <x-lux::sidebar.item
        icon="home-2"
        :link="route('lux.dashboard')"
        :active="Route::is('lux.dashboard')"
    >
        Inicio
    </x-lux::sidebar.item>

    <x-lux::sidebar.item
        icon="slideshow"
        :link="route('lux.sliders.index')"
        :active="Route::is('lux.sliders.*')"
    >
        Sliders
    </x-lux::sidebar.item>

    <x-lux::sidebar.group 
        label="Blog"
        :active="Route::is('lux.blog.*')"
    >
        <x-lux::sidebar.item
            icon="category-2"
            :link="route('lux.blog.categories.index')"
            :active="Route::is('lux.blog.categories.*')"
        >
            Categorías
        </x-lux::sidebar.item>

        <x-lux::sidebar.item
            icon="news"
            :link="route('lux.blog.posts.index')"
            :active="Route::is('lux.blog.posts.*')"
        >
            Posts
        </x-lux::sidebar.item>

    </x-lux::sidebar.group>

    <x-lux::sidebar.item
        icon="address-book"
        :link="route('lux.contact.form')"
        :active="Route::is('lux.contact.form')"
    >
        Contacto
    </x-lux::sidebar.item> --}}

    {{-- <x-lux::sidebar.group label="Blog" active="admin.blog.posts.index, admin.blog.posts.create, admin.blog.posts.edit, admin.blog.categories.index">
        <x-lux::sidebar.item icon="category" :link="route('admin.blog.categories.index')" :active="Route::is('admin.blog.categories.index')">
            Categorías
        </x-lux::sidebar.item>
        <x-lux::sidebar.item icon="news" :link="route('admin.blog.posts.index')" :active="Route::is('admin.blog.posts.index', 'admin.blog.posts.create')">
            Posts
        </x-lux::sidebar.item>
    </x-lux::sidebar.group> --}}
</ul>
