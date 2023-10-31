<?php

namespace Pieldefoca\Lux\Livewire\Pages;

use Livewire\Attributes\Js;
use Pieldefoca\Lux\Models\Page;
use Livewire\Attributes\Computed;
use Pieldefoca\Lux\Livewire\LuxComponent;

class Card extends LuxComponent
{
    public Page $page;

    public function toggleVisibility($confirm = true)
    {
        if($confirm && $this->page->visible) {
            return $this->js(<<<'JS'
                Swal.fire({
                    title: 'Ocultar página',
                    text: 'Al ocultar una página es posible que algunos enlaces dejen de funcionar, ¿seguro que quieres continuar?',
                    icon: 'warning',
                    showCancelButton: true,
                    customClass: {
                        confirmButton: 'px-4 py-2 rounded-lg border-2 border-red-500 bg-red-100 text-red-500 transition-colors duration-300 hover:bg-red-200 hover:border-red-600 hover:text-red-600',
                        cancelButton: 'hover:underline',
                        actions: 'space-x-6',
                    },
                    buttonsStyling: false,
                    confirmButtonText: 'Sí, ocultar',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $wire.call('toggleVisibility', false)
                    }
                })
            JS);
        }

        $this->page->update(['visible' => !$this->page->visible]);
    }

    public function render()
    {
        return view('lux::livewire.pages.card');
    }
}
