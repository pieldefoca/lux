<?php

namespace Pieldefoca\Lux\Livewire\Users;

use Livewire\Attributes\On;
use Pieldefoca\Lux\Livewire\LuxComponent;
use Pieldefoca\Lux\Livewire\Forms\UserForm;

class Create extends LuxComponent
{
    public UserForm $form;

    public function mount()
    {
        $this->form->setUser();
    }

    public function updatedFormUsername()
    {
        $this->form->slugifyUsername();
    }

    public function save()
    {
        $created = $this->form->create();

        if($created) $this->notifySuccess(message: '👍🏽 Has creado el usuario correctamente');
    }

    public function render()
    {
        return view('lux::livewire.users.create');
    }
}
