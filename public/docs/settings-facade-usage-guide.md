# Settings Facade - Usage Guide

## Installation Complete! ✅

Settings facade এখন globally use করতে পারবে পুরো application-এ।

---

## Usage Examples

### 1. Currency & Price Formatting

```php
use Settings;

// Get currency symbol
Settings::currency(); // Returns: ৳

// Format price with currency
Settings::formatPrice(1500); // Returns: ৳1,500.00
Settings::formatPrice(2500.50); // Returns: ৳2,500.50

// Format without currency symbol
Settings::formatPrice(1500, false); // Returns: 1,500.00
```

### 2. Blade Templates

```blade
<!-- Display price -->
<h3>Total: {{ Settings::formatPrice($booking->total_amount) }}</h3>

<!-- Display currency only -->
<span>Currency: {{ Settings::currency() }}</span>

<!-- Salon information -->
<h1>{{ Settings::salonName() }}</h1>
<p>{{ Settings::salonPhone() }}</p>
<p>{{ Settings::salonEmail() }}</p>
<p>{{ Settings::salonAddress() }}</p>

<!-- Logo & Cover -->
@if(Settings::salonLogo())
    <img src="{{ Settings::salonLogo() }}" alt="Logo">
@endif

@if(Settings::salonCover())
    <img src="{{ Settings::salonCover() }}" alt="Cover">
@endif
```

### 3. Controllers

```php
use Settings;

class BookingController extends Controller
{
    public function summary()
    {
        $total = 2500;
        $formattedPrice = Settings::formatPrice($total);
        
        return view('booking.summary', [
            'total' => $total,
            'formatted_price' => $formattedPrice,
        ]);
    }
}
```

### 4. Commission Calculation

```php
use Settings;

// Get commission percentage
$commission = Settings::commissionPercentage(); // Returns: 20.00

// Calculate provider earning (after commission)
$totalAmount = 5000;
$providerEarns = Settings::calculateProviderEarning($totalAmount);
// If commission is 20%, provider gets: ৳4,000.00

// Calculate commission amount only
$commissionAmount = Settings::calculateCommission($totalAmount);
// Returns: ৳1,000.00
```

### 5. Get Any Setting

```php
use Settings;

// Get specific setting
Settings::get('timezone'); // Returns: Asia/Dhaka
Settings::get('opening_time'); // Returns: 09:00:00

// Get with default value
Settings::get('some_key', 'default_value');

// Get all settings
$allSettings = Settings::all();
```

### 6. Update Settings

```php
use Settings;

// Update single setting
Settings::set('currency', '$');
Settings::set('currency_code', 'USD');
Settings::set('decimal_places', '0');

// Update multiple settings
Settings::setMany([
    'currency' => '€',
    'currency_code' => 'EUR',
    'currency_position' => 'after',
]);

// Refresh cache after update
Settings::refresh();
```

### 7. Business Hours

```php
use Settings;

$hours = Settings::businessHours();
// Returns:
// [
//     'opening' => '09:00:00',
//     'closing' => '21:00:00',
//     'timezone' => 'Asia/Dhaka'
// ]
```

---

## Change Currency Globally

### Method 1: Via Code
```php
use Settings;

// Change to USD
Settings::setMany([
    'currency' => '$',
    'currency_code' => 'USD',
    'currency_position' => 'before',
    'decimal_places' => '2',
]);
```

### Method 2: Via Database
Database-এ `settings` table-এ গিয়ে update করো:

```sql
UPDATE settings SET value = '$' WHERE key = 'currency';
UPDATE settings SET value = 'USD' WHERE key = 'currency_code';
```

তারপর cache clear করো:
```bash
php artisan cache:clear
```

অথবা code-এ:
```php
Settings::refresh();
```

---

## Available Methods

