<?php

namespace App\Filament\Resources\PageResource\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Page Information')
                    ->tabs([
                        Tabs\Tab::make('Content')
                            ->schema([
                                Section::make('Basic Information')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('title')
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->live(onBlur: true)
                                                    ->afterStateUpdated(fn ($state, $set) => $set('slug', Str::slug($state))),
                                                
                                                TextInput::make('slug')
                                                    ->required()
                                                    ->unique(ignoreRecord: true)
                                                    ->maxLength(255)
                                                    ->helperText('URL-friendly version of the title'),
                                            ]),
                                        
                                        Textarea::make('description')
                                            ->rows(3)
                                            ->columnSpanFull()
                                            ->helperText('Short description or excerpt'),
                                        
                                        FileUpload::make('hero_image')
                                            ->image()
                                            ->directory('pages/heroes')
                                            ->maxSize(2048)
                                            ->columnSpanFull()
                                            ->helperText('Hero/banner image for the page'),
                                    ]),
                                
                                Section::make('Page Content')
                                    ->schema([
                                        RichEditor::make('content')
                                            ->label('Content')
                                            ->toolbarButtons([
                                                'bold',
                                                'italic',
                                                'underline',
                                                'strike',
                                                'link',
                                                'h2',
                                                'h3',
                                                'bulletList',
                                                'orderedList',
                                                'blockquote',
                                                'codeBlock',
                                            ])
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        
                        Tabs\Tab::make('SEO')
                            ->schema([
                                Section::make('SEO Information')
                                    ->schema([
                                        TextInput::make('meta_title')
                                            ->maxLength(255)
                                            ->columnSpanFull()
                                            ->helperText('Leave blank to use page title'),
                                        
                                        Textarea::make('meta_description')
                                            ->rows(3)
                                            ->maxLength(500)
                                            ->columnSpanFull()
                                            ->helperText('Recommended 150-160 characters'),
                                        
                                        Textarea::make('meta_keywords')
                                            ->rows(2)
                                            ->columnSpanFull()
                                            ->helperText('Comma-separated keywords'),
                                    ]),
                            ]),
                        
                        Tabs\Tab::make('Settings')
                            ->schema([
                                Section::make('Page Settings')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('sort_order')
                                                    ->numeric()
                                                    ->default(0)
                                                    ->helperText('Order in which pages appear'),
                                                
                                                Checkbox::make('is_active')
                                                    ->label('Active')
                                                    ->default(true)
                                                    ->helperText('Show/hide this page on the frontend'),
                                            ]),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}
