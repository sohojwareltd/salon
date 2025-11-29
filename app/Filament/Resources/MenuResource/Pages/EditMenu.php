<?php

namespace App\Filament\Resources\MenuResource\Pages;

use App\Filament\Resources\MenuResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMenu extends EditRecord
{
    protected static string $resource = MenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('manage_items')
                ->label('Manage Menu Items')
                ->icon('heroicon-o-list-bullet')
                ->color('info')
                ->url(fn () => MenuResource::getUrl('view-items', ['record' => $this->record])),
            
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
