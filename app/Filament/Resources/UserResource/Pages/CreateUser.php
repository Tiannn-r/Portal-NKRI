<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected bool $isAdminState = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->isAdminState = !empty($data['is_admin']);
        unset($data['is_admin']);

        return $data;
    }

    protected function afterCreate(): void
    {
        if ($this->isAdminState) {
            $this->record->assignRole('Super Admin');
        }
    }
}
