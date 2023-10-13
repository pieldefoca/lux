<?php

namespace Pieldefoca\Lux\Livewire\Contact;

use Livewire\Attributes\On;
use Pieldefoca\Lux\Livewire\LuxForm;
use Pieldefoca\Lux\Models\Contact;

class Form extends LuxForm
{
    public Contact $contact;
    public $phone_1;
    public $phone_2;
    public $email;
    public $google_maps_url;
    public $opening_hours = [];
    public $address_line_1 = [];
    public $address_line_2 = [];
    public $address_line_3 = [];

    public function mount()
    {
        $this->contact = Contact::general()->first() ?? Contact::make(['type' => 'general']);

        $this->phone_1 = $this->contact->phone_1;
        $this->phone_2 = $this->contact->phone_2;
        $this->email = $this->contact->email;
        $this->google_maps_url = $this->contact->google_maps_url;
        $this->opening_hours = $this->contact->opening_hours;
        $this->opening_hours = $this->contact->getTranslations('opening_hours');
        $this->address_line_1 = $this->contact->getTranslations('address_line_1');
        $this->address_line_2 = $this->contact->getTranslations('address_line_2');
        $this->address_line_3 = $this->contact->getTranslations('address_line_3');
    }

    #[On('save-contact')]
    public function save()
    {
        $validated = $this->validate();

        $contact = Contact::general()->first();

        if(is_null($contact)) {
            Contact::create($validated);
        } else {
            $contact->update($validated);
        }

        $this->notifySuccess('🤙🏾 Has actualizado la información de contacto correctamente');
    }

    public function rules(): array
    {
        return [
            'phone_1' => ['nullable'],
            'phone_2' => ['nullable'],
            'email' => ['nullable', 'email'],
            'google_maps_url' => ['nullable', 'url'],
            'opening_hours' => ['nullable'],
            'address_line_1' => ['nullable'],
            'address_line_2' => ['nullable'],
            'address_line_3' => ['nullable'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.email' => '😵‍💫 Escribe un email válido',
            'google_maps_url.url' => '😵‍💫 Escribe una url válida',
        ];
    }

    public function render()
    {
        return view('lux::livewire.contact.form');
    }
}
