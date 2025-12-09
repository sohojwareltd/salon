<?php

namespace App\Filament\Resources\SettingResource\Pages;

use App\Filament\Resources\SettingResource;
use App\Models\Setting;
use Filament\Actions;
use Filament\Resources\Pages\Page;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;

class ManageSettings extends Page
{
    use Forms\Concerns\InteractsWithForms;

    protected static string $resource = SettingResource::class;

    protected string $view = 'filament.pages.manage-settings';

    public ?array $data = [];
    
    protected function getFormStatePath(): ?string
    {
        return 'data';
    }

    public function mount(): void
    {
        $settings = Setting::all();
        $this->data = [];
        
        foreach ($settings as $setting) {
            // Get raw value from database (always string)
            $rawValue = $setting->getAttributes()['value'] ?? '';
            
            if ($setting->key === 'opening_schedule') {
                $schedule = json_decode($rawValue, true) ?? [];
                $items = [];
                foreach ($schedule as $day => $times) {
                    $items[] = [
                        'day' => $day,
                        'open' => $times['open'] ?? false,
                        'start' => $times['start'] ?? '',
                        'end' => $times['end'] ?? '',
                    ];
                }
                $this->data[$setting->key] = $items;
            } else {
                $this->data[$setting->key] = match($setting->type) {
                    'boolean' => filter_var($rawValue, FILTER_VALIDATE_BOOLEAN),
                    'integer' => (int) $rawValue,
                    'json' => $rawValue, // Keep as JSON string for textarea
                    default => (string) $rawValue,
                };
            }
        }
        
        $this->form->fill($this->data);
    }

    protected function getFormSchema(): array
    {
        // Exclude menu-related settings (now managed via Menu resource)
        $settings = Setting::whereNotIn('key', [
            'main_menu',
            'footer_menu_1',
            'footer_menu_2', 
            'footer_menu_3',
            'footer_menu_1_title',
            'footer_menu_2_title',
            'footer_menu_3_title'
        ])->get()->groupBy('group');
        
        $tabs = [];
        
        foreach ($settings as $group => $groupSettings) {
            $fields = [];
            
            foreach ($groupSettings as $setting) {
                // Special handling for specific fields
                if (in_array($setting->key, ['header_logo', 'footer_logo', 'favicon'])) {
                    $field = Forms\Components\FileUpload::make($setting->key)
                        ->label(ucwords(str_replace('_', ' ', $setting->key)))
                        ->image()
                        ->disk('public')
                        ->directory($setting->key === 'favicon' ? 'favicons' : 'logos')
                        ->visibility('public')
                        ->maxSize(2048)
                        ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/jpg', 'image/gif', 'image/webp'])
                        ->imageEditor()
                        ->imageEditorAspectRatios($setting->key === 'favicon' ? ['1:1'] : null)
                        ->helperText($setting->key === 'favicon' ? 'Upload favicon (16x16 or 32x32 px recommended)' : null);
                } elseif ($setting->key === 'opening_schedule') {
                    $field = Forms\Components\Repeater::make($setting->key)
                        ->label('Opening Schedule')
                        ->schema([
                            Forms\Components\Select::make('day')
                                ->label('Day')
                                ->options([
                                    'monday' => 'Monday',
                                    'tuesday' => 'Tuesday',
                                    'wednesday' => 'Wednesday',
                                    'thursday' => 'Thursday',
                                    'friday' => 'Friday',
                                    'saturday' => 'Saturday',
                                    'sunday' => 'Sunday',
                                ])
                                ->required(),
                            Forms\Components\Toggle::make('open')
                                ->label('Open')
                                ->default(true)
                                ->reactive(),
                            Forms\Components\TimePicker::make('start')
                                ->label('Start Time')
                                ->visible(fn ($get) => $get('open')),
                            Forms\Components\TimePicker::make('end')
                                ->label('End Time')
                                ->visible(fn ($get) => $get('open')),
                        ])
                        ->defaultItems(0)
                        ->columns(4)
                        ->columnSpanFull();
                } elseif ($setting->key === 'about_salon_description') {
                    $field = Forms\Components\RichEditor::make($setting->key)
                        ->label('About Salon Description')
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
                        ])
                        ->columnSpanFull();
                } elseif ($setting->key === 'about_salon_image') {
                    $field = Forms\Components\FileUpload::make($setting->key)
                        ->label('About Salon Image')
                        ->image()
                        ->directory('about')
                        ->visibility('public')
                        ->maxSize(2048)
                        ->helperText('Upload image for about section (recommended: 600x400px)')
                        ->columnSpanFull();
                } elseif ($setting->key === 'hero_image') {
                    $field = Forms\Components\FileUpload::make($setting->key)
                        ->label('Hero Background Image')
                        ->image()
                        ->directory('hero')
                        ->visibility('public')
                        ->maxSize(2048)
                        ->helperText('Upload hero background image (recommended: 1600x900px)')
                        ->columnSpanFull();
                } elseif (in_array($setting->key, ['address', 'meta_description', 'feature_1_description', 'feature_2_description', 'feature_3_description', 'cta_description'])) {
                    $field = Forms\Components\Textarea::make($setting->key)
                        ->label(ucwords(str_replace('_', ' ', $setting->key)))
                        ->rows(3);
                } else {
                    $field = match($setting->type) {
                        'boolean' => Forms\Components\Toggle::make($setting->key)
                            ->label(ucwords(str_replace('_', ' ', $setting->key))),
                        'json' => Forms\Components\Textarea::make($setting->key)
                            ->label(ucwords(str_replace('_', ' ', $setting->key)))
                            ->helperText('Enter JSON data')
                            ->rows(3),
                        'integer' => Forms\Components\TextInput::make($setting->key)
                            ->label(ucwords(str_replace('_', ' ', $setting->key)))
                            ->numeric(),
                        default => Forms\Components\TextInput::make($setting->key)
                            ->label(ucwords(str_replace('_', ' ', $setting->key))),
                    };
                }
                
                $fields[] = $field;
            }
            
            $tabs[] = Tabs\Tab::make(ucwords(str_replace('_', ' ', $group)))
                ->schema($fields)
                ->columns(2);
        }
        
        return [
            Tabs::make('Settings')
                ->tabs($tabs)
                ->columnSpanFull(),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();
        
        foreach ($data as $key => $value) {
            $setting = Setting::where('key', $key)->first();
            
            if (!$setting) {
                continue;
            }
            
            if ($key === 'opening_schedule') {
                $schedule = [];
                foreach ($value as $item) {
                    $day = $item['day'];
                    $schedule[$day] = [
                        'open' => $item['open'] ?? false,
                        'start' => $item['start'] ?? '',
                        'end' => $item['end'] ?? '',
                    ];
                }
                $valueToStore = json_encode($schedule);
            } elseif (in_array($key, ['header_logo', 'footer_logo', 'favicon', 'about_salon_image', 'hero_image'])) {
                // Handle file uploads - value is already the file path from FileUpload component
                $valueToStore = $value ?? $setting->value;
            } else {
                $valueToStore = match($setting->type) {
                    'boolean' => $value ? '1' : '0',
                    'json' => is_string($value) ? $value : json_encode($value),
                    default => (string) $value,
                };
            }
            
            $setting->update(['value' => $valueToStore]);
        }
        
        \Filament\Notifications\Notification::make()
            ->title('Settings updated successfully')
            ->success()
            ->send();
    }
}
