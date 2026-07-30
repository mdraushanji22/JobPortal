<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employer;
use App\Models\Candidate;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'John Employer',
            'email' => 'employer@example.com',
            'password' => bcrypt('password'),
            'role' => 'employer',
            'is_active' => true,
        ])->employer()->create([
            'company_name' => 'Tech Corp Inc.',
            'industry' => 'Technology',
            'website' => 'https://techcorp.com',
            'phone' => '+1234567890',
            'address' => '123 Main St, New York, NY',
            'description' => 'A leading technology company.',
            'is_verified' => true,
        ]);

        User::create([
            'name' => 'Jane Candidate',
            'email' => 'candidate@example.com',
            'password' => bcrypt('password'),
            'role' => 'candidate',
            'is_active' => true,
        ])->candidate()->create([
            'phone' => '+0987654321',
            'skills' => 'PHP, Laravel, JavaScript, MySQL',
            'education' => 'BS in Computer Science',
            'experience' => '5 years of web development',
            'bio' => 'Passionate full-stack developer.',
        ]);
    }
}
