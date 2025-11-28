<?php

namespace App\Filament\Resources\Providers\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProviderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Provider Information')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Select::make('salon_id')
                            ->relationship('salon', 'name')
                            ->required()
                            ->searchable(),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('phone')
                                    ->tel()
                                    ->required()
                                    ->maxLength(255),
                            ]),
                        TextInput::make('expertise')
                            ->maxLength(255),
                        Textarea::make('bio')
                            ->rows(3),
                        FileUpload::make('photo')
                            ->image()
                            ->directory('providers'),
                        Checkbox::make('is_active')
                            ->default(true),
                    ]),
                Section::make('Break Time')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TimePicker::make('break_start'),
                                TimePicker::make('break_end'),
                            ]),
                    ]),
                Section::make('Services')
                    ->schema([
                        Select::make('services')
                            ->relationship('services', 'name')
                            ->multiple()
                            ->preload()
                            ->required(),
                    ]),
            ]);
    }
}
