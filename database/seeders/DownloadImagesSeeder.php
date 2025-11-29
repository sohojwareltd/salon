<?php

namespace Database\Seeders;

use App\Models\Provider;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DownloadImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('📥 Downloading and storing images locally...');

        // Create directories if they don't exist
        Storage::disk('public')->makeDirectory('providers');
        Storage::disk('public')->makeDirectory('services');
        
        $this->command->info('Creating storage directories...');

        // Download provider images
        $this->command->info('Downloading provider images...');
        $providers = Provider::whereNotNull('photo')->get();
        $providerCount = 0;

        foreach ($providers as $provider) {
            if (str_starts_with($provider->photo, 'http')) {
                try {
                    $response = Http::timeout(10)->get($provider->photo);
                    
                    if ($response->successful()) {
                        // Generate filename
                        $filename = 'provider_' . $provider->id . '_' . time() . '.jpg';
                        $path = 'providers/' . $filename;
                        
                        // Save to storage
                        Storage::disk('public')->put($path, $response->body());
                        
                        // Update provider photo path
                        $provider->update(['photo' => $path]);
                        $providerCount++;
                        
                        $this->command->info("✓ Downloaded: {$provider->name}");
                    }
                } catch (\Exception $e) {
                    $this->command->error("✗ Failed to download image for {$provider->name}: " . $e->getMessage());
                }
            }
        }
        
        $this->command->info("✅ Downloaded {$providerCount} provider images");

        // Download service images
        $this->command->info('Downloading service images...');
        $services = Service::whereNotNull('image')->get();
        $serviceCount = 0;

        foreach ($services as $service) {
            if (str_starts_with($service->image, 'http')) {
                try {
                    $response = Http::timeout(10)->get($service->image);
                    
                    if ($response->successful()) {
                        // Generate filename
                        $filename = 'service_' . $service->id . '_' . time() . '.jpg';
                        $path = 'services/' . $filename;
                        
                        // Save to storage
                        Storage::disk('public')->put($path, $response->body());
                        
                        // Update service image path
                        $service->update(['image' => $path]);
                        $serviceCount++;
                        
                        $this->command->info("✓ Downloaded: {$service->name}");
                    }
                } catch (\Exception $e) {
                    $this->command->error("✗ Failed to download image for {$service->name}: " . $e->getMessage());
                }
            }
        }
        
        $this->command->info("✅ Downloaded {$serviceCount} service images");
        
        $this->command->newLine();
        $this->command->info('🎉 All images downloaded and stored locally!');
        $this->command->info("📁 Storage path: storage/app/public/");
        $this->command->info("🔗 Public URL: /storage/providers/ and /storage/services/");
    }
}
