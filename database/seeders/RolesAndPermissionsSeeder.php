<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Limpa cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Roles sugeridas no relatório
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $editor = Role::firstOrCreate(['name' => 'editor']);
        $author = Role::firstOrCreate(['name' => 'author']);

        // Criar usuário admin inicial se não existir
        $user = User::firstOrCreate(
            ['email' => 'admin@mktcode.digital'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'), // Usando senha padrão para desenvolvimento
            ]
        );

        $user->assignRole($superAdmin);
    }
}
