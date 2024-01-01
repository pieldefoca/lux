@aware([
    'translatable' => false,
    'height' => 300,
    'toolbar' => 'h1 h2 h3 bold italic underline align | bullist numlist | image media link table | fullscreen',
])

<div class="w-full">
    <script src="/vendor/lux/tinymce/tinymce.min.js" referrerpolicy="origin"></script>

    @php
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
    @endphp

    <div
        x-data="{
            locale: @entangle('currentLocaleCode'),
            id: '',
            translatable: @js($translatable),
            model: '{{$model}}',
            init() {
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
                        editor.setContent($wire.$get(`${this.model}`) || '')

                        editor.on('blur', (e) => {
                            $wire.$set(`${this.model}`, editor.getContent())
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

                $wire.$watch('{{$model}}', () => {
                    tinymce.get(this.id).setContent($wire.$get(`${this.model}`) || '')
                })

                $watch('locale', ($value) => {
                    if(this.translatable) {
                        this.model = `{{$model}}.${this.locale}`
                    }
                    tinymce.get(this.id).setContent($wire.$get(`${this.model}`) || '')
                })
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
