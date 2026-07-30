<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Information Technology',
            'Healthcare',
            'Finance',
            'Education',
            'Marketing',
            'Sales',
            'Engineering',
            'Design',
            'Human Resources',
            'Legal',
        ];

        foreach ($categories as $name) {
            \App\Models\Category::create(['name' => $name]);
        }
    }
}
