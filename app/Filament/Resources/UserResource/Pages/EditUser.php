<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected bool $isAdminState = false;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->isAdminState = !empty($data['is_admin']);
        unset($data['is_admin']);

        return $data;
    }

    protected function afterSave(): void
    {
        if ($this->isAdminState) {
            $this->record->assignRole('Super Admin');
        } else {
            // Hindari mencabut role Super Admin akun sendiri agar tidak terkunci
            if ($this->record->id !== auth()->id()) {
                $this->record->removeRole('Super Admin');
            }
        }
    }
}
