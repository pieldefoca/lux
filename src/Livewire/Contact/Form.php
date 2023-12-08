<?php

namespace Pieldefoca\Lux\Livewire\Contact;

use Livewire\Attributes\On;
use Pieldefoca\Lux\Models\Locale;
use Pieldefoca\Lux\Models\Contact;
use Pieldefoca\Lux\Livewire\LuxForm;
use Pieldefoca\Lux\Support\Contact\ContactKeyValue;

class Form extends LuxForm
{
    public Contact $contact;

    public $phoneFields = [];
    public $phones;

    public $emailFields = [];
    public $emails;

    public $socialMediaFields = [];
    public $socialMedia;

    public $locationFields = [];
    public $locations;

    public $timetableFields = [];
    public $timetables;

    public function mount()
    {
        $this->contact = Contact::first() ?? Contact::create([]);

        $this->initPhones();

        $this->initEmails();

        $this->initSocialMedia();

        $this->initLocations();

        $this->initTimetables();
    }

    protected function initPhones()
    {
        foreach(config('lux.contact.phones') as $phone) {
            $key = str($phone)->slug()->toString();
            $this->phoneFields[$key] = $phone;
            $this->phones[$key] = $this->contact->getPhone($key);
        }
    }

    protected function initEmails()
    {
        foreach(config('lux.contact.emails') as $email) {
            $key = str($email)->slug()->toString();
            $this->emailFields[$key] = $email;
            $this->emails[$key] = $this->contact->getEmail($key);
        }
    }

    protected function initSocialMedia()
    {
        foreach(config('lux.contact.socialMedia') as $socialMedia) {
            $key = str($socialMedia)->slug()->toString();
            $this->socialMediaFields[$key] = $socialMedia;
            $this->socialMedia[$key] = $this->contact->getSocialMedia($key);
        }
    }

    protected function initLocations()
    {
        foreach(config('lux.contact.locations') as $location) {
            $key = str($location)->slug()->toString();
            $this->locationFields[$key] = $location;
            $this->locations[$key] = $this->contact->getLocation($key);
            foreach(Locale::all() as $locale) {
                if(empty($this->locations[$key]['name'][$locale->code])) {
                    $this->locations[$key]['name'][$locale->code] = $location;
                }
            }
        }
    }

    protected function initTimetables()
    {
        foreach(config('lux.contact.timetables') as $timetable) {
            $key = str($timetable)->slug()->toString();
            $this->timetableFields[$key] = $timetable;
            $this->timetables[$key] = $this->contact->getTimetable($key);
        }
    }

    #[On('save-contact')]
    public function save()
    {
        $this->contact->savePhones($this->phones);

        $this->contact->saveEmails($this->emails);

        $this->contact->saveSocialMedia($this->socialMedia);

        $this->contact->saveLocations($this->locations);

        $this->contact->saveTimetables($this->timetables);

        $this->notifySuccess('🤙🏾 Has actualizado la información de contacto correctamente');
    }

    public function render()
    {
        return view('lux::livewire.contact.form');
    }
}
