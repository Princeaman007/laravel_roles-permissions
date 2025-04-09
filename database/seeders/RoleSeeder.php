<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Utilisation de firstOrCreate pour éviter les erreurs de duplication
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $productManager = Role::firstOrCreate(['name' => 'Product Manager']);
        $user = Role::firstOrCreate(['name' => 'User']);

        // Permissions pour Admin
        $admin->syncPermissions([
            // Permissions existantes
            'create-user',
            'edit-user',
            'delete-user',
            'create-product',
            'edit-product',
            'delete-product',
            
            // Nouvelles permissions e-commerce
            'view-category', 
            'create-category', 
            'edit-category', 
            'delete-category',
            'view-order', 
            'process-order', 
            'cancel-order',
            'view-customer', 
            'edit-customer',
            'access-reports'
        ]);

        // Permissions pour Product Manager
        $productManager->syncPermissions([
            // Permissions existantes
            'create-product',
            'edit-product',
            'delete-product',
            
            // Nouvelles permissions liées aux produits
            'view-category', 
            'create-category', 
            'edit-category'
        ]);

        // Permissions pour User standard
        $user->syncPermissions([
            'view-product'
        ]);
    }
}