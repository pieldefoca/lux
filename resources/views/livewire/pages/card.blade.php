<div class="flex items-center justify-between p-3 border-2 border-black rounded-md shadow">
    <div class="">
        <div class="flex items-center space-x-4">
            <p>{{ $page->name }}</p>
            <button 
                wire:click="toggleVisibility"    
                @class([
                    'group px-1 py-px border rounded text-xs transition-all duration-300',
                    'border-green-500 bg-green-100 text-green-500 hover:border-stone-500 hover:bg-stone-100 hover:text-stone-500' => $page->visible,
                    'border-stone-500 bg-stone-100 hover:border-green-500 hover:bg-green-100 hover:text-green-500' => !$page->visible,
                ])
            >
                @if($page->visible)
                    <div class="flex items-center space-x-1 group-hover:hidden">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                        </span>
                        <span>Visible</span>
                    </div>
                    <div class="hidden group-hover:flex items-center space-x-1">
                        <x-lux::tabler-icons.eye-off class="w-3 h-3" />
                        <span>Ocultar</span>
                    </div>
                @else
                    <div class="flex items-center space-x-1 group-hover:hidden">
                        <span class="relative flex h-2 w-2">
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-stone-500"></span>
                        </span>
                        <span>Oculta</span>
                    </div>
                    <div class="hidden group-hover:flex items-center space-x-1">
                        <x-lux::tabler-icons.eye class="w-3 h-3 text-green-400" />
                        <span>Mostrar</span>
                    </div>
            @endif
            </button>
        </div>
        <p 
            x-data="{
                edit: false,
                slug: @js($page->slug),
                startEditing() {
                    this.edit = true
                    setTimeout(() => {
                        this.$refs.input.focus()
                        this.$refs.input.select()
                    }, 100)
                }
            }" 
            @click="startEditing"
            @click.outside="edit = false"
            class="flex text-[9px] text-stone-500"
        >
            <span>{{ config('app.url') }}/</span>
            <span x-show="!edit" x-text="slug"></span>
            <input x-ref="input" x-show="edit" type="text" x-model="slug" class="border border-black rounded-sm outline-none" />
        </p>
    </div>

    <div class="flex items-center space-x-4">
        @if($page->is_home_page)
            <span class="border border-sky-200 bg-sky-100 rounded text-[9px] text-sky-500 px-2 py-px">Página de inicio</span>
        @endif
        <a href="{{ route('lux.pages.edit', $page) }}">
            <x-lux::button.icon action="edit"/>
        </a>
    </div>
</div>
