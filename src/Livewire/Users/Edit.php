<?php

namespace Pieldefoca\Lux\Livewire\Users;

use App\Models\User;
use Livewire\WithFileUploads;
use Livewire\Attributes\Computed;
use Pieldefoca\Lux\Livewire\LuxComponent;
use Pieldefoca\Lux\Livewire\Forms\UserForm;

class Edit extends LuxComponent
{
    use WithFileUploads;

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

    #[Computed]
	public function avatarUrl()
	{
        return $this->form->avatarUrl();
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
