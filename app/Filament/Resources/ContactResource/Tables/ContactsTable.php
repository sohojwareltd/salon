<?php

namespace App\Filament\Resources\ContactResource\Tables;

use App\Models\Contact;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ContactsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                IconColumn::make('is_read')
                    ->label('')
                    ->boolean()
                    ->trueIcon('heroicon-o-envelope-open')
                    ->falseIcon('heroicon-o-envelope')
                    ->trueColor('gray')
                    ->falseColor('primary')
                    ->tooltip(fn (Contact $record): string => $record->is_read ? 'Read' : 'Unread'),
                
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight(fn (Contact $record) => $record->is_read ? 'normal' : 'bold'),
                
                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Email copied!')
                    ->icon('heroicon-o-envelope'),
                
                TextColumn::make('subject')
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->weight(fn (Contact $record) => $record->is_read ? 'normal' : 'bold'),
                
                TextColumn::make('message')
                    ->searchable()
                    ->limit(50)
                    ->wrap(),
                
                IconColumn::make('replied_at')
                    ->label('Replied')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->sortable(),
                
                TextColumn::make('created_at')
                    ->label('Received')
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->description(fn (Contact $record): string => $record->created_at->format('M d, Y h:i A')),
            ])
            ->filters([
                TernaryFilter::make('is_read')
                    ->label('Read Status')
                    ->placeholder('All Messages')
                    ->trueLabel('Read only')
                    ->falseLabel('Unread only'),
                
                TernaryFilter::make('replied_at')
                    ->label('Reply Status')
                    ->placeholder('All Messages')
                    ->trueLabel('Replied')
                    ->falseLabel('Not Replied')
                    ->queries(
                        true: fn ($query) => $query->whereNotNull('replied_at'),
                        false: fn ($query) => $query->whereNull('replied_at'),
                    ),
            ])
            ->actions([
                ViewAction::make(),
                Action::make('mark_read')
                    ->label('Mark as Read')
                    ->icon('heroicon-o-envelope-open')
                    ->color('success')
                    ->visible(fn (Contact $record) => !$record->is_read)
                    ->action(fn (Contact $record) => $record->markAsRead())
                    ->requiresConfirmation(false),
                
                Action::make('mark_replied')
                    ->label('Mark as Replied')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Contact $record) => !$record->replied_at)
                    ->action(fn (Contact $record) => $record->markAsReplied())
                    ->requiresConfirmation(false),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
