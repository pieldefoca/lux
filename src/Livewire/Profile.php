<?php

namespace Pieldefoca\Lux\Livewire;

use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\WithFileUploads;
use Livewire\Attributes\Computed;
use Pieldefoca\Lux\Livewire\LuxComponent;
use Illuminate\Validation\Rule as RuleValidation;

class Profile extends LuxComponent
{
	use WithFileUploads;

	public $user;

	#[Rule('nullable')]
	public $avatar;

	#[Rule('required', message: 'Escribe un nombre')]
	public $name;

	#[Rule('required', message: 'Escribe un email')]
	public $email;

	public $password;

	public function mount()
	{
		$this->user = auth()->user();
		$this->avatar = $this->user->avatarUrl;
		$this->name = $this->user->name;
		$this->email = $this->user->email;
		$this->password = 'La curiosidad mató al gato 😉';
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
		$this->validate();

		$this->user->update([
			'name' => $this->name,
			'email' => $this->email,
		]);

		if(is_null($this->avatar)) {
			$this->user->getFirstMedia('avatar')->delete();
		} else {
			if(!is_string($this->avatar)) {
				$this->user->addMedia($this->avatar)->toMediaCollection('avatar');
			}
		}

		$this->avatar = $this->user->avatarUrl;

		$this->notifySuccess('🤙🏾 Has actualizado tu perfil correctamente');
	}

	public function rules()
	{
		return [
			'email' => [RuleValidation::unique('users', 'email')->ignoreModel($this->user)],
		];
	}

	public function messages()
	{
		return [
			'email.unique' => 'Ese email ya está en uso',
		];
	}

	public function render()
	{
		return view('lux::livewire.profile');
	}
}
