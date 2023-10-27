<?php

namespace Pieldefoca\Lux\Livewire;

use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Pieldefoca\Lux\Livewire\LuxModal;

class PasswordModal extends LuxModal
{
    #[Rule('required', message: 'Escribe la contraseña actual')]
    #[Rule('current_password', message: 'La contraseña no es correcta')]
    public $currentPassword;

    #[Rule('required', message: 'Escribe la nueva contraseña')]
    #[Rule('confirmed', message: 'Las contraseñas no coinciden')]
    public $password;

    #[Rule('required', message: 'Confirma la nueva contraseña')]
    public $password_confirmation;

    #[On('change-password')]
    public function changePassword(): void
    {
        $this->currentPassword = null;
        $this->password = null;
        $this->password_confirmation = null;

        $this->show();
    }

    public function save()
    {
        $this->validate();

        auth()->user()->update(['password' => Hash::make($this->password)]);

        $this->notifySuccess('🤙🏾 Has cambiado la contraseña correctamente');

        $this->hide();
    }

    public function render()
    {
        return view('lux::livewire.password-modal');
    }
}
