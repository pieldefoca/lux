@props([
    'characterLevels' => null,
])

<div 
    x-data="{
        level: null,
    }"
    :class="{'border-green-200 text-green-800': level === 'success', 'border-orange-300 text-orange-600': level === 'warning', 'border-red-300 text-red-700': level === 'danger'}"
    class="relative border border-gray-300 rounded-md group-[.has-error]:border-red-400"
>
    <div
        x-data="{
            hasCharacterCount: @js($characterLevels) !== null,
            characterLevels: @js($characterLevels),
            characterCount: 0,
            init() {
                if(this.hasCharacterCount) {
                    this.$nextTick(() => {
                        this.refreshCharacterCount()
                    })
                    this.$refs.input.addEventListener('input', e => {
                        this.refreshCharacterCount()
                    })
                }
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
            {{ $attributes }} 
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

