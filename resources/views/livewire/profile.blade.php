<div>
	<x-slot name="title">{{ trans('lux::lux.mi-perfil') }}</x-slot>
	<x-slot name="subtitle">{{ trans('lux::lux.estas-editando-tu-perfil') }}</x-slot>

    <x-slot name="actions">
        <x-lux::button x-on:click="$dispatch('save-profile')">{{ trans('lux::lux.guardar') }}</x-lux::button>
    </x-slot>

    <div class="max-w-xl mx-auto space-y-6">
        <x-lux::input.inline-group label="Avatar">
            <div>
                @if($this->avatarUrl)
                    <div class="flex flex-col items-center space-y-3">
                        <img src="{{ $this->avatarUrl }}" class="w-32 aspect-square rounded-full border-2 border-black object-cover" />

                        <div class="flex items-center space-x-3">
                            <x-lux::button.link x-on:click="$refs.avatar.click()" class="!text-[10px]">Cambiar imagen</x-lux::button.link>
                            <button 
                                type="button"
                                x-on:click.stop.prevent="
                                    Swal.fire({
                                        title: 'Eliminar avatar',
                                        text: '¿Seguro que quieres eliminar el avatar?',
                                        icon: 'warning',
                                        showCancelButton: true,
                                        customClass: {
                                            confirmButton: 'px-4 py-2 rounded-lg border-2 border-red-500 bg-red-100 text-red-500 transition-colors duration-300 hover:bg-red-200 hover:border-red-600 hover:text-red-600',
                                            cancelButton: 'hover:underline',
                                            actions: 'space-x-6',
                                        },
                                        buttonsStyling: false,
                                        confirmButtonText: 'Eliminar',
                                        cancelButtonText: 'Cancelar',
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            $wire.$set('avatar', null)
                                        }
                                    })
                                "    
                            >
                                <x-lux::tabler-icons.trash class="w-4 h-4 transition-all duration-300 hover:scale-125 hover:text-red-400" />
                            </button>
                        </div>
                    </div>
                @else
                    <div class="flex flex-col items-center">
                        <x-lux::tabler-icons.user-circle class="w-32 h-32 stroke-1 aspect-square" />

                        <x-lux::button.link x-on:click="$refs.avatar.click()" class="!text-[10px]">Elegir imagen</x-lux::button.link>
                    </div>
                @endif

                <input x-ref="avatar" type="file" wire:model="avatar" style="display: none;" />
            </div>
        </x-lux::input.inline-group>

        <x-lux::input.inline-group required label="Nombre">
            <x-lux::input.text wire:model="name" />
        </x-lux::input.inline-group>

        <x-lux::input.inline-group required label="Email">
            <x-lux::input.text wire:model="email" />
        </x-lux::input.inline-group>

        <x-lux::input.inline-group required label="Contraseña">
            <div class="flex flex-col items-end space-y-1 w-full">
                <x-lux::input.password wire:model="password" />
                <x-lux::button.link x-on:click="$dispatch('change-password')" class="text-xs">Cambiar contraseña</x-lux::button.link>
            </div>
        </x-lux::input.inline-group>
    </div>

    <livewire:password-modal />
</div>
