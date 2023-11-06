<div>
    <x-slot name="title">Nuevo post</x-slot>
    <x-slot name="subtitle">Estás creando un post para el blog</x-slot>

    <x-slot name="actions">
        <div class="flex items-center space-x-8">
            <x-lux::link :link="route('lux.blog.posts.index')">Cancelar</x-lux::link>
            <x-lux::button x-on:click="$dispatch('save-post')" icon="device-floppy">Guardar</x-lux::button>
        </div>
    </x-slot>

    <livewire:blog.posts.form />
</div>
