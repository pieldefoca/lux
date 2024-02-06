<?php

namespace Pieldefoca\Lux\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Pieldefoca\Lux\Models\Media;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class LuxInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lux:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize lux stuff';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Create the uploads folder

        // Copy placeholder image

        Media::create([
            'name' => ['es' => 'Placeholder'],
            'filename' => '_placeholder.jpg',
            'mime_type' => 'image/jpeg',
            'media_type' => 'image',
            'extension' => 'jpg',
            'original_image' => '_placeholder.jpg',
        ]);

        Artisan::call('lux:pages');
    }
}
