<div>
    <x-slot name="title">{{ trans('lux::lux.contacto-titulo') }}</x-slot>
    <x-slot name="subtitle">{{ trans('lux::lux.contacto-subtitulo') }}</x-slot>

    <x-slot name="actions">
        <x-lux::button x-on:click="$dispatch('save-contact')">{{ trans('lux::lux.guardar') }}</x-lux::button>
    </x-slot>

    <x-lux::form class="max-w-2xl mx-auto space-y-6">
        <x-lux::input.inline-group
            label="Teléfono 1"
            :error="$errors->first('phone_1')"
        >
            <x-lux::input.text wire:model="phone_1" />
        </x-lux::input.inline-group>

        <x-lux::input.inline-group
            label="Teléfono 2"
            :error="$errors->first('phone_2')"
        >
            <x-lux::input.text wire:model="phone_2" />
        </x-lux::input.inline-group>

        <x-lux::input.inline-group
            label="Email"
            :error="$errors->first('email')"
        >
            <x-lux::input.text wire:model="email" />
        </x-lux::input.inline-group>

        <x-lux::input.inline-group
            label="URL Google Maps"
            :error="$errors->first('google_maps_url')"
        >
            <x-lux::input.text wire:model="google_maps_url" />
        </x-lux::input.inline-group>

        <x-lux::input.inline-group
            translatable
            label="Horario"
            :error="$errors->first('opening_hours')"
        >
            <x-lux::input.textarea wire:model="opening_hours" rows="5" />
        </x-lux::input.inline-group>

        <x-lux::input.inline-group
            translatable
            label="Línea dirección 1"
            :error="$errors->first('address_line_1')"
        >
            <x-lux::input.text wire:model="address_line_1" />
        </x-lux::input.inline-group>

        <x-lux::input.inline-group
            translatable
            label="Línea dirección 2"
            :error="$errors->first('address_line_2')"
        >
            <x-lux::input.text wire:model="address_line_2" />
        </x-lux::input.inline-group>

        <x-lux::input.inline-group
            translatable
            label="Línea dirección 3"
            :error="$errors->first('address_line_3')"
        >
            <x-lux::input.text wire:model="address_line_3" />
        </x-lux::input.inline-group>
    </x-lux::form>
</div>