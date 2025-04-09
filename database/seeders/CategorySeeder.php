<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Électronique' => [
                'Smartphones', 'Ordinateurs', 'Accessoires'
            ],
            'Vêtements' => [
                'Hommes', 'Femmes', 'Enfants'
            ],
            'Maison' => [
                'Décoration', 'Cuisine', 'Jardin'
            ]
        ];
        
        foreach ($categories as $mainCategory => $subCategories) {
            $parent = Category::firstOrCreate(
                ['name' => $mainCategory],
                [
                    'slug' => Str::slug($mainCategory),
                    'is_active' => true
                ]
            );
            
            foreach ($subCategories as $subCategory) {
                Category::firstOrCreate(
                    ['name' => $subCategory, 'parent_id' => $parent->id],
                    [
                        'slug' => Str::slug($subCategory),
                        'is_active' => true
                    ]
                );
            }
        }
    }
}