<?php

namespace Pieldefoca\Lux\Livewire;

use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\WithFileUploads;
use Livewire\Attributes\Computed;
use Pieldefoca\Lux\Livewire\LuxComponent;
use Illuminate\Validation\Rule as RuleValidation;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class Profile extends LuxComponent
{
	use WithFileUploads;

	public $user;

	public $avatar;

	public $username;

	public $name;

	public $email;

	public $password;

	public function mount()
	{
		$this->user = auth()->user();
		$this->avatar = $this->user->avatarUrl;
		$this->username = $this->user->username;
		$this->name = $this->user->name;
		$this->email = $this->user->email;
		$this->password = 'La curiosidad mató al gato 😉';
	}

	public function updatedUsername($value)
	{
		$this->username = str($value)->slug()->replace('-', '_')->toString();
	}

	#[Computed]
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

	#[On('save-profile')]
	public function save()
	{
		$validated = $this->validate();

		if(is_null($this->avatar)) {
			$this->user->removeAvatar();
		} else {
			if($this->avatar instanceof TemporaryUploadedFile) {
				$name = $this->avatar->store('/', 'avatars');
	
				$validated = array_merge($validated, ['avatar' => $name]);

				$this->user->removeAvatar();
			}
		}

		$this->user->update($validated);

		$this->notifySuccess('🤙🏾 Has actualizado tu perfil correctamente');
	}

	public function rules()
	{
		return [
			'avatar' => ['nullable', 'exclude'],
			'username' => ['required'],
			'name' => ['required', 'string'],
			'email' => ['required', RuleValidation::unique('users', 'email')->ignoreModel($this->user)],
		];
	}

	public function messages()
	{
		return [
			'name.required' => 'Escribe un nombre',
			'email.required' => 'Escribe un email',
			'email.unique' => 'Ese email ya está en uso',
		];
	}

	public function render()
	{
		return view('lux::livewire.profile');
	}
}
