import * as draggable from './draggable.js'
import Swal from 'sweetalert2'

window.Swal = Swal

document.addEventListener('alpine:init', () => {
    Alpine.data('sortable', (method = 'reorder') => ({
        sorting: false,
        init() {
            window.addEventListener('started-sorting', () => {
                this.disableSorting()
            })
        },
        toggleSorting() {
            if(this.sorting) {
                this.disableSorting()
            } else {
                this.enableSorting()
            }
        },
        enableSorting() {
            this.$dispatch('started-sorting')
            const ul = this.$el.closest('ul')
            ul.setAttribute('drag-root', method)
            ul.querySelectorAll(':scope > li:not(:first-child)').forEach((li, index) => {
                const id = li.getAttribute('drag-id') ?? index
                li.setAttribute('draggable', true)
                li.setAttribute('drag-item', id)
            })
            this.$dispatch('refresh-drag')
            this.sorting = true
        },
        disableSorting() {
            this.sorting = false
            const ul = this.$el.closest('ul')
            ul.removeAttribute('drag-root')
            ul.querySelectorAll(':scope > li:not(:first-child)').forEach((li) => {
                li.removeAttribute('draggable')
                li.removeAttribute('drag-item')
            })
        }
    }))

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

    Alpine.data('adminPage', ($wire) => ({
        init() {
            Alpine.store('lux', {
                locale: $wire.$entangle('locale').live,
                selectLocale(locale) { this.locale = locale }
            })
        }
    }))
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
