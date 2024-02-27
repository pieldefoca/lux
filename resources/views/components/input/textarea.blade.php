@props([
    'translatable' => false,
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
    class="relative border border-gray-300 rounded-md group-[.has-error]:border-red-400"
>
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
    
                this.$watch('value', value => { 
                    this.sync() 
                })
    
                this.$watch('$store.lux.locale', (locale) => {
                    field = this.translatable ? `${this.field}.${locale}` : this.field
                    this.value = $wire.$get(field) 
                })

                this.$nextTick(() => {
                    if(this.$refs.inlineLeadingAddon) {
                        width = this.$refs.inlineLeadingAddon.getBoundingClientRect().width
                        this.$refs.input.style.paddingLeft = `calc(${width}px + 1.8rem)`
                    }
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
        <textarea
            x-ref="input"
            {{ $xModel }} 
            {{ $attributes->whereStartsWith(['id', 'rows']) }} 
            type="text" 
            :class="{'!bg-green-50': level === 'success', '!bg-orange-100': level === 'warning', '!bg-red-100': level === 'danger'}"
            @class([
                'w-full py-1.5 bg-transparent border-none rounded-md group-[.has-error]:bg-red-50 group-[.has-error]:text-red-600',
                'pr-12' => $characterLevels !== null,
             ])
        ></textarea>

        <div x-show="hasCharacterCount && characterCount > 0" class="absolute right-3 top-1/2 -translate-y-1/2 opacity-50">
            <p x-text="characterCount"></p>
        </div>
    </div>
</div>

