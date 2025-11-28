<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string currency()
 * @method static string formatPrice(float $amount)
 * @method static string salonName()
 * @method static string salonLogo()
 * @method static string salonCover()
 * @method static string salonPhone()
 * @method static string salonEmail()
 * @method static string salonAddress()
 * @method static array businessHours()
 * @method static float commissionPercentage()
 * @method static mixed get(string $key, $default = null)
 * @method static void refresh()
 *
 * @see \App\Services\SettingsService
 */
class Settings extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'settings';
    }
}
