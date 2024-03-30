<div class="grid grid-cols-1 lg:grid-cols-2 h-screen">
    <div class="hidden lg:block relative max-h-screen">
        <div class="absolute inset-0 w-full h-full bg-black opacity-30"></div>

        <img src="{{ config('lux.login_image') }}" class="w-full h-full object-cover" />
    </div>

    <div class="flex items-center justify-center bg-gray-50 shadow-xl">
        <form wire:submit="login" class="flex flex-col items-center w-[300px] space-y-4">
            <img src="{{ config('lux.logo') }}" class="{{ config('lux.login_logo_classes') }}" />

            <div class="w-full">
                <x-lux::input.group label="{{ trans('lux::auth.username_or_email') }}" :error="$errors->first('username')">
                    <x-lux::input.text wire:model="username" />
                </x-lux::input.group>
            </div>
    
            <div class="w-full">
                <x-lux::input.group label="{{ trans('lux::auth.password') }}" :error="$errors->first('password')">
                    <x-lux::input.password wire:model="password" />
                </x-lux::input.group>
            </div>

            <div class="w-full">
                <label class="flex items-center space-x-2">
                    <x-lux::input.checkbox wire:model="remember" />
                    <span class="text-sm">Recuérdame</span>
                </label>
            </div>

            <hr class="w-full">
    
            <x-lux::button type="submit" class="w-full">{{ trans('lux::auth.login') }}</x-lux::button>
        </form>
    </div>
</div>