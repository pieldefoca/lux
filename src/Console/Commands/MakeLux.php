<?php

namespace Pieldefoca\Lux\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeLux extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:lux {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make lux resource files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $nameTitle = str($name)->replace('.', '\\')->title()->toString();

        $namespace = "App\Livewire\Lux\{$nameTitle}";
        $view = "livewire.lux.{$name}";

        $classStubsDir = __DIR__.'/../../../stubs/classes';
        foreach(File::files($classStubsDir) as $file) {
            $fileName = pathinfo($file)['filename'];
            $classFileName = "{$fileName}.php";

            if(!file_exists(app_path('Livewire/Lux'))) {
                mkdir(app_path('Livewire/Lux'), recursive: true);
            }

            copy($file->getPath().'/'.$file->getFilename(), app_path('Livewire/Lux/'.$classFileName));
        }

        $viewsStubsDir = __DIR__.'/../../../stubs/views';
        foreach(File::files($viewsStubsDir) as $file) {
            $fileName = strtolower($file->getFilename());

            if(!file_exists(resource_path('views/livewire/lux'))) {
                mkdir(resource_path('views/livewire/lux'), recursive: true);
            }

            copy($file->getPath().'/'.$file->getFilename(), resource_path('views/livewire/lux/'.$fileName));
        }
    }
}
