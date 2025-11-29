<?php

namespace App\Filament\Resources\MenuResource\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MenuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Menu Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255)
                                    ->helperText('Unique identifier (e.g., main_menu, footer_menu_1)')
                                    ->alphaDash(),
                                
                                TextInput::make('title')
                                    ->required()
                                    ->maxLength(255)
                                    ->helperText('Display title for admin panel'),
                            ]),
                        
                        Grid::make(3)
                            ->schema([
                                Select::make('location')
                                    ->required()
                                    ->options([
                                        'header' => 'Header Navigation',
                                        'footer_1' => 'Footer Column 1',
                                        'footer_2' => 'Footer Column 2',
                                        'footer_3' => 'Footer Column 3',
                                        'sidebar' => 'Sidebar',
                                    ])
                                    ->default('header'),
                                
                                TextInput::make('sort_order')
                                    ->numeric()
                                    ->default(0)
                                    ->required()
                                    ->helperText('Order in which menus appear'),
                                
                                Checkbox::make('is_active')
                                    ->label('Active')
                                    ->default(true)
                                    ->helperText('Show/hide this menu on the frontend'),
                            ]),
                    ]),
            ]);
    }
}
