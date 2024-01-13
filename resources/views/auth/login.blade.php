<x-lux::layouts.auth>
    <div class="grid place-items-center w-screen h-screen">
        <div class="flex flex-col items-center w-full sm:w-2/3 md:w-1/2 lg:w-1/2 xl:w-1/3 2xl:w-1/4 p-4 space-y-8">
            <x-lux::logo class="w-28" />

            <form action="/login" method="POST" class="w-full rounded-lg shadow bg-white p-8 space-y-6">
                <h1 class="text-xl font-bold">Entrar</h1>
                @csrf

                <x-lux::input.group label="Usuario o email" :error="$errors->first('login')">
                    <x-lux::auth.input.text name="login" value="{{ old('login') }}" />
                </x-lux::input.group>

                <x-lux::input.group label="Contraseña" :error="$errors->first('password')">
                    <x-lux::auth.input.password name="password" />
                </x-lux::input.group>

                <hr>

                <div class="flex items-center justify-between">
                    <x-lux::auth.input.checkbox name="remember" label="Recuérdame" />

                    <x-lux::button type="submit">Entrar</x-lux::button>
                </div>
            </form>
        </div>
    </div>
</x-lux::layouts.auth>
