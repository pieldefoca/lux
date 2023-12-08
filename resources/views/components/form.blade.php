<form {{ $attributes }}>
    <x-lux::locale-selector />

    <div>
        {{ $slot  }}
    </div>
</form>