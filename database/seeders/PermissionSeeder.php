<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Permissions existantes
            'create-role', 'edit-role', 'delete-role',
            'create-user', 'edit-user', 'delete-user',
            'view-product', 'create-product', 'edit-product', 'delete-product',
            
            // Nouvelles permissions e-commerce
            'view-category', 'create-category', 'edit-category', 'delete-category',
            'view-order', 'process-order', 'cancel-order',
            'view-customer', 'edit-customer',
            'access-reports'
        ];
 
        // Looping and Inserting Array's Permissions into Permission Table
        foreach ($permissions as $permission) {
            // Vérifier si la permission existe déjà pour éviter les doublons
            if (!Permission::where('name', $permission)->exists()) {
                Permission::create(['name' => $permission]);
            }
        }
    }
}