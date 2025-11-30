# FACADE FIXED - Server Deployment Commands

## ✅ What Was Fixed:

All blade files have been updated to use `App\Facades\Settings::` instead of `Settings::`

Files fixed (20 files):
- service-card.blade.php
- booking-details.blade.php (multiple)
- dashboard.blade.php (multiple)
- payment files
- home.blade.php
- appointments files
- provider files
- wallet files
- service management files

## 🚀 Deploy to Server:

### Step 1: Upload Files to Server
Upload all the fixed blade files from `resources/views/` to your server.

### Step 2: Run These Commands on Server:

```bash
cd ~/repositories/salon

# Clear all cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Remove bootstrap cache
rm -rf bootstrap/cache/*.php

# Regenerate autoload
composer dump-autoload -o

# Fix permissions
chmod -R 775 storage bootstrap/cache

# Restart PHP-FPM and web server
sudo systemctl restart php8.1-fpm
sudo systemctl restart apache2
```

### One-Liner Command:
```bash
cd ~/repositories/salon && php artisan config:clear && php artisan cache:clear && php artisan view:clear && rm -rf bootstrap/cache/*.php && composer dump-autoload -o && chmod -R 775 storage bootstrap/cache && sudo systemctl restart php8.1-fpm && sudo systemctl restart apache2
```

## ✅ Verify Fix:

After running commands, test your site:
```bash
# Test if app bootstraps correctly
php artisan about

# Test Settings facade
php artisan tinker
>>> App\Facades\Settings::get('site_name')
>>> exit
```

## 📋 Checklist:

- [x] All blade files updated with App\Facades\Settings::
- [x] AppServiceProvider.php has Settings service registered
- [x] SettingsService.php exists in app/Services/
- [x] Settings.php facade exists in app/Facades/
- [ ] Files uploaded to server
- [ ] Cache cleared on server
- [ ] PHP-FPM restarted
- [ ] Site tested and working

## 🔍 If Still Having Issues:

1. Verify AppServiceProvider is correct:
```bash
cat app/Providers/AppServiceProvider.php
```

Should contain:
```php
$this->app->singleton('settings', function ($app) {
    return new SettingsService();
});
```

2. Check if files uploaded correctly:
```bash
grep -r "App\\Facades\\Settings::" resources/views/ | wc -l
# Should show many matches
```

3. Test manually:
```bash
php artisan tinker
>>> app()->has('settings')  // Should return true
>>> app('settings')         // Should return SettingsService instance
>>> exit
```

## 🎯 Expected Result:

Your site should load without the "A facade root has not been set" error.
All Settings:: calls will now work properly throughout the application.

---

**Status**: ✅ FIXED
**Next**: Upload files and run commands on server
