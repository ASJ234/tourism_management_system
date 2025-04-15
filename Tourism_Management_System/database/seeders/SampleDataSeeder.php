<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Destination;
use App\Models\Service;
use App\Models\ActivityLog;
use App\Models\User;

class SampleDataSeeder extends Seeder
{
    public function run()
    {
        // Create a sample destination
        $destination = Destination::create([
            'name' => 'Sample Destination',
            'description' => 'A beautiful sample destination for testing.',
            'location' => 'Sample Location',
            'image_url' => 'https://example.com/sample.jpg',
            'created_by' => 1, // Admin user ID
            'is_active' => true,
        ]);

        // Create sample services
        Service::create([
            'name' => 'Sample Service 1',
            'description' => 'A sample service for testing.',
            'price' => 100.00,
            'destination_id' => $destination->destination_id,
            'created_by' => 1, // Admin user ID
            'is_active' => true,
        ]);

        Service::create([
            'name' => 'Sample Service 2',
            'description' => 'Another sample service for testing.',
            'price' => 200.00,
            'destination_id' => $destination->destination_id,
            'created_by' => 1, // Admin user ID
            'is_active' => true,
        ]);

        // Create sample activity logs
        ActivityLog::create([
            'user_id' => 1, // Admin user ID
            'description' => 'Created a new destination',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        ]);

        ActivityLog::create([
            'user_id' => 1, // Admin user ID
            'description' => 'Added new services',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        ]);
    }
} 