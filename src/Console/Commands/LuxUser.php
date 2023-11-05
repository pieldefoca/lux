<?php

namespace Pieldefoca\Lux\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use function Laravel\Prompts\text;
use function Laravel\Prompts\password;

class LuxUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lux:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = text('Nombre:', required: true);

        $email = text('Email:', required: true);

        $password = password('Contraseña:', required: true);

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);
    }
}
