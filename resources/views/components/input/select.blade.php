@props([
    'native' => false,
    'multiple' => false,
    'options',
    'placeholder' => 'Selecciona una opción',
])
@aware(['translatable' => false])

@php
$locales = $translatable
    ? Pieldefoca\Lux\Models\Locale::all()
    : [Pieldefoca\Lux\Models\Locale::default()];
@endphp

@foreach($locales as $locale)

    @if($native)
        <select 
            @if($translatable)
                {{ $attributes->localizedWireModel($locale->code) }}
            @endif
            {{ 
                $attributes->class([
                    'w-full bg-stone-100 border border-stone-300 rounded-md outline-none px-2 py-2.5 text-sm transition-colors duration-300 hover:border-stone-400 hover:white focus:border-stone-500 focus:bg-white',
                    'hidden' => $translatable && $this->currentLocaleCode !== $locale->code,
                ]) 
            }}
        >
            {{ $slot }}
        </select>
    @else
        @php
            $field = $translatable ? $attributes->wire('model').'.'.$locale->code : $attributes->wire('model');
        @endphp
        <div
            x-data="{
                value: @entangle($field),
                open: false,
                selected: null,
                options: @entangle($options),
                multiple: @js($multiple),
                locale: @js($locale),
                placeholder: @js($placeholder),
                init() {
                    console.log('init')
                    this.selected = this.multiple ? [] : {}
                    $nextTick(() => {
                        if(this.multiple) {
                            for(value in this.value) {
                                this.selected.push(this.options.filter((option) => option.value == this.value)[0])
                            }
                        } else {
                            this.selected = this.options.filter((option) => option.value == this.value)[0] ?? {}
                        }
                    })

                    $watch('value', () => {
                        if(this.multiple) {
                            this.selected = this.options.filter((option) => this.value.includes(option.value))
                        } else {
                            this.selected = this.options.filter((option) => option.value == this.value)[0]
                        }
                    })
                },
                select(value) {
                    if(this.multiple) {
                        this.toggle(value)
                    } else {
                        this.value = value
                        this.selected = this.options.filter((option) => option.value === value)[0]
                        this.open = false
                    }
                },
                deselect(value) {
                    index = this.value.indexOf(value)
                    this.value.splice(index, 1)
                },
                toggle(value) {
                    if(this.isSelected(value)) {
                        this.deselect(value)
                    } else {
                        this.value.push(value)
                    }
                },
                isSelected(value) {
                    if(this.multiple) {
                        return this.value.includes(value)
                    }
                    return this.value === value
                },
            }"
            @click.outside="open = false"
            @keyup.escape="open = false"
            @class([
                'relative w-full',
                'hidden' => $translatable && $this->currentLocaleCode !== $locale->code,
            ])
        >
            <button
                @click="open = !open"
                @keyup.up.stop.prevent="open = true"
                @keyup.down.stop.prevent="open = true"
                type="button"
                class="w-full px-2 border border-stone-300 rounded-md text-left"
            >
                <div class="flex items-center justify-between py-2 w-full">
                    @if($multiple)
                        <span class="text-sm">Selecciona las opciones</span>
                    @else
                        <div class="flex items-center space-x-1">
                            <template x-if="'image' in selected">
                                <img :src="selected.image" class="w-5 h-5 rounded-full object-cover" />
                            </template>
                            <template x-if="selected.label">
                                <span x-text="selected.label" class="text-sm"></span>
                            </template>
                            <template x-if="!selected.label">
                                <span x-text="placeholder" class="text-stone-500"></span>
                            </template>
                        </div>
                    @endif
        
                    <span>
                        <x-lux::tabler-icons.chevron-down x-bind:class="{'rotate-180': open}" class="w-4 h-4 text-stone-400 transition-all duration-200 origin-center" />
                    </span>
                </div>

                @if($multiple)
                    <div x-show="selected.length > 0" class="flex space-x-1 border-t border-stone-200 py-2">
                        <template x-for="option in selected">
                            <div class="flex items-center px-1 border border-stone-300 rounded">
                                <template x-if="'image' in option">
                                    <img :src="option.image" class="w-3 h-3 mr-1 rounded-full object-cover" />
                                </template>
            
                                <span x-text="option.label" class="text-xs"></span>
                                <button @click.stop="deselect(option.value)" type="button" class="ml-2">
                                    <x-lux::tabler-icons.trash class="w-3 h-3 transition-all duration-300 hover:scale-125 hover:text-red-400 focus:scale-125 focus:text-red-400" />
                                </button>
                            </div>

                        </template>
                    </div>
                @endif
            </button>

            <ul x-show="open" class="absolute top-full left-0 w-full max-h-72 mt-1 overflow-y-auto rounded-md bg-white shadow z-10">
                <template x-for="option in options">
                    <li @click="select(option.value)" :class="{'border-teal-200': isSelected(option.value)}" class="border-b border-stone-200">
                        <button 
                            type="button" 
                            :class="{
                                'bg-teal-100': isSelected(option.value),
                                'hover:bg-stone-100': !isSelected(option.value),
                            }"
                            class="flex items-center justify-between w-full pl-2 pr-4 py-2"
                        >
                            <div class="flex items-center space-x-1">
                                <template x-if="'image' in option">
                                    <img :src="option.image" class="w-5 h-5 rounded-full object-cover" />
                                </template>
                                <span x-text="option.label" class="text-sm"></span>
                            </div>

                            @if(!$multiple)
                                <span x-show="isSelected(option.value)">
                                    <x-lux::tabler-icons.check class="w-5 h-5 text-teal-400" />
                                </span>
                            @endif

                            @if($multiple)
                                <div>
                                    <div :class="{'bg-teal-300': isSelected(option.value)}" class="w-2 h-2 rounded-full bg-stone-200"></div>
                                </div>
                            @endif
                        </button>
                    </li>
                </template>
            </ul>
        </div>
    @endif

@endforeach
