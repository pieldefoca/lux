<?php

namespace Pieldefoca\Lux\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Livewire\Attributes\Validate;

#[Layout('lux::components.layouts.guest')]
class Login extends Component
{
    #[Validate('required', message: 'Escribe tu email o nombre de usuario')]
    public string $username = '';

    #[Validate('required', message: 'Escribe tu contraseña')]
    public string $password = '';

    #[Validate('boolean')]
    public bool $remember = false;

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $credentials = [
            'username' => $this->username,
            'password' => $this->password
        ];

        $user = \App\Models\User::where('email', $this->username)
                ->orWhere('username', $this->username)
                ->first();

        if (!Auth::attempt($credentials, $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'username' => trans('lux::lux.auth_failed'),
            ]);
        }

        Auth::login($user);
    }

    public function login()
    {
        $this->validate();

        $this->authenticate();
    
        Session::regenerate();
    
        $this->redirectIntended(default: route('home'), navigate: true);    
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'form.email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->username).'|'.request()->ip());
    }

    public function render()
    {
        return view('lux::livewire.auth.login');
    }
}