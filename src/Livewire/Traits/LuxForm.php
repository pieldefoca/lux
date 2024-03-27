<?php

namespace Pieldefoca\Lux\Livewire\Traits;

trait LuxForm
{
    public $editing = false;

    public function bootLuxForm()
    {
        $this->withValidator(function($validator) {
			if($validator->fails()) {
				$this->dispatch('show-error-feedback');
			}
		});
    }

    protected function prepareTranslatableAttributes(array $validated): array
    {
        $translatableAttributes = (new $this->model)->getTranslatableAttributes();

        foreach($translatableAttributes as $attribute) {
            if(array_key_exists($attribute, $validated)) {
                $validated[$attribute] = [$this->locale => $validated[$attribute]];
            }
        }

        return $validated;
    }
}