<x-filament-panels::page>
    <x-filament-panels::form wire:submit="save">
        {{ $this->form }}

        <div class="flex items-center gap-3 mt-6">
            <x-filament::button type="submit" color="primary">
                Simpan Pengaturan Portal
            </x-filament::button>
        </div>
    </x-filament-panels::form>
</x-filament-panels::page>
