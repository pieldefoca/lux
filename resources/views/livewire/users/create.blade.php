<div>
    <x-slot name="title">{{ trans('lux::lux.users-create-title') }}</x-slot>
    <x-slot name="subtitle">{{ trans('lux::lux.users-create-subtitle') }}</x-slot>

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

    <x-lux::card class="max-w-4xl mx-auto p-8 mt-12">
        <form class="space-y-6">
            <x-lux::input.inline-group required :label="trans('lux::lux.username')" :error="$errors->first('form.username')">
                <x-lux::input.text wire:model.blur="form.username" />
            </x-lux::input.inline-group>
    
            <x-lux::input.inline-group required :label="trans('lux::lux.name')" :error="$errors->first('form.name')">
                <x-lux::input.text wire:model="form.name" />
            </x-lux::input.inline-group>
    
            <x-lux::input.inline-group required :label="trans('lux::lux.email')" :error="$errors->first('form.email')">
                <x-lux::input.text wire:model="form.email" />
            </x-lux::input.inline-group>
    
            <x-lux::input.inline-group required :label="trans('lux::lux.password')" :error="$errors->first('form.password')">
                <x-lux::input.password wire:model="form.password" />
            </x-lux::input.inline-group>
    
            <x-lux::input.inline-group required :label="trans('lux::lux.password-confirmation')">
                <x-lux::input.password wire:model="form.password_confirmation" />
            </x-lux::input.inline-group>
        </form>
    </x-lux::card>
</div>