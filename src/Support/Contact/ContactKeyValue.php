<?php

namespace Pieldefoca\Lux\Support\Contact;

use Livewire\Wireable;

class ContactKeyValue implements Wireable
{
    public string $key;

    public string $value;

    public function __construct(string $key = '', string $value = '')
    {
        $this->key = $key;
        $this->value = $value;
    }

    public function getLabel()
    {
        return $this->key;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function toLivewire()
    {
        return [
            'key' => $this->key,
            'value' => $this->value,
        ];
    }

    public static function fromLivewire($value)
    {
        $key = $value['key'];
        $value = $value['value'];

        return new static($key, $value);
    }
}