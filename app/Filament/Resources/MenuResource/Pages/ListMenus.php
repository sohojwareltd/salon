<?php

namespace App\Filament\Resources\MenuResource\Pages;

use App\Filament\Resources\MenuResource;
use App\Models\Menu;
use App\Models\MenuItem;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ListRecords;

class ListMenus extends ListRecords
{
    protected static string $resource = MenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus'),
            
            Actions\Action::make('add_menu_item')
                ->label('Add Menu Item')
                ->icon('heroicon-o-plus-circle')
                ->color('success')
                ->modalHeading('Add Menu Item')
                ->modalSubmitActionLabel('Create Item')
                ->form([
                    Forms\Components\Select::make('menu_id')
                        ->label('Select Menu')
                        ->options(Menu::all()->pluck('title', 'id'))
                        ->required()
                        ->searchable()
                        ->helperText('Choose which menu this item belongs to'),
                    
                    Forms\Components\TextInput::make('label')
                        ->required()
                        ->maxLength(255)
                        ->label('Menu Label')
                        ->placeholder('e.g., Home, About Us'),
                    
                    Forms\Components\TextInput::make('url')
                        ->required()
                        ->maxLength(255)
                        ->label('URL')
                        ->placeholder('e.g., /, /about')
                        ->helperText('Use "/" for homepage or "/about" for pages'),
                    
                    Forms\Components\TextInput::make('route')
                        ->maxLength(255)
                        ->label('Route (Optional)')
                        ->placeholder('e.g., home, about'),
                    
                    Forms\Components\TextInput::make('icon')
                        ->maxLength(255)
                        ->label('Icon (Optional)')
                        ->placeholder('e.g., house, info-circle')
                        ->prefix('bi-'),
                    
                    Forms\Components\Select::make('target')
                        ->options([
                            '_self' => 'Same Window',
                            '_blank' => 'New Tab',
                        ])
                        ->default('_self')
                        ->required(),
                    
                    Forms\Components\TextInput::make('sort_order')
                        ->numeric()
                        ->default(0)
                        ->required()
                        ->label('Order'),
                    
                    Forms\Components\Toggle::make('is_active')
                        ->label('Active')
                        ->default(true),
                ])
                ->action(function (array $data) {
                    MenuItem::create($data);
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Menu item created successfully')
                        ->success()
                        ->send();
                }),
        ];
    }
}
