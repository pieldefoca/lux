<div>
    <x-slot name="title">{{ trans('lux::lux.contact-title') }}</x-slot>
    <x-slot name="subtitle">{{ trans('lux::lux.contact-subtitle') }}</x-slot>

    <x-slot name="actions">
        <x-lux::button x-on:click="$dispatch('save-contact')">{{ trans('lux::lux.guardar') }}</x-lux::button>
    </x-slot>

    <x-lux::locale-selector />

    <div class="grid grid-cols-1 lg:grid-cols-2 items-start gap-8">
        <x-lux::card title="{{ trans('lux::lux.contact-data') }}" class="p-6 space-y-6">
            @foreach($phoneFields as $key => $label)
                <x-lux::input.inline-group
                    :label="$label"
                    :error="$errors->first('phones.{{$key}}')"
                >
                    <x-lux::input.text wire:model="phones.{{$key}}" />
                </x-lux::input.inline-group>
            @endforeach

            @foreach($emailFields as $key => $label)
                <x-lux::input.inline-group
                    :label="$label"
                    :error="$errors->first('emails.{{$key}}')"
                >
                    <x-lux::input.text wire:model="emails.{{$key}}" />
                </x-lux::input.inline-group>
            @endforeach

            @foreach($socialMediaFields as $key => $label)
                <x-lux::input.inline-group
                    :label="$label"
                    :error="$errors->first('socialMedia.{{$key}}')"
                >
                    <x-lux::input.text wire:model="socialMedia.{{$key}}" />
                </x-lux::input.inline-group>
            @endforeach

            @foreach($timetableFields as $key => $label)
                <x-lux::input.inline-group
                    translatable
                    :label="$label"
                    :error="$errors->first('timetables.{{$key}}')"
                >
                    <x-lux::input.textarea rows="4" wire:model="timetables.{{$key}}" />
                </x-lux::input.inline-group>
            @endforeach
        </x-lux::card>

        <x-lux::card title="{{ trans('lux::lux.locations') }}" class="p-6 space-y-6">
                @foreach($locationFields as $key => $label)
                    <div class="p-3 space-y-6 border border-stone-200 rounded-md shadow">
                        <p>{{ $label }}</p>

                        <x-lux::input.inline-group
                            translatable
                            label="Nombre"
                            :error="$errors->first('locations.{{$key}}.name')"
                        >
                            <x-lux::input.text wire:model="locations.{{$key}}.name" />
                        </x-lux::input.inline-group>

                        <x-lux::input.inline-group
                            translatable
                            label="Dirección"
                            :error="$errors->first('locations.{{$key}}.address')"
                        >
                            <x-lux::input.textarea rows="4" wire:model="locations.{{$key}}.address" />
                        </x-lux::input.inline-group>

                        <x-lux::input.inline-group
                            label="URL Google Maps"
                            :error="$errors->first('locations.{{$key}}.google_maps_url')"
                        >
                            <x-lux::input.text wire:model="locations.{{$key}}.google_maps_url" />
                        </x-lux::input.inline-group>
                    </div>
                @endforeach
        </x-lux::card>
    </div>
</div>