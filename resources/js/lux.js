import Swal from 'sweetalert2'
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm'

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
})

Livewire.start()

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
