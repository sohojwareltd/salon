# PowerShell script to fix Settings facade usage
# This script replaces all instances of "Settings::" with "App\Facades\Settings::" in blade files

$files = @(
    "resources\views\home.blade.php",
    "resources\views\components\footer.blade.php",
    "resources\views\components\navbar.blade.php",
    "resources\views\components\service-card.blade.php",
    "resources\views\customer\booking-details.blade.php",
    "resources\views\customer\dashboard.blade.php",
    "resources\views\customer\payment-cancel.blade.php",
    "resources\views\customer\payment-success.blade.php",
    "resources\views\customer\payment.blade.php",
    "resources\views\customer\payments\index.blade.php",
    "resources\views\pages\home.blade.php",
    "resources\views\pages\appointments\book.blade.php",
    "resources\views\pages\appointments\thank-you.blade.php",
    "resources\views\pages\dashboard\index.blade.php",
    "resources\views\pages\providers\show-subdomain.blade.php",
    "resources\views\pages\providers\show.blade.php",
    "resources\views\provider\booking-details.blade.php",
    "resources\views\provider\dashboard.blade.php",
    "resources\views\provider\bookings\index.blade.php",
    "resources\views\provider\services\create.blade.php",
    "resources\views\provider\services\edit.blade.php",
    "resources\views\provider\services\index.blade.php",
    "resources\views\provider\wallet\index.blade.php"
)

$count = 0
foreach ($file in $files) {
    if (Test-Path $file) {
        $content = Get-Content $file -Raw
        $newContent = $content -replace '(?<!App\\Facades\\)Settings::', 'App\Facades\Settings::'
        
        if ($content -ne $newContent) {
            Set-Content $file -Value $newContent -NoNewline
            $matches = ([regex]::Matches($newContent, 'Settings::')).Count
            Write-Host "✓ Fixed $file - $matches replacements" -ForegroundColor Green
            $count++
        } else {
            Write-Host "- Skipped $file - Already fixed" -ForegroundColor Yellow
        }
    } else {
        Write-Host "✗ File not found: $file" -ForegroundColor Red
    }
}

Write-Host "`nTotal files processed: $count" -ForegroundColor Cyan
Write-Host "Facade issue fixed! Run 'php artisan config:clear' on server." -ForegroundColor Green
