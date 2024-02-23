@props([
    'translatable' => false,
    'leadingIcon' => null,
    'trailingIcon' => null,
    'leadingAddon' => null,
    'trailingAddon' => null,
    'inlineLeadingAddon' => null,
    'inlineTrailingAddon' => null,
    'characterLevels' => null,
])

@php
$wireModelData = $attributes->whereStartsWith('wire:model')->getAttributes();
$wireModelName = array_key_first($wireModelData);
$wireModelValue = $wireModelData[$wireModelName];
$modifiers = array_slice(explode('.', $wireModelName), 1);
$live = in_array('live', $modifiers);
$blur = in_array('blur', $modifiers);
$debounce = in_array('debounce', $modifiers);
$debounceMs = null;
if($debounce) {
    $index = array_search('debounce', $modifiers);
    try {
        $possibleDebounceMs = $modifiers[$index + 1];
        if(str($possibleDebounceMs)->contains('ms')) {
            $debounceMs = $possibleDebounceMs;
        }
    } catch(\Exception $e) {}
}
$xModel = str('x-model')
    ->when(!$live, fn($str) => $str->append('.lazy'))
    ->when($live || $debounce, fn($str) => $str->append('.debounce'))
    ->when(!is_null($debounceMs), fn($str) => $str->append(".{$debounceMs}"))
    ->append('=value');
@endphp

<div 
    x-data="{
        level: null,
    }"
    :class="{'border-green-200 text-green-800': level === 'success', 'border-orange-300 text-orange-600': level === 'warning', 'border-red-300 text-red-700': level === 'danger'}"
    class="relative border border-gray-300 rounded-md group-[.has-error]:border-red-400 has-[input:focus]:border-transparent has-[input:focus]:ring-1 has-[input:focus]:ring-gray-800"
>
    @if($leadingIcon)
        <div class="absolute left-2 top-1/2 -translate-y-1/2 pointer-events-none opacity-60">
            <x-dynamic-component component="lux::tabler-icons.{{ $leadingIcon}}" class="w-5 h-5" />
        </div>
    @endif

    @if($trailingIcon)
        <div class="absolute right-2 top-1/2 -translate-y-1/2 pointer-events-none opacity-60">
            <x-dynamic-component component="lux::tabler-icons.{{ $trailingIcon}}" class="w-5 h-5" />
        </div>
    @endif

    <div
        x-data="{
            value: null,
            field: @js($wireModelValue),
            live: @js($live),
            blur: @js($blur),
            translatable: @js($translatable),
            hasCharacterCount: @js($characterLevels) !== null,
            characterLevels: @js($characterLevels),
            characterCount: 0,
            init() {
                field = this.translatable ? `${this.field}.${$store.lux.locale}` : this.field
                this.value = $wire.$get(field)

                $wire.$watch(field, (value) => {
                    this.value = value
                })
    
                this.$watch('value', value => { 
                    this.sync() 
                })
    
                this.$watch('$store.lux.locale', (locale) => {
                    field = this.translatable ? `${this.field}.${locale}` : this.field
                    this.value = $wire.$get(field) 
                })

                if(this.hasCharacterCount) {
                    this.$nextTick(() => {
                        this.refreshCharacterCount()
                    })
                    this.$refs.input.addEventListener('input', e => {
                        this.refreshCharacterCount()
                    })
                }
            },
            sync() {
                field = this.translatable ? `${this.field}.${$store.lux.locale}` : this.field
                $wire.$set(field, this.value, this.live || this.blur)
            },
            refreshCharacterCount() {
                value = this.$refs.input.value
                this.characterCount = value.length
                warningLevel = this.characterLevels[0]
                dangerLevel = this.characterLevels[1]
                if(value.length >= dangerLevel) {
                    this.level = 'danger'
                } else if(value.length >= warningLevel) {
                    this.level = 'warning'
                } else if(value.length > 0) {
                    this.level = 'success'
                } else {
                    this.level = null
                }
            }
        }"
        class="flex items-center w-full"
    >
        @if($leadingAddon)
            <div class="px-2 text-sm opacity-50">
                {{ $leadingAddon }}
            </div>
        @endif

        @if($inlineLeadingAddon)
            <label for="{{ $attributes->whereStartsWith('id')->get('id') }}" x-ref="inlineLeadingAddon" class="pl-2 opacity-60 text-sm">
                {{ $inlineLeadingAddon }}
            </label>
        @endif

        <input
            x-ref="input"
            {{ $xModel }} 
            {{ $attributes->whereStartsWith('id') }} 
            type="text" 
            :class="{'!bg-green-50': level === 'success', '!bg-orange-100': level === 'warning', '!bg-red-100': level === 'danger'}"
            @class([
                'w-full py-1.5 bg-transparent ring-0 focus:ring-0 group-[.has-error]:bg-red-50 group-[.has-error]:text-red-600',
                'pl-9' => $leadingIcon,
                'pr-9' => $trailingIcon,
                'rounded-r-md pl-2 border-l border-y-0 border-r-0 border-gray-300' => $leadingAddon && !$trailingAddon,
                'rounded-l-md border-r border-y-0 border-l-0 border-gray-300' => $trailingAddon && !$leadingAddon,
                'border-x border-y-0 border-gray-300' => $leadingAddon && $trailingAddon,
                'border-none rounded-md' => !$leadingAddon && !$trailingAddon,
            ])
        />

        <div x-show="hasCharacterCount && characterCount > 0" class="absolute right-3 top-1/2 -translate-y-1/2 opacity-50">
            <p x-text="characterCount"></p>
        </div>

        @if($trailingAddon)
            <div class="px-2 text-sm opacity-50">
                {{ $trailingAddon }}
            </div>
        @endif

        @if($inlineTrailingAddon)
            <div x-ref="inlineTrailingAddon" class="absolute left-2 top-1/2 -translate-y-1/2 pointer-events-none opacity-60 text-sm">
                {{ $inlineTrailingAddon }}
            </div>
        @endif
    </div>
</div>

