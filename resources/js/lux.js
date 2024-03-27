import { Editor } from '@tiptap/core'
import StarterKit from '@tiptap/starter-kit'
import Underline from '@tiptap/extension-underline'
import Swal from 'sweetalert2'

window.Swal = Swal
window.Editor = Editor
window.StarterKit = StarterKit

document.addEventListener('alpine:init', () => {
    // Magic: $tooltip
    Alpine.magic('tooltip', el => message => {
        let instance = tippy(el, { content: message, trigger: 'manual' })

        instance.show()

        setTimeout(() => {
            instance.hide()

            setTimeout(() => instance.destroy(), 150)
        }, 2000)
    })

    // Directive: x-tooltip
    Alpine.directive('tooltip', (el, { expression }) => {
        tippy(el, { content: expression })
    })
    
    Alpine.store('lux', {
        hasDirtyState: false,

        setDirtyState(dirty) {
            this.hasDirtyState = dirty
        }
    })

    Alpine.data('tiptap', (model, toolbar, $wire) => {
        let editor

        return {
            content: $wire.$entangle(model),
            updatedAt: Date.now(),
            init() {
                const _this = this
                editor = new window.Editor({
                    element: _this.$refs.editor,
                    extensions: [
                        StarterKit,
                        Underline,
                    ],
                    content: _this.content,
                    editorProps: {
                        attributes: {
                            class: 'p-4 focus-visible:outline-none',
                        },
                    },
                    onUpdate: ({ editor }) => {
                        _this.content = editor.getHTML()
                        _this.updatedAt = Date.now()
                    },
                    onSelectionUpdate({ editor }) { _this.updatedAt = Date.now() }
                })

                this.$watch('content', (content) => {
                    if (content === editor.getHTML()) return
                    
                    editor.commands.setContent(content, false)
                })
            },
            isActive(type, opts = {}) {
                return editor.isActive(type, opts)
            },
            toggleBold() {
                editor.chain().toggleBold().focus().run()
            },
            toggleItalic() {
                editor.chain().toggleItalic().focus().run()
            },
            toggleUnderline() {
                editor.chain().focus().toggleUnderline().run()
            },
            toggleBulletList() {
                editor.chain().toggleBulletList().focus().run()
            },
            toggleOrderedList() {
                editor.chain().toggleOrderedList().focus().run()
            }
        }
    })
})

window.addEventListener('notify-success', e => {
    Swal.fire({
        text: e.detail.message,
        icon: 'success',
        confirmButtonText: 'Ok',
        timer: 5000,
        timerProgressBar: true,
        didClose: () => {
            if(e.detail.redirectUrl) {
                window.location.href = e.detail.redirectUrl
            }
        }
    })
})

window.addEventListener('notify-error', e => {
    Swal.fire({
        text: e.detail.message,
        icon: 'error',
        confirmButtonText: 'Ok',
        timer: 5000,
        timerProgressBar: true,
    })
})
