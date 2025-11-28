<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Service Information')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('category')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('description')
                            ->rows(3),
                        FileUpload::make('image')
                            ->image()
                            ->directory('services'),
                    ]),
                Section::make('Pricing & Duration')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('duration')
                                    ->numeric()
                                    ->required()
                                    ->suffix('minutes'),
                                TextInput::make('price')
                                    ->numeric()
                                    ->required()
                                    ->prefix('$'),
                                Checkbox::make('is_active')
                                    ->default(true),
                            ]),
                    ]),
            ]);
    }
}
