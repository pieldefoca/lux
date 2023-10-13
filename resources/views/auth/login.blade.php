<x-lux::layouts.auth>
    <div class="grid place-items-center w-screen h-screen">
        <div class="flex flex-col items-center w-1/4 space-y-8">
            <x-lux::logo class="w-28" />

            <form action="/login" method="POST" class="w-full rounded-lg shadow bg-white p-8 space-y-6">
                <h1 class="text-xl font-bold">Entrar</h1>
                @csrf

                <x-lux::input.group label="Email" :error="$errors->first('email')">
                    <x-lux::auth.input.text name="email" value="{{ old('email') }}" />
                </x-lux::input.group>

                <x-lux::input.group label="Contraseña" :error="$errors->first('password')">
                    <x-lux::auth.input.password name="password" />
                </x-lux::input.group>

                <hr>

                <div class="flex items-center justify-between">
                    <x-lux::button type="submit">Entrar</x-lux::button>

                    <x-lux::auth.input.checkbox name="remember" label="Recuérdame" />
                </div>
            </form>
        </div>
    </div>
</x-lux::layouts.auth>
