<?php

namespace App\Filament\Resources\FaqResource\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('FAQ Information')
                    ->schema([
                        TextInput::make('question')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull()
                            ->placeholder('Enter the question'),
                        
                        Textarea::make('answer')
                            ->required()
                            ->rows(5)
                            ->columnSpanFull()
                            ->placeholder('Enter the answer'),
                        
                        Grid::make(2)
                            ->schema([
                                TextInput::make('sort_order')
                                    ->numeric()
                                    ->default(0)
                                    ->helperText('Order in which FAQs appear'),
                                
                                Checkbox::make('is_active')
                                    ->label('Active')
                                    ->default(true)
                                    ->helperText('Show/hide this FAQ'),
                            ]),
                    ]),
            ]);
    }
}
