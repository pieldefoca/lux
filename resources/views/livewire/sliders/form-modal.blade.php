<x-lux::modal>
    <x-lux::modal.panel>
        <x-lux::modal.title>{{ trans('lux::sliders.new_slider') }}</x-lux::modal.title>
        
        <form wire:submit="save" class="space-y-6">
            <x-lux::input.group required :label="trans('lux::sliders.name')" error="{{ $errors->first('name') }}">
                <x-lux::input.text wire:model="name" />
            </x-lux::input.group>

            <x-lux::modal.footer>
                <div class="flex items-center justify-end space-x-8 w-full">
                    <x-lux::modal.button.cancel />
                    <x-lux::modal.button.save />
                </div>
            </x-lux::modal.footer>
        </form>
    </x-lux::modal.panel>
</x-lux::modal>
