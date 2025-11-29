<?php

namespace App\Filament\Resources\ContactResource\Pages;

use App\Filament\Resources\ContactResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewContact extends ViewRecord
{
    protected static string $resource = ContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('mark_read')
                ->label('Mark as Read')
                ->icon('heroicon-o-envelope-open')
                ->color('success')
                ->visible(fn () => !$this->record->is_read)
                ->action(function () {
                    $this->record->markAsRead();
                }),
            
            Action::make('mark_replied')
                ->label('Mark as Replied')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn () => !$this->record->replied_at)
                ->action(function () {
                    $this->record->markAsReplied();
                }),
            
            DeleteAction::make(),
        ];
    }
    
    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Contact Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Name')
                                    ->disabled(),
                                
                                TextInput::make('email')
                                    ->label('Email')
                                    ->disabled(),
                            ]),
                        
                        TextInput::make('subject')
                            ->label('Subject')
                            ->disabled()
                            ->columnSpanFull(),
                        
                        Textarea::make('message')
                            ->label('Message')
                            ->rows(6)
                            ->disabled()
                            ->columnSpanFull(),
                    ]),
                
                Section::make('Metadata')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Placeholder::make('created_at')
                                    ->label('Received At')
                                    ->content(fn ($record) => $record->created_at->format('M d, Y h:i A')),
                                
                                Placeholder::make('is_read')
                                    ->label('Status')
                                    ->content(fn ($record) => $record->is_read ? '✓ Read' : '✗ Unread'),
                                
                                Placeholder::make('replied_at')
                                    ->label('Replied At')
                                    ->content(fn ($record) => $record->replied_at ? $record->replied_at->format('M d, Y h:i A') : 'Not replied yet'),
                            ]),
                    ]),
            ]);
    }
    
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Mark as read when viewing
        if (!$this->record->is_read) {
            $this->record->markAsRead();
        }
        
        return $data;
    }
}
