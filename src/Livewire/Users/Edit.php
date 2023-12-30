<?php

namespace Pieldefoca\Lux\Livewire\Users;

use Pieldefoca\Lux\Livewire\LuxComponent;
use Pieldefoca\Lux\Livewire\Forms\UserForm;
use App\Models\User;

class Edit extends LuxComponent
{
    public User $user;

    public UserForm $form;

    public function mount()
    {
        $this->form->setUser($this->user);
    }

    public function updatedFormUsername()
    {
        $this->form->slugifyUsername();
    }

    public function save()
    {
        $updated = $this->form->update();

        if($updated) $this->notifySuccess(message: '🤙🏽 Has actualizado el usuario correctamente');
    }

    public function render()
    {
        return view('lux::livewire.users.edit');
    }
}
