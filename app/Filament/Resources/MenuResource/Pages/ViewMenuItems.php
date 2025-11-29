<?php

namespace App\Filament\Resources\MenuResource\Pages;

use App\Filament\Resources\MenuResource;
use App\Models\Menu;
use App\Models\MenuItem;
use BackedEnum;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ViewMenuItems extends ManageRelatedRecords
{
    protected static string $resource = MenuResource::class;

    protected static string $relationship = 'items';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedListBullet;

    public static function getNavigationLabel(): string
    {
        return 'Menu Items';
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('label')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                TextColumn::make('url')
                    ->searchable()
                    ->copyable()
                    ->color('primary'),
                
                TextColumn::make('route')
                    ->placeholder('—'),
                
                TextColumn::make('icon')
                    ->formatStateUsing(fn ($state) => $state ? 'bi-' . $state : '—')
                    ->badge()
                    ->color('gray'),
                
                BadgeColumn::make('target')
                    ->colors([
                        'success' => '_self',
                        'warning' => '_blank',
                    ])
                    ->formatStateUsing(fn ($state) => match($state) {
                        '_self' => 'Same Window',
                        '_blank' => 'New Tab',
                        default => $state,
                    }),
                
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),
                
                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable()
                    ->badge(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Active Status'),
            ])
            ->headerActions([
                Actions\CreateAction::make()
                    ->label('Add Menu Item')
                    ->icon('heroicon-o-plus-circle')
                    ->form([
                        Forms\Components\TextInput::make('label')
                            ->required()
                            ->maxLength(255)
                            ->label('Menu Label')
                            ->placeholder('e.g., Home, About Us'),
                        
                        Forms\Components\TextInput::make('url')
                            ->required()
                            ->maxLength(255)
                            ->label('URL')
                            ->placeholder('e.g., /, /about'),
                        
                        Forms\Components\TextInput::make('route')
                            ->maxLength(255)
                            ->label('Route (Optional)'),
                        
                        Forms\Components\TextInput::make('icon')
                            ->maxLength(255)
                            ->label('Icon (Optional)')
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
                            ->required(),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ]),
            ])
            ->recordActions([
                EditAction::make()
                    ->form([
                        Forms\Components\TextInput::make('label')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('url')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('route')
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('icon')
                            ->maxLength(255)
                            ->prefix('bi-'),
                        
                        Forms\Components\Select::make('target')
                            ->options([
                                '_self' => 'Same Window',
                                '_blank' => 'New Tab',
                            ])
                            ->required(),
                        
                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->required(),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active'),
                    ]),
                
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('enable')
                        ->label('Enable Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(fn ($records) => $records->each(fn ($record) => $record->update(['is_active' => true])))
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),
                    
                    BulkAction::make('disable')
                        ->label('Disable Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn ($records) => $records->each(fn ($record) => $record->update(['is_active' => false])))
                        ->deselectRecordsAfterCompletion()
                        ->requiresConfirmation(),
                    
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
