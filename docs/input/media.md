# Media

```html
<x-lux::input.group
    required
    label="Imagen"
>
    <x-lux::input.media wire:model="image" />
</x-lux::input.group>
```

```php

use Pieldefoca\Lux\Livewire\Attributes\Media;
use Pieldefoca\Lux\Traits\HasMediaFields;

class Form extends Component 
{
    use HasMediaFields;

    #[Media(collection: 'images', translatable: true)]
    public $image = [];

    public function save()
    {
        ...

        $this->saveMediaFields()

        ...
    }
}
```


