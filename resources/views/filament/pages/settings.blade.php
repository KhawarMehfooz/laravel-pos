<x-filament::page>
    <form wire:submit.prevent="save">
        {{ $this->form }}
        <x-filament::button class="mt-6" type="submit">
            Save Settings
        </x-filament::button>
    </form>
</x-filament::page>