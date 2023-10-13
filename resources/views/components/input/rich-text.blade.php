@aware([
    'translatable' => false,
])

@php
$wireModel = $attributes->whereStartsWith('wire:model')->getAttributes();
$model = '';
foreach($wireModel as $attribute => $value) {
    $wireModel = $attribute;
    $model = $value;
}

$locales = $translatable
    ? Admin\Models\Locale::all()
    : [Admin\Models\Locale::default()];
@endphp

@foreach($locales as $locale)
    <div
        x-data="{
            locale: @entangle('currentLocaleCode'),
            quill: null,
            init() {
                this.quill = new Quill(this.$refs.quill, {
                    modules: {
                        toolbar: [
                            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

                            ['bold', 'italic', 'underline', 'strike'],        // toggled buttons

                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                            [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript

                            [{ 'align': [] }],

                            ['image']
                        ]
                    },
                    theme: 'snow'
                })

                this.quill.root.addEventListener('blur', () => {
                    $wire.$set(`{{$model}}.${this.locale}`, this.quill.root.innerHTML)
                })

                this.quill.root.addEventListener('focus', () => {
                    this.fillInput()
                })

                this.fillInput()

                $watch('locale', () => {
                    this.fillInput()
                })
            },
            fillInput() {
                const locale = this.locale
                this.quill.root.innerHTML = $wire.$get(`{{$model}}.${this.locale}`)
            },
        }"
        @class([
            'w-full',
            'hidden' => $translatable && $this->currentLocaleCode !== $locale->code,
        ])
        wire:ignore
    >
        <div x-ref="quill"></div>
    </div>
@endforeach

@push('js')
<link href="//cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="//cdn.quilljs.com/1.3.6/quill.min.js"></script>
@endpush
