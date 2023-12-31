<div>
    <x-slot name="title">{{ trans('lux::lux.users-edit-title') }}</x-slot>
    <x-slot name="subtitle">{{ trans('lux::lux.users-edit-subtitle') }} {{ $user->username }}</x-slot>

    @teleport('#lux-actions')
        <div class="flex items-center space-x-8">
            @if($errors->any())
                <p class="flex items-center space-x-1 text-red-400">
                    <x-lux::tabler-icons.exclamation-circle />
                    <span>{{ trans('lux::lux.fix-errors') }}</span>
                </p>
            @endif

            <x-lux::link :link="route('lux.users.index')">{{ trans('lux::lux.cancel') }}</x-lux::link>
            <x-lux::button.save wire:click="save" />
        </div>
    @endteleport

    <x-lux::card title="Datos del usuario" class="max-w-4xl mx-auto p-6 mt-6">
        <form class="space-y-6">
            <x-lux::input.inline-group label="Avatar">
                <x-lux::input.avatar wire:model="form.avatar" />
            </x-lux::input.inline-group>

            <x-lux::input.inline-group required :label="trans('lux::lux.username')" :error="$errors->first('form.username')">
                <x-lux::input.text wire:model="form.username" />
            </x-lux::input.inline-group>
    
            <x-lux::input.inline-group required :label="trans('lux::lux.name')" :error="$errors->first('form.name')">
                <x-lux::input.text wire:model="form.name" />
            </x-lux::input.inline-group>
    
            <x-lux::input.inline-group required :label="trans('lux::lux.email')" :error="$errors->first('form.email')">
                <x-lux::input.text wire:model="form.email" />
            </x-lux::input.inline-group>
    
            <x-lux::input.inline-group required :label="trans('lux::lux.password')" :error="$errors->first('form.password')">
                <div class="flex flex-col items-end space-y-1 w-full">
                    <x-lux::input.password wire:model="form.password" />
                    <x-lux::button.link x-on:click="$dispatch('change-password', { user: {{ $user->id }} })" class="text-xs">Cambiar contraseña</x-lux::button.link>
                </div>
            </x-lux::input.inline-group>
        </form>
    </x-lux::card>

    <livewire:password-modal />
</div>