@props([
    'translatable' => false,
    'height' => 300,
    'toolbar' => 'h1 h2 h3 bold italic underline align | bullist numlist | image media link table | fullscreen',
])

@php
$wireModelData = $attributes->whereStartsWith('wire:model')->getAttributes();
$wireModelName = array_key_first($wireModelData);
$wireModelValue = $wireModelData[$wireModelName];
$id = str($wireModelValue)->replace('.', '_')->toString();
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

<div class="w-full">
    <script src="/vendor/lux/tinymce/tinymce.min.js" referrerpolicy="origin"></script>

    {{-- @php
        $wireModel = $attributes->whereStartsWith('wire:model')->getAttributes();
        $model = '';
        foreach($wireModel as $attribute => $value) {
            $wireModel = $attribute;
            $model = $value;
        }
        $id = str($model)->replace('.', '_')->toString();

        $locales = $translatable
            ? Pieldefoca\Lux\Models\Locale::all()
            : [Pieldefoca\Lux\Models\Locale::default()];
    @endphp --}}

    <div
        x-data="{
            locale: @entangle('locale'),
            id: '',
            value: null,
            field: @js($wireModelValue),
            live: @js($live),
            blur: @js($blur),
            translatable: @js($translatable),
            {{-- model: '{{$model}}', --}}
            init() {
                field = this.translatable ? `${this.field}.${$store.lux.locale}` : this.field
                this.value = $wire.$get(field)

                $wire.$watch(field, (value) => {
                    tinymce.get(this.id).setContent(value)
                })

                this.$watch('value', value => {
                    this.sync()
                })

                this.$watch('$store.lux.locale', (locale) => {
                    field = this.translatable ? `${this.field}.${locale}` : this.field
                    this.value = $wire.$get(field)
                    tinymce.get(this.id).setContent(this.value)
                })

                this.$nextTick(() => {
                    if(this.$refs.inlineLeadingAddon) {
                        width = this.$refs.inlineLeadingAddon.getBoundingClientRect().width
                        this.$refs.input.style.paddingLeft = `calc(${width}px + 1.8rem)`
                    }
                })
                this.id = '{{$id}}'
                if(this.translatable) {
                    this.model += `.${this.locale}`
                }

                tinymce.init({
                    selector: `#${this.id}`,
                    language: 'es',
                    min_height: @js(is_string($height) ? intval($height) : $height),
                    plugins: 'link image table lists autoresize media fullscreen',
                    menubar: false,
                    toolbar: @js($toolbar),
                    init_instance_callback: (editor) => {
                        editor.setContent(this.value)

                        editor.on('blur', (e) => {
                            this.value = editor.getContent()
                        });
                    },
                    images_upload_url: '/admin/tinymce/upload',
                    automatic_uploads: true,
                    images_upload_credentials: true,
                    images_upload_handler: (blobInfo, progress) => new Promise((resolve, reject) => {
                        const xhr = new XMLHttpRequest();
                        xhr.withCredentials = false;
                        xhr.open('POST', '/admin/tinymce/upload');

                        xhr.upload.onprogress = (e) => {
                            progress(e.loaded / e.total * 100);
                        };

                        xhr.onload = () => {
                            if (xhr.status === 403) {
                                reject({ message: 'HTTP Error: ' + xhr.status, remove: true });
                                return;
                            }

                            if (xhr.status < 200 || xhr.status >= 300) {
                                reject('HTTP Error: ' + xhr.status);
                                return;
                            }

                            const json = JSON.parse(xhr.responseText);

                            if (!json || typeof json.location != 'string') {
                                reject('Invalid JSON: ' + xhr.responseText);
                                return;
                            }

                            resolve(json.location);
                        };

                        xhr.onerror = () => {
                            reject('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
                        };

                        const formData = new FormData();
                        formData.append('file', blobInfo.blob(), blobInfo.filename());

                        xhr.send(formData);
                    }),
                })
            },
            sync() {
                field = this.translatable ? `${this.field}.${$store.lux.locale}` : this.field
                $wire.$set(field, this.value, this.live || this.blur)
            },
        }"
        @class([
            'w-full',
        ])
        wire:ignore
    >
        <textarea x-ref="editor" id="{{$id}}"></textarea>
    </div>
</div>
