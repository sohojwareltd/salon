<?php

namespace App\Filament\Resources\Providers\Schemas;

use App\Models\User;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class ProviderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('User Account')
                    ->description('Create user account for provider login')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('user.name')
                                    ->label('Full Name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('user.email')
                                    ->label('Email')
                                    ->email()
                                    ->required()
                                    ->unique(User::class, 'email', modifyRuleUsing: function ($rule, $livewire) {
                                        if ($livewire instanceof \Filament\Resources\Pages\EditRecord) {
                                            return $rule->ignore($livewire->record->user_id ?? null);
                                        }
                                        return $rule;
                                    })
                                    ->maxLength(255),
                            ]),
                        TextInput::make('user.password')
                            ->label('Password')
                            ->password()
                            ->required(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord)
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->maxLength(255),
                    ]),
                
                Section::make('Services')
                    ->schema([
                        Select::make('services')
                            ->relationship('services', 'name')
                            ->multiple()
                            ->preload()
                            ->required(),
                    ]),
                
                Section::make('Schedule & Timing')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TimePicker::make('break_start')
                                    ->label('Break Start Time'),
                                TimePicker::make('break_end')
                                    ->label('Break End Time'),
                                TextInput::make('buffer_time')
                                    ->label('Buffer Time (minutes)')
                                    ->numeric()
                                    ->suffix('min')
                                    ->default(0)
                                    ->helperText('Time gap between appointments'),
                            ]),
                        
                        Repeater::make('schedules')
                            ->relationship('schedules')
                            ->label('Weekly Schedule')
                            ->schema([
                                Select::make('weekday')
                                    ->label('Day')
                                    ->options([
                                        0 => 'Sunday',
                                        1 => 'Monday',
                                        2 => 'Tuesday',
                                        3 => 'Wednesday',
                                        4 => 'Thursday',
                                        5 => 'Friday',
                                        6 => 'Saturday',
                                    ])
                                    ->required()
                                    ->distinct()
                                    ->validationMessages([
                                        'distinct' => 'Each day can only be selected once.',
                                    ]),
                                TimePicker::make('start_time')
                                    ->label('Start Time'),
                                TimePicker::make('end_time')
                                    ->label('End Time'),
                                Checkbox::make('is_off')
                                    ->label('Available')
                                    ->helperText('Uncheck if provider is off on this day')
                                    ->default(true)
                                    ->formatStateUsing(fn ($state) => !$state)
                                    ->dehydrateStateUsing(fn ($state) => !$state),
                            ])
                            ->columns(4)
                            ->defaultItems(0)
                            ->addActionLabel('Add Schedule')
                            ->collapsible(),
                    ]),
                
                Section::make('Provider Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Provider Display Name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('phone')
                                    ->tel()
                                    ->required()
                                    ->maxLength(255),
                            ]),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('email')
                                    ->label('Contact Email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('expertise')
                                    ->maxLength(255),
                            ]),
                        Textarea::make('bio')
                            ->rows(3),
                        FileUpload::make('photo')
                            ->image()
                            ->directory('providers'),
                        Checkbox::make('is_active')
                            ->default(true),
                    ]),
            ]);
    }
}
