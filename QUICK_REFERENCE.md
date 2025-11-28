# Quick Reference - Single Vendor System

## 🎯 What Changed

**FROM (Multi-Vendor):**
- 4 roles: admin, salon, provider, customer
- Subdomain system (salon1.saloon.test, salon2.saloon.test)
- Providers belonged to salons
- Salon dashboard
- Salon owners managed providers

**TO (Single-Vendor):**
- 3 roles: admin, provider, customer
- Single domain only
- Independent providers
- No salon dashboard
- Providers self-manage

---

## 🚀 Quick Commands

### Fresh Start
```bash
# Drop everything and start fresh
php artisan migrate:fresh --seed

# Creates:
# - 1 admin (admin@saloon.com / password)
# - 20 providers
# - 50 customers
# - 200 appointments
# - Sample payments & reviews
```

### Update Existing Database
```bash
# Apply migration to remove salon tables
php artisan migrate
```

### Clear Everything
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
composer dump-autoload
```

---

## 📁 Key Routes

### Authentication
- `/login` - Login page
- `/register` - Customer registration

### Dashboards
- `/admin` - Filament admin panel (admin only)
- `/provider-dashboard` - Provider dashboard
- `/customer-dashboard` - Customer dashboard

### Public
- `/` - Homepage
- `/providers` - Browse providers
- `/services` - Browse services
- `/appointments/book` - Book appointment

---

## 👥 Test Users

After seeding:

**Admin:**
- Email: `admin@saloon.com`
- Password: `password`

**All Others:**
- Password: `password`
- Check database for generated emails

---

## 🗑️ What Was Removed

### Files & Directories (29 items)
- All salon controllers, models, views
- Subdomain system (routes, middleware, controllers)
- Salon observer & commands
- 7 salon migrations

### Database
- `salons` table
- `salon_exceptions` table
- `salon_id` from users, providers, appointments, reviews

### Roles
- Removed `salon` role
- Role checks: `isSalon()` removed everywhere

---

## ✅ Verification

Run verification script:
```bash
php verify-conversion.php
```

Should show:
```
🎉 Conversion verified successfully!
Summary: 18 checks passed, 0 checks failed
```

---

## 🔧 Troubleshooting

### "Table 'salons' doesn't exist"
```bash
php artisan migrate
```

### "Class 'App\Models\Salon' not found"
```bash
composer dump-autoload
php artisan config:clear
```

### Cache Issues
```bash
php artisan optimize:clear
composer dump-autoload
```

### Route Not Found
```bash
php artisan route:clear
php artisan route:list | grep dashboard
```

---

## 📝 Common Tasks

### Add New Provider
1. Create user with role_id = 2 (provider)
2. Create provider record
3. Link provider_id in user
4. Add services to provider

### Booking Flow
1. Customer selects provider
2. Chooses service(s)
3. Selects date/time
4. Confirms booking
5. Provider approves
6. Customer pays
7. Customer reviews

---

## 🎨 View Updates Needed

Some views still reference `$provider->salon`. Search and replace:

**Files to update manually:**
- `resources/views/pages/providers/show.blade.php`
- `resources/views/pages/providers/index.blade.php`
- `resources/views/pages/services/index.blade.php`

**Find:**
```php
$provider->salon->name
$provider->salon->hasSubdomain()
```

**Replace with:**
```php
// Remove salon references or replace with provider name
$provider->name
```

---

## 📊 Database Structure

### Users
- No `salon_id` ✅
- Has `provider_id` (if provider)

### Providers
- No `salon_id` ✅
- Independent entities

### Appointments
- No `salon_id` ✅
- Direct provider booking

---

## 🔐 Roles & Permissions

| Role | ID | Routes | Features |
|------|-----|--------|----------|
| Admin | 1 | `/admin` | Filament panel, full access |
| Provider | 2 | `/provider-dashboard` | Manage bookings, earnings |
| Customer | 3 | `/customer-dashboard` | Book, pay, review |

---

## 📞 Need Help?

1. Check `SINGLE_VENDOR_CONVERSION.md` for full details
2. Review Laravel logs: `storage/logs/laravel.log`
3. Run verification: `php verify-conversion.php`
4. Clear caches and retry

---

**Last Updated:** November 27, 2025  
**Version:** 1.0 - Single Vendor
