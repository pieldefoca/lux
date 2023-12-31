<?php

namespace Pieldefoca\Lux\Livewire\Forms;

use Livewire\Form;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Hash;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class UserForm extends Form
{
    public User $user;

    public $avatar;

    public $username;

    public $name;

    public $email;

    public $password;

    public $password_confirmation;

    public function setUser($user = null)
    {
        if(is_null($user)) {
            $this->user = User::make([]);
        } else {
            $this->user = $user;
            $this->avatar = $user->avatarUrl;
            $this->username = $user->username;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->password = 'La curiosidad mató al gato 🐈';
        }
    }

    public function slugifyUsername()
    {
        $this->username = str($this->username)->slug()->replace('-', '_')->toString();
    }

    public function avatarUrl()
    {
        if(!is_null($this->avatar)) {
			if(is_string($this->avatar)) {
				return $this->avatar;
			} else {
				if($this->avatar->isPreviewable()) {
					return $this->avatar->temporaryUrl();
				}
			}
		}
    }

    public function create()
    {
        $validated = $this->validate();

        $validated['password'] = Hash::make($this->password);

        $validated = $this->saveAvatar($validated);

        return $this->user->create($validated);
    }

    public function update()
    {
        $validated = $this->validate();

        $validated = $this->saveAvatar($validated);

        return $this->user->update($validated);
    }

    protected function saveAvatar(array $validated)
    {
        if(is_null($this->avatar)) {
			$this->user->removeAvatar();
		} else {
			if($this->avatar instanceof TemporaryUploadedFile) {
				$name = $this->avatar->store('/', 'avatars');

                $validated = array_merge($validated, ['avatar' => $name]);

				$this->user->removeAvatar();
			}
		}

        return $validated;
    }

    protected function rules()
    {
        $rules = [
            'username' => ['required', Rule::unique('users', 'username')->ignore($this->user)],
            'name' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'exclude'],
        ];

        if(! $this->user->getKey()) {
            array_unshift($rules['password'], 'confirmed');
        }

        return $rules;
    }

    protected function messages()
    {
        return [
            'username.required' => trans('lux::validation.user.username.required'),
            'username.unique' => trans('lux::validation.user.username.unique'),
            'name.required' => trans('lux::validation.user.name.required'),
            'email.required' => trans('lux::validation.user.email.required'),
            'email.email' => trans('lux::validation.user.email.email'),
            'password.required' => trans('lux::validation.user.password.required'),
            'password.confirmed' => trans('lux::validation.user.password.confirmed'),
        ];
    }
}
