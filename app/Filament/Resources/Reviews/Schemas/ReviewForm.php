<?php

namespace App\Filament\Resources\Reviews\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Review Information')
                    ->schema([
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable()
                            ->label('Customer'),
                        Select::make('provider_id')
                            ->relationship('provider', 'name')
                            ->required()
                            ->searchable(),
                        Select::make('appointment_id')
                            ->relationship('appointment', 'id')
                            ->required()
                            ->searchable(),
                    ])->columns(3),
                Section::make('Rating & Comment')
                    ->schema([
                        TextInput::make('rating')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->maxValue(5)
                            ->step(1),
                        Textarea::make('comment')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
