<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tender;
use App\Models\User;
use App\Models\Category;
use Carbon\Carbon;

class TenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some categories
        $categories = Category::where('is_active', true)->take(5)->get();
        
        // Get or create a test buyer user
        $buyer = User::firstOrCreate(
            ['email' => 'buyer@test.com'],
            [
                'name' => 'Test Buyer',
                'password' => bcrypt('password'),
                'role' => 'buyer',
                'is_approved' => true,
                'is_buyer' => true,
            ]
        );

        $sampleTenders = [
            [
                'title' => 'Industrial Equipment Maintenance Services',
                'description' => 'We are seeking qualified contractors to provide comprehensive maintenance services for our industrial equipment including pumps, valves, and electrical systems. The contractor must have experience with heavy machinery and be available for emergency repairs.',
                'budget' => 50000,
                'currency' => 'AUD',
                'deadline' => Carbon::now()->addDays(30),
                'location' => 'California-South',
                'contact_email' => 'procurement@company.com',
                'contact_phone' => '+1-555-0123',
                'requirements' => 'Minimum 5 years experience in industrial maintenance, valid contractor license, insurance coverage of at least $1M'
            ],
            [
                'title' => 'Custom CNC Machining Services',
                'description' => 'Looking for precision CNC machining services for aerospace components. Must meet strict tolerances and quality standards. Project includes 1000+ parts with various materials including aluminum, titanium, and stainless steel.',
                'budget' => 75000,
                'currency' => 'AUD',
                'deadline' => Carbon::now()->addDays(45),
                'location' => 'Colorado',
                'contact_email' => 'engineering@aerospace.com',
                'contact_phone' => '+1-555-0456',
                'requirements' => 'AS9100 certification, ITAR compliance, 5-axis CNC capability, quality management system'
            ],
            [
                'title' => 'Environmental Monitoring Equipment',
                'description' => 'Procurement of advanced environmental monitoring systems for air quality, water quality, and soil contamination detection. Systems must be capable of real-time data transmission and remote monitoring.',
                'budget' => 120000,
                'currency' => 'AUD',
                'deadline' => Carbon::now()->addDays(60),
                'location' => 'Florida',
                'contact_email' => 'environment@corp.com',
                'contact_phone' => '+1-555-0789',
                'requirements' => 'EPA certified equipment, 24/7 technical support, 3-year warranty, installation and training included'
            ],
            [
                'title' => 'Renewable Energy System Installation',
                'description' => 'Comprehensive solar panel installation project for commercial facility. Includes design, permitting, installation, and maintenance services. System capacity of 500kW with battery storage integration.',
                'budget' => 200000,
                'currency' => 'AUD',
                'deadline' => Carbon::now()->addDays(90),
                'location' => 'Oregon',
                'contact_email' => 'sustainability@greenenergy.com',
                'contact_phone' => '+1-555-0321',
                'requirements' => 'NABCEP certification, electrical contractor license, experience with commercial installations, insurance coverage'
            ],
            [
                'title' => 'Medical Device Manufacturing',
                'description' => 'Contract manufacturing services for medical devices including injection molding, assembly, and packaging. Must comply with FDA regulations and ISO 13485 standards. Production volume of 10,000 units per month.',
                'budget' => 300000,
                'currency' => 'AUD',
                'deadline' => Carbon::now()->addDays(120),
                'location' => 'Michigan',
                'contact_email' => 'procurement@medtech.com',
                'contact_phone' => '+1-555-0654',
                'requirements' => 'ISO 13485 certification, FDA registered facility, clean room capabilities, quality management system'
            ]
        ];

        foreach ($sampleTenders as $index => $tenderData) {
            $category = $categories->random();
            
            Tender::create([
                'user_id' => $buyer->id,
                'category_id' => $category->id,
                'title' => $tenderData['title'],
                'description' => $tenderData['description'],
                'budget' => $tenderData['budget'],
                'currency' => $tenderData['currency'],
                'deadline' => $tenderData['deadline'],
                'location' => $tenderData['location'],
                'contact_email' => $tenderData['contact_email'],
                'contact_phone' => $tenderData['contact_phone'],
                'requirements' => $tenderData['requirements'],
                'status' => 'active'
            ]);
        }
    }
}
