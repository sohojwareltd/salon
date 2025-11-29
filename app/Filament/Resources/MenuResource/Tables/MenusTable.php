<?php

namespace App\Filament\Resources\MenuResource\Tables;

use App\Filament\Resources\MenuResource;
use App\Models\Menu;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class MenusTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Menu $record): string => $record->name),
                
                BadgeColumn::make('location')
                    ->formatStateUsing(fn($state) => match($state) {
                        'header' => 'Header Navigation',
                        'footer_1' => 'Footer Column 1',
                        'footer_2' => 'Footer Column 2',
                        'footer_3' => 'Footer Column 3',
                        'sidebar' => 'Sidebar',
                        default => $state,
                    })
                    ->colors([
                        'primary' => 'header',
                        'success' => fn($state) => str_starts_with($state, 'footer'),
                        'warning' => 'sidebar',
                    ])
                    ->sortable(),
                
                TextColumn::make('items_count')
                    ->counts('items')
                    ->label('Items')
                    ->badge()
                    ->color('success')
                    ->icon('heroicon-o-list-bullet'),
                
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),
                
                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable()
                    ->badge()
                    ->color('gray'),
            ])
            ->defaultSort('sort_order')
            ->filters([
                SelectFilter::make('location')
                    ->options([
                        'header' => 'Header',
                        'footer_1' => 'Footer 1',
                        'footer_2' => 'Footer 2',
                        'footer_3' => 'Footer 3',
                        'sidebar' => 'Sidebar',
                    ]),
                
                TernaryFilter::make('is_active')
                    ->label('Active Status'),
            ])
            ->recordActions([
                Action::make('add_item')
                    ->label('Add Item')
                    ->icon('heroicon-o-plus-circle')
                    ->color('success')
                    ->modalHeading(fn (?Menu $record) => $record ? 'Add Item to ' . $record->title : 'Add Menu Item')
                    ->modalSubmitActionLabel('Create Item')
                    ->form([
                        TextInput::make('label')
                            ->required()
                            ->maxLength(255)
                            ->label('Menu Label')
                            ->placeholder('e.g., Home, About Us'),
                        
                        TextInput::make('url')
                            ->required()
                            ->maxLength(255)
                            ->label('URL')
                            ->placeholder('e.g., /, /about')
                            ->helperText('Use "/" for homepage or "/about" for pages'),
                        
                        TextInput::make('route')
                            ->maxLength(255)
                            ->label('Route (Optional)')
                            ->placeholder('e.g., home, about'),
                        
                        TextInput::make('icon')
                            ->maxLength(255)
                            ->label('Icon (Optional)')
                            ->placeholder('e.g., house, info-circle')
                            ->prefix('bi-'),
                        
                        Select::make('target')
                            ->options([
                                '_self' => 'Same Window',
                                '_blank' => 'New Tab',
                            ])
                            ->default('_self')
                            ->required(),
                        
                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->label('Order'),
                        
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ])
                    ->action(function (?Menu $record, array $data) {
                        if ($record) {
                            $record->items()->create($data);
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Menu item created successfully')
                                ->success()
                                ->send();
                        }
                    }),
                
                Action::make('view_items')
                    ->label('View Items')
                    ->icon('heroicon-o-list-bullet')
                    ->color('info')
                    ->url(fn (?Menu $record): ?string => $record ? MenuResource::getUrl('view-items', ['record' => $record]) : null),
                
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