| Method | Description | Example |
|--------|-------------|---------|
| `currency()` | Get currency symbol | `৳` |
| `currencyCode()` | Get currency code | `BDT` |
| `formatPrice($amount, $showCurrency = true)` | Format price with currency | `৳1,500.00` |
| `salonName()` | Get salon name | `Salon` |
| `salonLogo()` | Get logo URL | `http://...` |
| `salonCover()` | Get cover URL | `http://...` |
| `salonPhone()` | Get phone number | `+880...` |
| `salonEmail()` | Get email | `info@salon.com` |
| `salonAddress()` | Get address | `Dhaka, Bangladesh` |
| `businessHours()` | Get opening/closing times | `array` |
| `commissionPercentage()` | Get commission % | `20.00` |
| `calculateProviderEarning($amount)` | Calculate after commission | `4000.00` |
| `calculateCommission($amount)` | Calculate commission amount | `1000.00` |
| `get($key, $default = null)` | Get any setting | `mixed` |
| `set($key, $value)` | Update setting | `bool` |
| `setMany($array)` | Update multiple | `bool` |
| `refresh()` | Clear cache | `void` |
| `all()` | Get all settings | `array` |

---

## Configuration Options

Settings stored in database `settings` table with these keys:

### Currency Settings
- `currency` - Symbol (৳, $, €, £, etc.)
- `currency_code` - Code (BDT, USD, EUR, GBP)
- `currency_position` - before/after amount
- `decimal_separator` - . or ,
- `thousand_separator` - , or .
- `decimal_places` - 0, 1, 2, etc.

### Salon Settings
- `salon_name`
- `salon_logo` (path)
- `salon_cover` (path)
- `salon_phone`
- `salon_email`
- `salon_address`

### Business Settings
- `opening_time`
- `closing_time`
- `timezone`
- `commission_percentage`

---

## Important Notes

✅ **Caching**: Settings cached for 1 hour automatically
✅ **Auto-refresh**: Cache refreshes on update via `set()` or `setMany()`
✅ **Global**: Available everywhere using `Settings::method()`
✅ **No need to import**: Facade alias registered in `config/app.php`

---

## Examples in Real Code

### Example 1: Booking Summary Page
```blade
<div class="booking-summary">
    <h2>Booking Summary</h2>
    
    <div class="services">
        @foreach($services as $service)
            <div class="service-item">
                <span>{{ $service->name }}</span>
                <span>{{ Settings::formatPrice($service->price) }}</span>
            </div>
        @endforeach
    </div>
    
    <div class="total">
        <strong>Total:</strong>
        <strong>{{ Settings::formatPrice($booking->total_amount) }}</strong>
    </div>
</div>
```

### Example 2: Provider Earnings Calculation
```php
use Settings;

public function calculateEarnings(Appointment $appointment)
{
    $totalAmount = $appointment->total_amount;
    
    // Provider gets amount after commission
    $providerEarning = Settings::calculateProviderEarning($totalAmount);
    
    // Salon owner gets commission
    $salonCommission = Settings::calculateCommission($totalAmount);
    
    WalletEntry::create([
        'provider_id' => $appointment->provider_id,
        'amount' => $providerEarning,
        'type' => 'earning',
    ]);
    
    return [
        'provider' => Settings::formatPrice($providerEarning),
        'commission' => Settings::formatPrice($salonCommission),
    ];
}
```

### Example 3: Change Currency from Admin Panel
```php
public function updateCurrencySettings(Request $request)
{
    $validated = $request->validate([
        'currency' => 'required|string|max:5',
        'currency_code' => 'required|string|max:3',
        'decimal_places' => 'required|integer|min:0|max:4',
    ]);
    
    Settings::setMany([
        'currency' => $validated['currency'],
        'currency_code' => $validated['currency_code'],
        'decimal_places' => $validated['decimal_places'],
    ]);
    
    return back()->with('success', 'Currency settings updated!');
}
```

---

## Testing

```php
use Settings;

// Test in tinker
php artisan tinker

>>> Settings::currency()
=> "৳"

>>> Settings::formatPrice(1500)
=> "৳1,500.00"

>>> Settings::set('currency', '$')
=> true

>>> Settings::formatPrice(1500)
=> "$1,500.00"
```

---

🎉 **All Done!** এখন তুমি যেকোনো জায়গা থেকে `Settings::formatPrice()` use করতে পারবে!
