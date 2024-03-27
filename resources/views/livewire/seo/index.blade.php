<div>
    <x-lux::title-bar :title="trans('lux::lux.seo_index_title')" :subtitle="trans('lux::lux.seo_index_subtitle')" />

    <div 
        x-data="{
            selected: 'analytics',
            goTo(section) { this.selected = section }
        }" 
        class="flex items-start gap-8 mt-12"
    >
        <div class="basis-1/4 bg-white shadow rounded-md p-3">
            <ul class="space-y-1.5">
                <li x-bind:class="{'bg-black text-white': selected === 'analytics'}" class="flex items-center justify-between pr-3 rounded-md hover:bg-black hover:text-white">
                    <button x-on:click="goTo('analytics')" class="flex items-center space-x-2 w-full px-3 py-2" type="button">
                        <x-lux::tabler-icons.brand-google-analytics class="w-4 h-4" />
                        <span>{{ trans('lux::lux.analytics') }}</span>
                    </button>

                    <div>
                        @if($this->analyticsStatus)
                            <x-lux::tabler-icons.circle-check class="text-green-400" />
                        @else
                            <x-lux::tabler-icons.circle-x class="text-red-400" />
                        @endif
                    </div>
                </li>
                <li x-bind:class="{'bg-black text-white': selected === 'robots'}" class="flex items-center justify-between pr-3 rounded-md hover:bg-black hover:text-white">
                    <button x-on:click="goTo('robots')" class="flex items-center space-x-2 w-full px-3 py-2" type="button">
                        <x-lux::tabler-icons.robot class="w-4 h-4" />
                        <span>{{ trans('lux::lux.robots') }}</span>
                    </button>

                    <div>
                        @if($this->robotsStatus)
                            <x-lux::tabler-icons.circle-check class="text-green-400" />
                        @else
                            <x-lux::tabler-icons.circle-x class="text-red-400" />
                        @endif
                    </div>
                </li>
                <li x-bind:class="{'bg-black text-white': selected === 'sitemap'}" class="flex items-center justify-between pr-3 rounded-md hover:bg-black hover:text-white">
                    <button x-on:click="goTo('sitemap')" class="flex items-center space-x-2 w-full px-3 py-2" type="button">
                        <x-lux::tabler-icons.sitemap class="w-4 h-4" />
                        <span>{{ trans('lux::lux.sitemap') }}</span>
                    </button>

                    <div>
                        @if($this->sitemapStatus)
                            <x-lux::tabler-icons.circle-check class="text-green-400" />
                        @else
                            <x-lux::tabler-icons.circle-x class="text-red-400" />
                        @endif
                    </div>
                </li>
            </ul>
        </div>

        <div x-show="selected === 'analytics'" class="basis-3/4 flex-grow bg-white shadow rounded-md p-6">
            <h2 class="text-2xl font-bold">Google Analytics</h2>

            <div class="max-w-full mt-4 border border-gray-300 rounded-md overflow-hidden">
                <textarea wire:model="analyticsContent" id="analyticsEditor"></textarea>
            </div>
        </div>

        <div x-show="selected === 'robots'" class="basis-3/4 flex-grow bg-white shadow rounded-md p-6">
            <h2 class="text-2xl font-bold">Robots Txt</h2>

            <div class="max-w-full mt-4 border border-gray-300 rounded-md overflow-hidden">
                <textarea wire:model="robotsContent" id="robotsEditor"></textarea>
            </div>
        </div>

        <div x-show="selected === 'sitemap'" class="basis-3/4 flex-grow bg-white shadow rounded-md p-6">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold">Sitemap</h2>

                <x-lux::button wire:click="generateSitemap">{{ trans('lux::lux.generate_sitemap') }}</x-lux::button>
            </div>

            <div class="max-w-full mt-4 border border-gray-300 rounded-md overflow-hidden">
                <textarea wire:model="sitemapContent" id="sitemapEditor"></textarea>
            </div>
        </div>
    </div>

    <x-lux::action-bar>
        <div class="flex items-center space-x-8">
            <x-lux::button x-on:click="$dispatch('save-seo')" icon="device-floppy">{{ trans('lux::lux.save') }}</x-lux::button>
            <a href="javascript:void(0)" onclick="location.reload()">
                <x-lux::button.link>{{ trans('lux::lux.cancel') }}</x-lux::button.link>
            </a>
        </div>
    </x-lux::action-bar>
</div>

@assets
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.1/codemirror.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.1/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.1/mode/javascript/javascript.min.js"></script>
<style>   
.CodeMirror {
    height: auto;
}
.CodeMirror-scroll {
    min-height: 300px;
}
</style>
@endassets

@script
<script>
    let analyticsEditor
    let robotsEditor
    let sitemapEditor

    const editorOptions = {
        mode: "javascript",
        lineNumbers: true,
        autoRefresh: true,
        lineWrapping: true,
    }

    window.addEventListener('load', () => {
        analyticsEditor = CodeMirror.fromTextArea(document.getElementById('analyticsEditor'), editorOptions)
        robotsEditor = CodeMirror.fromTextArea(document.getElementById('robotsEditor'), editorOptions)
        sitemapEditor = CodeMirror.fromTextArea(document.getElementById('sitemapEditor'), editorOptions)

        analyticsEditor.on('change', (editor) => { $wire.analyticsContent = editor.getValue() })
        analyticsEditor.on('focus', () => analyticsEditor.refresh())
        robotsEditor.on('change', (editor) => { $wire.robotsContent = editor.getValue() })
        robotsEditor.on('focus', () => robotsEditor.refresh())
        sitemapEditor.on('change', (editor) => { $wire.sitemapContent = editor.getValue() })
    
        setTimeout(() => { analyticsEditor.refresh() }, 500)
        setTimeout(() => { robotsEditor.refresh() }, 1000)
        setTimeout(() => { sitemapEditor.refresh() }, 1000)
    })
</script>
@endscript