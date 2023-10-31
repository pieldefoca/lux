<?php

namespace Pieldefoca\Lux\Livewire;

use Livewire\Component;
use Pieldefoca\Lux\Traits\UsesLocale;

class LuxComponent extends Component
{
    use UsesLocale;

    public function notifySuccess(string $message)
    {
        $this->dispatch('notify-success', message: $message);
    }

    public function confirm($options)
    {
        $this->dispatch('confirm', 
            options: array_merge([
                'title' => 'Eliminar avatar',
                'text' => '¿Seguro que quieres eliminar el avatar?',
                'icon' => 'warning',
                'showCancelButton' => true,
                'customClass' => [
                    'confirmButton' => 'px-4 py-2 rounded-lg border-2 border-red-500 bg-red-100 text-red-500 transition-colors duration-300 hover:bg-red-200 hover:border-red-600 hover:text-red-600',
                    'cancelButton' => 'hover:underline',
                    'actions' => 'space-x-6',
                ],
                'buttonsStyling' => false,
                'confirmButtonText' => 'Eliminar',
                'cancelButtonText' => 'Cancelar',
            ], $options),
            callback: 'toggleVisibility'    
        );
    }
}
