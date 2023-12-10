<?php

namespace Pieldefoca\Lux\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Pieldefoca\Lux\Support\Contact\ContactKeyValue;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
	use HasFactory;
    use HasTranslations;

    protected $table = 'lux_contact';

    protected $casts = [
        'phone_numbers' => 'array',
        'emails' => 'array',
        'social_media' => 'array',
        'locations' => 'array',
        'timetables' => 'array',
    ];

	protected $guarded = [];

    public $translatable = ['opening_hours', 'address_line_1', 'address_line_2', 'address_line_3'];

    public function scopeGeneral(Builder $query): void
    {
        $query->where('type', 'general');
    }

    public function getPhone($key)
    {
        if(is_null($this->phone_numbers)) return null;

        foreach($this->phone_numbers as $phone) {
            if($phone['key'] === $key) {
                return $phone['value'];
            }
        }
    }

    public function savePhones(?array $phones)
    {
        if(is_null($phones)) {
            return $this->update(['phone_numbers' => null]);
        }

        $newPhones = [];

        foreach($phones as $key => $phone) {
            $newPhones[] = ['key' => $key, 'value' => $phone];
        }

        $this->update(['phone_numbers' => $newPhones]);
    }

    public function getEmail($key)
    {
        if(is_null($this->emails)) return null;

        foreach($this->emails as $email) {
            if($email['key'] === $key) {
                return $email['value'];
            }
        }
    }

    public function saveEmails(?array $emails)
    {
        if(is_null($emails)) {
            return $this->update(['emails' => null]);
        }

        $newEmails = [];

        foreach($emails as $key => $email) {
            $newEmails[] = ['key' => $key, 'value' => $email];
        }

        $this->update(['emails' => $newEmails]);
    }

    public function getSocialMedia($key)
    {
        if(is_null($this->social_media)) return null;

        foreach($this->social_media as $socialMedia) {
            if($socialMedia['key'] === $key) {
                return $socialMedia['value'];
            }
        }
    }

    public function saveSocialMedia(?array $socialMedia)
    {
        if(is_null($socialMedia)) {
            return $this->update(['social_media' => null]);
        }

        $newSocialMedia = [];

        foreach($socialMedia as $key => $socialMedia) {
            $newSocialMedia[] = ['key' => $key, 'value' => $socialMedia];
        }

        $this->update(['social_media' => $newSocialMedia]);
    }

    public function getLocation($key)
    {
        if(is_null($this->locations)) {
            foreach(Locale::all() as $locale) {
                $name[$locale->code] = '';
                $address[$locale->code] = '';
            }
            return [
                'name' => $name,
                'address' => $address,
                'image' => '',
                'google_maps_url' => '',
            ];
        }

        foreach($this->locations as $location) {
            if($location['key'] === $key) {
                return [
                    'name' => $location['name'],
                    'address' => $location['address'],
                    'image' => $location['image'],
                    'google_maps_url' => $location['google_maps_url'],
                ];
            }
        }
    }

    public function saveLocations(?array $locations)
    {
        if(is_null($locations)) {
            return $this->update(['locations' => null]);
        }

        $newLocations = [];

        foreach($locations as $key => $locationData) {
            $newLocations[] = [
                'key' => $key, 
                'name' => $locationData['name'],
                'address' => $locationData['address'],
                'image' => $locationData['image'],
                'google_maps_url' => $locationData['google_maps_url'],
            ];
        }

        $this->update(['locations' => $newLocations]);
    }

    public function getTimetable($key)
    {
        if(is_null($this->timetables)) {
            $emptyValue = [];
            foreach(Locale::all() as $locale) {
                $emptyValue[$locale->code] = '';
            }
            return $emptyValue;
        }

        foreach($this->timetables as $timetable) {
            if($timetable['key'] === $key) {
                return $timetable['value'];
            }
        }
    }

    public function saveTimetables(?array $timetables)
    {
        if(is_null($timetables)) {
            return $this->update(['timetables' => null]);
        }

        $newTimetables = [];

        foreach($timetables as $key => $timetable) {
            $newTimetables[] = ['key' => $key, 'value' => $timetable];
        }

        $this->update(['timetables' => $newTimetables]);
    }
}
