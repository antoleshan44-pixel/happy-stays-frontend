<?php
// File: database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Property;
use App\Models\Booking;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('Starting database seeding...');
        
        // Clear existing data
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Booking::truncate();
        Property::truncate();
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@eserianhomes.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '254700000000',
        ]);

        // Create owner user
        $owner = User::create([
            'name' => 'John Owner',
            'email' => 'owner@eserianhomes.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'phone' => '254700000001',
        ]);

        // Create customer user
        $customer = User::create([
            'name' => 'Jane Customer',
            'email' => 'customer@eserianhomes.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '254708374149',
        ]);

        $this->command->info('Users created successfully!');

        // Create sample property 1 - Approved and Active
        $property1 = Property::create([
            'owner_id' => $owner->id,
            'title' => 'Beautiful Beach Villa',
            'location' => 'Diani Beach, Mombasa',
            'description' => 'A stunning beachfront villa with amazing ocean views. Perfect for family vacations and romantic getaways.',
            'property_type' => 'Villa',
            'bedrooms' => 3,
            'bathrooms' => 2,
            'price_per_night' => 15000.00,
            'amenities' => 'WiFi,Pool,Parking,Air Conditioning,Kitchen,Sea View',
            'status' => 'approved',
            'admin_status' => 'active',
            'archived_data' => null,
        ]);

        $this->command->info('Property 1 created: ' . $property1->title);

        // Create sample property 2 - Approved and Active
        $property2 = Property::create([
            'owner_id' => $owner->id,
            'title' => 'Cozy City Apartment',
            'location' => 'Westlands, Nairobi',
            'description' => 'Modern apartment in the heart of Nairobi. Close to restaurants, shopping malls, and nightlife.',
            'property_type' => 'Apartment',
            'bedrooms' => 2,
            'bathrooms' => 2,
            'price_per_night' => 8000.00,
            'amenities' => 'WiFi,Parking,Security,Gym,Swimming Pool',
            'status' => 'approved',
            'admin_status' => 'active',
            'archived_data' => null,
        ]);

        $this->command->info('Property 2 created: ' . $property2->title);

        // Create sample property 3 - Pending Approval
        $property3 = Property::create([
            'owner_id' => $owner->id,
            'title' => 'Mountain View Cottage',
            'location' => 'Limuru, Kiambu',
            'description' => 'Peaceful cottage with breathtaking mountain views. Perfect for nature lovers.',
            'property_type' => 'Cottage',
            'bedrooms' => 2,
            'bathrooms' => 1,
            'price_per_night' => 6000.00,
            'amenities' => 'WiFi,Fireplace,Garden,Parking,Fire Pit',
            'status' => 'pending',
            'admin_status' => 'active',
            'archived_data' => null,
        ]);

        $this->command->info('Property 3 created: ' . $property3->title);

        // Create sample property 4 - Another approved property
        $property4 = Property::create([
            'owner_id' => $owner->id,
            'title' => 'Luxury Executive Suite',
            'location' => 'Kilimani, Nairobi',
            'description' => 'Luxurious executive suite with modern amenities and 24/7 security.',
            'property_type' => 'Apartment',
            'bedrooms' => 3,
            'bathrooms' => 3,
            'price_per_night' => 25000.00,
            'amenities' => 'WiFi,Parking,Security,Gym,Pool,Spa,Conference Room',
            'status' => 'approved',
            'admin_status' => 'active',
            'archived_data' => null,
        ]);

        $this->command->info('Property 4 created: ' . $property4->title);

        // Create sample property 5 - Suspended (for admin demo)
        $property5 = Property::create([
            'owner_id' => $owner->id,
            'title' => 'Budget Friendly Studio',
            'location' => 'CBD, Nairobi',
            'description' => 'Affordable studio apartment in the city center.',
            'property_type' => 'House',
            'bedrooms' => 1,
            'bathrooms' => 1,
            'price_per_night' => 3000.00,
            'amenities' => 'WiFi,Hot Water,Security',
            'status' => 'approved',
            'admin_status' => 'suspended',
            'archived_data' => null,
        ]);

        $this->command->info('Property 5 created: ' . $property5->title);

        // Create bookings
        // Booking 1 - Confirmed
        $booking1 = Booking::create([
            'customer_id' => $customer->id,
            'property_id' => $property1->id,
            'check_in_date' => now()->addDays(30),
            'check_out_date' => now()->addDays(33),
            'guests' => 4,
            'total_price' => 45000.00,
            'status' => 'confirmed',
        ]);

        $this->command->info('Booking 1 created for: ' . $property1->title);

        // Booking 2 - Pending
        $booking2 = Booking::create([
            'customer_id' => $customer->id,
            'property_id' => $property2->id,
            'check_in_date' => now()->addDays(45),
            'check_out_date' => now()->addDays(47),
            'guests' => 2,
            'total_price' => 16000.00,
            'status' => 'pending',
        ]);

        $this->command->info('Booking 2 created for: ' . $property2->title);

        // Booking 3 - Completed (past booking)
        $booking3 = Booking::create([
            'customer_id' => $customer->id,
            'property_id' => $property4->id,
            'check_in_date' => now()->subDays(10),
            'check_out_date' => now()->subDays(7),
            'guests' => 2,
            'total_price' => 75000.00,
            'status' => 'completed',
        ]);

        $this->command->info('Booking 3 created for: ' . $property4->title);

        $this->command->info('====================================');
        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('====================================');
        $this->command->info('');
        $this->command->info('📋 Demo Accounts:');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('👑 Admin:    admin@eserianhomes.com');
        $this->command->info('👤 Owner:    owner@eserianhomes.com');
        $this->command->info('👥 Customer: customer@eserianhomes.com');
        $this->command->info('🔑 Password: password (for all accounts)');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('');
        $this->command->info('🏠 Properties Created:');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('✅ Beautiful Beach Villa (Approved/Active) - KES 15,000/night');
        $this->command->info('✅ Cozy City Apartment (Approved/Active) - KES 8,000/night');
        $this->command->info('⏳ Mountain View Cottage (Pending Approval) - KES 6,000/night');
        $this->command->info('✅ Luxury Executive Suite (Approved/Active) - KES 25,000/night');
        $this->command->info('⚠️  Budget Friendly Studio (Suspended) - KES 3,000/night');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info('');
        $this->command->info('📅 Bookings Created: 3 bookings');
        $this->command->info('====================================');
    }
}