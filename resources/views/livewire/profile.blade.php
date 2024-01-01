<div>
	<x-slot name="title">{{ trans('lux::lux.profile-title') }}</x-slot>
	<x-slot name="subtitle">{{ trans('lux::lux.profile-subtitle') }}</x-slot>

    <x-slot name="actions">
        <x-lux::button.save x-on:click="$dispatch('save-profile')" />
    </x-slot>

    <div class="max-w-xl mx-auto space-y-6">
        <x-lux::input.inline-group label="Avatar">
            <x-lux::input.avatar wire:model="avatar" />
        </x-lux::input.inline-group>

        <x-lux::input.inline-group required label="Nombre de usuario">
            <x-lux::input.text wire:model.live.debounce.500ms="username" />
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
