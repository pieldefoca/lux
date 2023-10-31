<div 
    x-data="{
        bold() { this.insert('<b>', '</b>', 3) },
        italic() { this.insert('<i>', '</i>', 3) },
        underline() { this.insert('<u>', '</u>', 3) },
        lineBreak() { this.insert('<br/>') },
        link() { this.insert('{{ '<a href="https://">' }}' , '</a>', 17) },
        insert(tagOpen, tagClose, cursorOffset) {
            input = this.$refs.input
            start = input.selectionStart
            end = input.selectionEnd
            if(start === end) {
                leadingText = input.value.substring(0, start)
                trailingText = input.value.substring(end, input.value.length)
                input.value = leadingText + tagOpen + tagClose + trailingText
            } else {
                leadingText = input.value.substring(0, start)
                selectedText = input.value.substring(start, end)
                trailingText = input.value.substring(end, input.value.length)
                input.value = leadingText + tagOpen + selectedText + tagClose + trailingText
            }
            input.focus()
            input.selectionStart = input.selectionEnd = start + cursorOffset
        }
    }"
    class="w-full border border-stone-300 rounded-md"
>
    <div class="flex items-center space-x-4 px-2 pt-1 border-b border-stone-100">
        <div>
            <button @click="bold" type="button" title="Negrita">
                <x-lux::tabler-icons.bold class="w-4 h-4" />
            </button>
            <button @click="italic" type="button" title="Cursiva">
                <x-lux::tabler-icons.italic class="w-4 h-4" />
            </button>
            <button @click="underline" type="button" title="Subrayado">
                <x-lux::tabler-icons.underline class="w-4 h-4" />
            </button>
        </div>

        <div>
            <button @click="link" type="button" title="Enlace">
                <x-lux::tabler-icons.link class="w-4 h-4" />
            </button>
        </div>

        <div>
            <button @click="lineBreak" type="button" title="Salto de línea">
                <x-lux::tabler-icons.corner-down-left class="w-4 h-4" />
            </button>
        </div>
    </div>

    <textarea x-ref="input" {{ $attributes->wire('model') }} rows="3" class="block w-full p-2 outline-none rounded-md resize-none text-xs focus:ring-1 focus:ring-black"></textarea>
</div>
