<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Limpa cache de permissões
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ==================== PERMISSÕES ====================
        $permissions = [
            // Role
            'view_any_role',
            'view_role',
            'create_role',
            'update_role',
            'delete_role',
            'delete_any_role',
            'force_delete_role',
            'force_delete_any_role',
            'restore_role',
            'restore_any_role',
            'replicate_role',
            'reorder_role',

            // User
            'view_any_user',
            'view_user',
            'create_user',
            'update_user',
            'delete_user',
            'delete_any_user',
            'force_delete_user',
            'force_delete_any_user',
            'restore_user',
            'restore_any_user',
            'replicate_user',
            'reorder_user',

            // Project
            'view_any_project',
            'view_project',
            'create_project',
            'update_project',
            'delete_project',
            'delete_any_project',
            'force_delete_project',
            'force_delete_any_project',
            'restore_project',
            'restore_any_project',
            'replicate_project',
            'reorder_project',

            // Post
            'view_any_post',
            'view_post',
            'create_post',
            'update_post',
            'delete_post',
            'delete_any_post',
            'force_delete_post',
            'force_delete_any_post',
            'restore_post',
            'restore_any_post',
            'replicate_post',
            'reorder_post',

            // Category
            'view_any_category',
            'view_category',
            'create_category',
            'update_category',
            'delete_category',
            'delete_any_category',
            'force_delete_category',
            'force_delete_any_category',
            'restore_category',
            'restore_any_category',
            'replicate_category',
            'reorder_category',

            // Inquiry
            'view_any_inquiry',
            'view_inquiry',
            'create_inquiry',
            'update_inquiry',
            'delete_inquiry',
            'delete_any_inquiry',
            'force_delete_inquiry',
            'force_delete_any_inquiry',
            'restore_inquiry',
            'restore_any_inquiry',
            'replicate_inquiry',
            'reorder_inquiry',

            // Service
            'view_any_service',
            'view_service',
            'create_service',
            'update_service',
            'delete_service',
            'delete_any_service',
            'force_delete_service',
            'force_delete_any_service',
            'restore_service',
            'restore_any_service',
            'replicate_service',
            'reorder_service',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ==================== ROLES ====================
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $editor = Role::firstOrCreate(['name' => 'editor']);
        $author = Role::firstOrCreate(['name' => 'author']);

        // ==================== USUÁRIO ADMIN PADRÃO ====================
        $user = User::firstOrCreate(
            ['email' => 'admin@mktcode.digital'],
            [
                'name' => 'Admin',
                'username' => 'admin',
                'password' => Hash::make('password'),
            ]
        );

        if (! $user->hasRole('super_admin')) {
            $user->assignRole($superAdmin);
        }
    }
}
