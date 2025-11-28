<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Payment Information')
                    ->schema([
                        Select::make('appointment_id')
                            ->relationship('appointment', 'id')
                            ->required()
                            ->searchable(),
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable()
                            ->label('Customer'),
                    ])->columns(2),
                Section::make('Amount Details')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('service_amount')
                                    ->numeric()
                                    ->required()
                                    ->prefix('$'),
                                TextInput::make('tip_amount')
                                    ->numeric()
                                    ->default(0)
                                    ->prefix('$'),
                                TextInput::make('total_amount')
                                    ->numeric()
                                    ->required()
                                    ->prefix('$'),
                            ]),
                    ]),
                Section::make('Payment Details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'completed' => 'Completed',
                                        'failed' => 'Failed',
                                        'refunded' => 'Refunded',
                                    ])
                                    ->required()
                                    ->default('pending'),
                                TextInput::make('payment_method')
                                    ->maxLength(255),
                            ]),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('stripe_payment_intent_id')
                                    ->maxLength(255),
                                TextInput::make('stripe_charge_id')
                                    ->maxLength(255),
                            ]),
                        DateTimePicker::make('paid_at'),
                    ]),
            ]);
    }
}
