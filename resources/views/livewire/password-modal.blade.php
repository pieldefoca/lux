<x-lux::modal title="Cambiar contraseña">
    <form wire:submit="save" class="space-y-6">
        <x-lux::input.inline-group
            required
            label="Contraseña actual"
            :error="$errors->first('currentPassword')"
        >
            <x-lux::input.password wire:model="currentPassword" />
        </x-lux::input.group>

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
    </form>

    <x-slot name="footer">
        <div class="flex items-center justify-end space-x-8 w-full">
            <x-lux::link wire:click="hide">Cancelar</x-lux::link>
            <x-lux::button type="submit" wire:click="save" icon="device-floppy">Guardar</x-lux::button>
        </div>
    </x-slot>
</x-lux::modal>
