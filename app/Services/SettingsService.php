<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SettingsService
{
    protected $settings;
    protected $cacheKey = 'app_settings';
    protected $cacheDuration = 3600; // 1 hour

    public function __construct()
    {
        $this->loadSettings();
    }

    /**
     * Load settings from database with caching
     */
    protected function loadSettings()
    {
        $this->settings = Cache::remember($this->cacheKey, $this->cacheDuration, function () {
            // Check if settings table exists
            try {
                $settingsData = DB::table('settings')->pluck('value', 'key');
                
                if ($settingsData->isEmpty()) {
                    return $this->getDefaultSettings();
                }
                
                return $settingsData->toArray();
            } catch (\Exception $e) {
                return $this->getDefaultSettings();
            }
        });
    }

    /**
     * Get default settings
     */
    protected function getDefaultSettings(): array
    {
        return [
            'salon_name' => config('app.name', 'Salon'),
            'salon_logo' => null,
            'salon_cover' => null,
            'salon_phone' => '+880-1234-567890',
            'salon_email' => 'info@salon.com',
            'salon_address' => 'Dhaka, Bangladesh',
            'currency' => '৳',
            'currency_code' => 'BDT',
            'currency_position' => 'before', // before or after
            'decimal_separator' => '.',
            'thousand_separator' => ',',
            'decimal_places' => 2,
            'commission_percentage' => 20.00,
            'opening_time' => '09:00:00',
            'closing_time' => '21:00:00',
            'timezone' => 'Asia/Dhaka',
        ];
    }

    /**
     * Get currency symbol
     */
    public function currency(): string
    {
        return $this->get('currency', '৳');
    }

    /**
     * Get currency code
     */
    public function currencyCode(): string
    {
        return $this->get('currency_code', 'BDT');
    }

    /**
     * Format price with currency
     */
    public function formatPrice(?float $amount, bool $showCurrency = true): string
    {
        $amount = $amount ?? 0;
        
        $decimalPlaces = (int) $this->get('decimal_places', 2);
        $decimalSeparator = $this->get('decimal_separator', '.');
        $thousandSeparator = $this->get('thousand_separator', ',');
        
        $formattedAmount = number_format($amount, $decimalPlaces, $decimalSeparator, $thousandSeparator);
        
        if (!$showCurrency) {
            return $formattedAmount;
        }
        
        $currency = $this->currency();
        $position = $this->get('currency_position', 'before');
        
        if ($position === 'after') {
            return $formattedAmount . ' ' . $currency;
        }
        
        return $currency . $formattedAmount;
    }

    /**
     * Get salon name
     */
    public function salonName(): string
    {
        return $this->get('salon_name', config('app.name', 'Salon'));
    }

    /**
     * Get salon logo URL
     */
    public function salonLogo(): ?string
    {
        $logo = $this->get('salon_logo');
        return $logo ? asset('storage/' . $logo) : null;
    }

    /**
     * Get salon cover image URL
     */
    public function salonCover(): ?string
    {
        $cover = $this->get('salon_cover');
        return $cover ? asset('storage/' . $cover) : null;
    }

    /**
     * Get salon phone
     */
    public function salonPhone(): string
    {
        return $this->get('salon_phone', '+880-1234-567890');
    }

    /**
     * Get salon email
     */
    public function salonEmail(): string
    {
        return $this->get('salon_email', 'info@salon.com');
    }

    /**
     * Get salon address
     */
    public function salonAddress(): string
    {
        return $this->get('salon_address', 'Dhaka, Bangladesh');
    }

    /**
     * Get business hours
     */
    public function businessHours(): array
    {
        return [
            'opening' => $this->get('opening_time', '09:00:00'),
            'closing' => $this->get('closing_time', '21:00:00'),
            'timezone' => $this->get('timezone', 'Asia/Dhaka'),
        ];
    }

    /**
     * Get commission percentage
     */
    public function commissionPercentage(): float
    {
        return (float) $this->get('commission_percentage', 20.00);
    }

    /**
     * Calculate provider earning after commission
     */
    public function calculateProviderEarning(float $totalAmount): float
    {
        $commission = $this->commissionPercentage();
        return $totalAmount * (1 - ($commission / 100));
    }

    /**
     * Calculate commission amount
     */
    public function calculateCommission(float $totalAmount): float
    {
        $commission = $this->commissionPercentage();
        return $totalAmount * ($commission / 100);
    }

    /**
     * Get specific setting by key
     */
    public function get(string $key, $default = null)
    {
        return $this->settings[$key] ?? $default;
    }

    /**
     * Set setting value (and update database)
     */
    public function set(string $key, $value): bool
    {
        try {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'updated_at' => now()]
            );
            
            $this->refresh();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Set multiple settings at once
     */
    public function setMany(array $settings): bool
    {
        try {
            foreach ($settings as $key => $value) {
                DB::table('settings')->updateOrInsert(
                    ['key' => $key],
                    ['value' => $value, 'updated_at' => now()]
                );
            }
            
            $this->refresh();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Refresh cached settings
     */
    public function refresh(): void
    {
        Cache::forget($this->cacheKey);
        $this->loadSettings();
    }

    /**
     * Get all settings
     */
    public function all(): array
    {
        return $this->settings;
    }
}
