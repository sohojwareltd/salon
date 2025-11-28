#!/usr/bin/env php
<?php

/**
 * Single-Vendor Conversion Verification Script
 * Run this to verify the conversion was successful
 */

echo "\n🔍 Verifying Single-Vendor Conversion...\n\n";

$checks = [
    'Files Deleted' => [
        'routes/salon.php',
        'app/Http/Middleware/CheckSalonStatus.php',
        'app/Http/Controllers/SubdomainControllers',
        'app/Http/Controllers/Salon',
        'app/Http/Controllers/SalonController.php',
        'app/Models/Salon.php',
        'app/Models/SalonException.php',
        'app/Observers/SalonObserver.php',
        'database/factories/SalonFactory.php',
        'resources/views/salon',
        'resources/views/salon-subdomain',
    ],
    'Files Created' => [
        'database/migrations/2025_11_27_000001_drop_salon_tables.php',
        'SINGLE_VENDOR_CONVERSION.md',
    ],
];

$passed = 0;
$failed = 0;

// Check deleted files
echo "✓ Checking deleted files...\n";
foreach ($checks['Files Deleted'] as $file) {
    $path = __DIR__ . '/' . $file;
    if (!file_exists($path)) {
        echo "  ✅ Deleted: $file\n";
        $passed++;
    } else {
        echo "  ❌ Still exists: $file\n";
        $failed++;
    }
}

echo "\n✓ Checking created files...\n";
foreach ($checks['Files Created'] as $file) {
    $path = __DIR__ . '/' . $file;
    if (file_exists($path)) {
        echo "  ✅ Created: $file\n";
        $passed++;
    } else {
        echo "  ❌ Missing: $file\n";
        $failed++;
    }
}

// Check key files for salon references
echo "\n✓ Checking for salon references in key files...\n";

$filesToCheck = [
    'app/Models/User.php' => ['salon_id', 'isSalon', 'salon()'],
    'app/Models/Role.php' => ['SALON', 'isSalon'],
    'app/Models/Provider.php' => ['salon_id', 'salon()'],
    'app/Models/Appointment.php' => ['salon_id', 'salon()'],
    'routes/web.php' => ['salon.register', 'salon.dashboard', 'Route::domain'],
];

foreach ($filesToCheck as $file => $patterns) {
    $path = __DIR__ . '/' . $file;
    if (file_exists($path)) {
        $content = file_get_contents($path);
        $found = false;
        foreach ($patterns as $pattern) {
            if (stripos($content, $pattern) !== false) {
                echo "  ⚠️  Found '$pattern' in $file\n";
                $found = true;
            }
        }
        if (!$found) {
            echo "  ✅ Clean: $file\n";
            $passed++;
        } else {
            $failed++;
        }
    }
}

// Summary
echo "\n" . str_repeat('=', 50) . "\n";
echo "Summary: $passed checks passed, $failed checks failed\n";

if ($failed === 0) {
    echo "\n🎉 Conversion verified successfully!\n";
    echo "\nNext steps:\n";
    echo "  1. Run: php artisan migrate\n";
    echo "  2. Run: php artisan migrate:fresh --seed (optional)\n";
    echo "  3. Test the system\n\n";
} else {
    echo "\n⚠️  Some checks failed. Review the output above.\n\n";
}

exit($failed === 0 ? 0 : 1);
