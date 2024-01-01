<?php

namespace Pieldefoca\Lux\Livewire\Table\Traits;

trait WithSorting
{
    public $sorts = [];

    public function sortBy($field)
    {
       if (! isset($this->sorts[$field])) return $this->sorts[$field] = 'asc';

       if ($this->sorts[$field] === 'asc') return $this->sorts[$field] = 'desc';

        unset($this->sorts[$field]);
    }

    public function applySorting($query)
    {
        $translatableFields = [];

        try {
            $translatableFields = collect((new $this->model)->getTranslatableAttributes());
        } catch(\Exception $e) {
            $translatableFields = collect([]);
        }

        foreach ($this->sorts as $field => $direction) {
            if($translatableFields->contains($field)) {
                $locale = $this->currentLocaleCode;
                $query->orderBy("{$field}->{$locale}", $direction);
            } else {
                $query->orderBy($field, $direction);
            }
        }

        return $query;
    }
}
