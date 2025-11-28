<?php

namespace App\Filament\Resources\Appointments\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AppointmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Appointment Details')
                    ->schema([
                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable()
                            ->label('Customer'),
                        Select::make('salon_id')
                            ->relationship('salon', 'name')
                            ->required()
                            ->searchable(),
                        Select::make('provider_id')
                            ->relationship('provider', 'name')
                            ->required()
                            ->searchable(),
                        Select::make('service_id')
                            ->relationship('service', 'name')
                            ->required()
                            ->searchable(),
                    ])->columns(2),
                Section::make('Date & Time')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                DatePicker::make('appointment_date')
                                    ->required(),
                                TimePicker::make('start_time')
                                    ->required(),
                                TimePicker::make('end_time')
                                    ->required(),
                            ]),
                    ]),
                Section::make('Status')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'confirmed' => 'Confirmed',
                                        'completed' => 'Completed',
                                        'cancelled' => 'Cancelled',
                                    ])
                                    ->required()
                                    ->default('pending'),
                                Select::make('payment_status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'paid' => 'Paid',
                                        'failed' => 'Failed',
                                    ])
                                    ->required()
                                    ->default('pending'),
                            ]),
                    ]),
            ]);
    }
}
