<?php

namespace Pieldefoca\Lux\Console\Commands;

use Pieldefoca\Lux\Models\Page;
use Illuminate\Console\Command;
use function Laravel\Prompts\{text, select};
use Pieldefoca\Lux\Models\Locale;
use Illuminate\Support\Str;
use Pieldefoca\Lux\Facades\Pages;

class LuxPage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lux:page';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new lux page';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = text(label: 'Page ID:', hint: 'Think of the ID like the route name', required: true);

        $name = text(label: 'Name', hint: 'Nombre descriptivo de la página', required: true);

        $url = text(label: 'URL:', required: true);

        $target = select('Target type:', ['controller' => 'Controlador', 'livewire' => 'Livewire'], default: 'livewire');

        $controllerClass = $controllerAction = $livewireComponent = null;

        if($target === 'controller') {
            $controllerName = text(label: 'Controller:', placeholder: 'e.g.: HomeController', required: true);

            if(Str::startsWith($controllerName, 'App\Http\Controllers')) {
                $controllerClass = "App\Http\Controllers\\{$controllerName}";
            }

            $controllerAction = text(label: 'Controller action:', required: true);
        } else {
            $componentName = text(label: 'Livewire component:', required: true);

            $livewireComponent = Pages::getLivewireComponentClassName($componentName);
        }

        Page::create([
            'id' => $id,
            'name' => $name,
            'slug' => $url === '/' ? $url : Str::unwrap($url, '/', '/'),
            'controller' => $controllerClass,
            'controller_action' => $controllerAction,
            'livewire_component' => $livewireComponent,
        ]);

        if($target === 'controller') {
            $this->call('make:controller', ['name' => $controllerName]);
        } else {
            $this->call('make:livewire', ['name' => $componentName]);
        }

        Pages::generatePageRoutes();
    }
}
