<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';
    
    protected static int|null $navigationSort = 100;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('group')
                    ->options([
                        'general' => 'General',
                        'currency' => 'Currency',
                        'business_hours' => 'Business Hours',
                        'finance' => 'Finance',
                    ])
                    ->required()
                    ->columnSpanFull(),
                    
                Forms\Components\TextInput::make('key')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->disabled(fn ($record) => $record !== null),
                    
                Forms\Components\Select::make('type')
                    ->options([
                        'string' => 'Text',
                        'boolean' => 'Yes/No',
                        'integer' => 'Number',
                        'json' => 'JSON',
                    ])
                    ->required()
                    ->reactive(),
                    
                Forms\Components\Textarea::make('value')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull()
                    ->visible(fn ($get) => in_array($get('type'), ['string', 'json'])),
                    
                Forms\Components\TextInput::make('value')
                    ->numeric()
                    ->visible(fn ($get) => $get('type') === 'integer'),
                    
                Forms\Components\Toggle::make('value')
                    ->visible(fn ($get) => $get('type') === 'boolean'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('group')
                    ->badge()
                    ->colors([
                        'primary' => 'general',
                        'success' => 'currency',
                        'warning' => 'business_hours',
                        'danger' => 'finance',
                    ])
                    ->sortable()
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('key')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                    
                Tables\Columns\TextColumn::make('value')
                    ->limit(50)
                    ->searchable(),
                    
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('group')
                    ->options([
                        'general' => 'General',
                        'currency' => 'Currency',
                        'business_hours' => 'Business Hours',
                        'finance' => 'Finance',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultGroup('group');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
