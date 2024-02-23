<x-lux::admin-page>
    <x-lux::title-bar :title="trans('lux::lux.profile-title')" :subtitle="trans('lux::lux.profile-subtitle')" />

    <div class="flex-grow w-1/2 max-w-xl mx-auto mt-8 space-y-6">
        <x-lux::card class="p-6">
            <div class="space-y-6">
                <x-lux::input.group label="Avatar">
                    <x-lux::input.avatar wire:model="avatar" />
                </x-lux::input.group>
        
                <x-lux::input.group required label="Nombre de usuario" :error="$errors->first('username')">
                    <x-lux::input.text wire:model.live.debounce.500ms="username" />
                </x-lux::input.group>
        
                <x-lux::input.group required label="Nombre" :error="$errors->first('name')">
                    <x-lux::input.text wire:model="name" />
                </x-lux::input.group>
        
                <x-lux::input.group required label="Email" :error="$errors->first('email')">
                    <x-lux::input.text wire:model="email" />
                </x-lux::input.group>
        
                <x-lux::input.group label="Contraseña">
                    <div class="flex flex-col items-end space-y-1 w-full">
                        <x-lux::input.password wire:model="password" />
                        <x-lux::button.link x-on:click="$dispatch('change-password')" class="text-xs">Cambiar contraseña</x-lux::button.link>
                    </div>
                </x-lux::input.group>
            </div>
        </x-lux::card>
    </div>

    <x-lux::action-bar without-locale centered>
        <x-lux::link :link="route('lux.profile')">{{ trans('lux::lux.cancel') }}</x-lux::link>
        <x-lux::button.save x-on:click="$dispatch('save-profile')" />    
    </x-lux::action-bar>

    <livewire:password-modal />
</x-lux::admin-page>
