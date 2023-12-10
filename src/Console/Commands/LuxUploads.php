<?php

namespace Pieldefoca\Lux\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Pieldefoca\Lux\Models\Media;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;

class LuxUploads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lux:uploads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Analyze uploads folder';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $uploadsPath = public_path('uploads');

        $files = File::allFiles($uploadsPath);

        foreach($files as $file) {
            $media = Media::where('filename', $file->getFilename())->first();

            if(is_null($media)) {
                $name = $file->getFilename() === '_placeholder.jpg'
                    ? 'Placeholder'
                    :  str($file->getFilename())->title()->replace('-', ' ')->replace('_', ' ')->toString();
                Media::create([
                    'name' => $name,
                    'filename' => $file->getFilename(),
                    'mime_type' => mime_content_type($file->getPathname()),
                    'media_type' => 'image',
                ]);
            }
        }
    }
}
