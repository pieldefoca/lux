<?php

namespace Pieldefoca\Lux\Livewire;

use Livewire\Attributes\On;
use Pieldefoca\Lux\Livewire\LuxForm;

class Form extends LuxForm
{
    #[On('save-')]
    public function save()
    {
        //
    }

    public function rules(): array
    {
        return [
            //
        ];
    }

    public function messages(): array
    {
        return [
            //
        ];
    }

    public function render()
    {
        return view('lux::livewire.');
    }
}
