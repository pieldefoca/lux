<?php

namespace Pieldefoca\Lux\Livewire;

use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Pieldefoca\Lux\Livewire\LuxModal;
use App\Models\User;

class PasswordModal extends LuxModal
{
    public User $user;

    public $currentPassword;

    public $password;

    public $password_confirmation;

    #[On('change-password')]
    public function changePassword(User $user = null): void
    {
        $this->user = is_null($user) ? auth()->user() : $user;
        $this->currentPassword = null;
        $this->password = null;
        $this->password_confirmation = null;

        $this->show();
    }

    public function save()
    {
        $this->validate();

        $this->user->update(['password' => Hash::make($this->password)]);

        $this->notifySuccess('🤙🏾 Has cambiado la contraseña correctamente');

        $this->hide();
    }

    public function rules()
    {
        $rules = [
            'password' => ['required', 'confirmed'],
        ];

        if(! auth()->user()->hasRole('superadmin')) {
            $rules['currentPassword'] = ['required', 'current_password'];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'currentPassword.required' => trans('lux::validation.user.currentPassword.required'),
            'currentPassword.current_password' => trans('lux::validation.user.currentPassword.current_password'),
            'password.required' => trans('lux::validation.user.password.required'),
            'password.confirmed' => trans('lux::validation.user.password.confirmed'),
        ];
    }

    public function render()
    {
        return view('lux::livewire.password-modal');
    }
}
