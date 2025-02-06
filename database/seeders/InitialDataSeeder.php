<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class InitialDataSeeder extends Seeder
{
    public function run(): void
    {
        // Criar permissões
        $permissions = [
            'view products',
            'create products',
            'edit products',
            'delete products',
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',
            'view service-orders',
            'create service-orders',
            'edit service-orders',
            'delete service-orders',
            'view suppliers',
            'create suppliers',
            'edit suppliers',
            'delete suppliers',
            'view employees',
            'create employees',
            'edit employees',
            'delete employees',
            'view reports',
            'manage stock',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Criar papéis
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $employeeRole = Role::firstOrCreate(['name' => 'employee']);

        // Atribuir permissões aos papéis
        $adminRole->givePermissionTo($permissions);
        
        $managerRole->givePermissionTo([
            'view products',
            'create products',
            'edit products',
            'view categories',
            'create categories',
            'edit categories',
            'view service-orders',
            'create service-orders',
            'edit service-orders',
            'view suppliers',
            'create suppliers',
            'edit suppliers',
            'view employees',
            'view reports',
            'manage stock',
        ]);

        $employeeRole->givePermissionTo([
            'view products',
            'view categories',
            'view service-orders',
            'create service-orders',
            'view suppliers',
            'manage stock',
        ]);

        // Criar usuário admin
        $admin = User::firstOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        // Criar categorias iniciais
        $categories = [
            [
                'name' => 'Adesivos',
                'description' => 'Materiais adesivos para comunicação visual',
                'sku_prefix' => 'ADV',
                'attributes_schema' => json_encode([
                    'material' => ['Vinil', 'Papel'],
                    'acabamento' => ['Brilho', 'Fosco'],
                ]),
            ],
            [
                'name' => 'Banners',
                'description' => 'Banners e faixas para comunicação visual',
                'sku_prefix' => 'BNR',
                'attributes_schema' => json_encode([
                    'material' => ['Lona', 'Tecido'],
                    'acabamento' => ['Ilhós', 'Bastão', 'Sem acabamento'],
                ]),
            ],
            [
                'name' => 'Placas',
                'description' => 'Placas para sinalização e comunicação visual',
                'sku_prefix' => 'PLC',
                'attributes_schema' => json_encode([
                    'material' => ['ACM', 'PVC', 'PS'],
                    'acabamento' => ['Com instalação', 'Sem instalação'],
                ]),
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['name' => $category['name']], $category);
        }
    }
}
