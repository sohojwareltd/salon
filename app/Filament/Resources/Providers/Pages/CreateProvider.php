<?php

namespace App\Filament\Resources\Providers\Pages;

use App\Filament\Resources\Providers\ProviderResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateProvider extends CreateRecord
{
    protected static string $resource = ProviderResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // Create user first
        $userData = $data['user'];
        $userData['role'] = 'provider';
        
        $user = User::create($userData);

        // Remove user data from provider data
        unset($data['user']);
        
        // Create provider
        $data['user_id'] = $user->id;
        
        return static::getModel()::create($data);
    }
}
