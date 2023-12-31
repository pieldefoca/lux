<x-lux::modal>
    <x-lux::modal.panel>
        <x-lux::modal.title>{{ trans('lux::lux.change-password') }}</x-lux::modal.title>
        <form wire:submit="save" class="space-y-6">
            @unlessrole('superadmin')
                <x-lux::input.inline-group
                    required
                    label="Contraseña actual"
                    :error="$errors->first('currentPassword')"
                >
                    <x-lux::input.password wire:model="currentPassword" />
                </x-lux::input.group>
            @endunlessrole
    
            <x-lux::input.inline-group
                required
                label="Nueva contraseña"
                :error="$errors->first('password')"
            >
                <x-lux::input.password wire:model="password" />
            </x-lux::input.inline-group>
    
            <x-lux::input.inline-group
                required
                label="Confirma la nueva contraseña"
                :error="$errors->first('password_confirmation')"
            >
                <x-lux::input.password wire:model="password_confirmation" />
            </x-lux::input.inline-group>

            <x-lux::modal.footer>
                <div class="flex items-center justify-end space-x-8 w-full">
                    <x-lux::modal.button.cancel />
                    <x-lux::modal.button.save />
                </div>
            </x-lux::modal.footer>
        </form>
    </x-lux::modal.panel>
</x-lux::modal>
