<?php

namespace App\Filament\Resources\Providers\Pages;

use App\Filament\Resources\Providers\ProviderResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditProvider extends EditRecord
{
    protected static string $resource = ProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load user data
        if ($this->record->user) {
            $data['user'] = [
                'name' => $this->record->user->name,
                'email' => $this->record->user->email,
            ];
        }

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Update user if data provided
        if (isset($data['user']) && $record->user) {
            $userData = $data['user'];
            
            // Only update password if provided
            if (empty($userData['password'])) {
                unset($userData['password']);
            }
            
            $record->user->update($userData);
        }

        // Remove user data from provider data
        unset($data['user']);

        // Update provider
        $record->update($data);

        return $record;
    }
}
