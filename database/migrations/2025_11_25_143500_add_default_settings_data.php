<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert default settings if they don't exist
        $settings = [
            ['key' => 'salon_name', 'value' => 'Salon', 'type' => 'string', 'group' => 'general'],
            ['key' => 'salon_phone', 'value' => '+880-1234-567890', 'type' => 'string', 'group' => 'general'],
            ['key' => 'salon_email', 'value' => 'info@salon.com', 'type' => 'string', 'group' => 'general'],
            ['key' => 'salon_address', 'value' => 'Dhaka, Bangladesh', 'type' => 'string', 'group' => 'general'],
            ['key' => 'currency', 'value' => '৳', 'type' => 'string', 'group' => 'currency'],
            ['key' => 'currency_code', 'value' => 'BDT', 'type' => 'string', 'group' => 'currency'],
            ['key' => 'currency_position', 'value' => 'before', 'type' => 'string', 'group' => 'currency'],
            ['key' => 'decimal_separator', 'value' => '.', 'type' => 'string', 'group' => 'currency'],
            ['key' => 'thousand_separator', 'value' => ',', 'type' => 'string', 'group' => 'currency'],
            ['key' => 'decimal_places', 'value' => '2', 'type' => 'integer', 'group' => 'currency'],
            ['key' => 'commission_percentage', 'value' => '20.00', 'type' => 'string', 'group' => 'finance'],
            ['key' => 'opening_time', 'value' => '09:00:00', 'type' => 'string', 'group' => 'business_hours'],
            ['key' => 'closing_time', 'value' => '21:00:00', 'type' => 'string', 'group' => 'business_hours'],
            ['key' => 'timezone', 'value' => 'Asia/Dhaka', 'type' => 'string', 'group' => 'general'],
        ];

        foreach ($settings as $setting) {
            $exists = DB::table('settings')->where('key', $setting['key'])->exists();
            
            if (!$exists) {
                DB::table('settings')->insert([
                    'key' => $setting['key'],
                    'value' => $setting['value'],
                    'type' => $setting['type'],
                    'group' => $setting['group'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove only the settings we added
        $keys = [
            'salon_name', 'salon_phone', 'salon_email', 'salon_address',
            'currency', 'currency_code', 'currency_position',
            'decimal_separator', 'thousand_separator', 'decimal_places',
            'commission_percentage', 'opening_time', 'closing_time', 'timezone'
        ];
        
        DB::table('settings')->whereIn('key', $keys)->delete();
    }
};
