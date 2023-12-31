<?php

namespace Pieldefoca\Lux\Livewire\Users;

use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Livewire\Attributes\Computed;
use Pieldefoca\Lux\Livewire\LuxComponent;
use Pieldefoca\Lux\Livewire\Forms\UserForm;

class Create extends LuxComponent
{
    use WithFileUploads;

    public UserForm $form;

    public function mount()
    {
        $this->form->setUser();
    }

    public function updatedFormUsername()
    {
        $this->form->slugifyUsername();
    }

    #[Computed]
	public function avatarUrl()
	{
        return $this->form->avatarUrl();
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
