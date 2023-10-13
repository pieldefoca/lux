@aware([
    'translatable' => false,
])

<textarea
    wire:model="{{ $translatable ? $attributes->get('wire:model') . '.' . $this->currentLocaleCode : $attributes->get('wire:model')}}"
    {{
        $attributes->class(['w-full px-2 py-2 rounded-md border border-stone-300 outline-none bg-stone-100 text-sm transition-colors duration-300 focus:bg-white focus:border-stone-500 hover:bg-white hover:border-stone-400'])
    }}
></textarea>
