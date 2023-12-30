<div>
    <x-slot name="title">{{ trans('lux::lux.users-index-title') }}</x-slot>
    <x-slot name="subtitle">{{ trans('lux::lux.users-index-subtitle') }}</x-slot>

    <x-slot name="actions">
        <a href="{{ route('lux.users.create') }}">
            <x-lux::button icon="square-rounded-plus">{{ trans('lux::lux.new-user') }}</x-lux::button>
        </a>
    </x-slot>

    <div>
        <livewire:users.table />
    </div>
</div>