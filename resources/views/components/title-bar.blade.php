<div class="flex items-center justify-between space-x-6">
    <div class="flex items-center space-x-6">
        <button
            x-data
            type="button"
            x-on:click="toggleSidebar"
            class="relative flex items-center justify-center p-1.5 rounded transition-colors duration-300 bg-stone-200 text-stone-600 hover:bg-stone-800 hover:text-stone-100"
        >
            <x-lux::tabler-icons.layout-sidebar-left-collapse x-show="!sidebarCollapsed" class="w-6 h-6" />
            <x-lux::tabler-icons.layout-sidebar-left-expand x-show="sidebarCollapsed" class="w-6 h-6" />
        </button>
        <div>
            <h1 class="uppercase font-bold tracking-wider text-lg">{{ $title }}</h1>
            <h2 class="text-stone-500">{{ $subtitle }}</h2>
        </div>
    </div>

    <div>
        {{ $slot }}
    </div>
</div>