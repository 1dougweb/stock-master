<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        $employees = [
            [
                'user' => [
                    'name' => 'João Silva',
                    'email' => 'joao.silva@empresa.com',
                    'password' => Hash::make('password'),
                ],
                'position' => 'Técnico',
                'department' => 'Manutenção',
                'phone' => '(11) 98765-4321',
            ],
            [
                'user' => [
                    'name' => 'Maria Santos',
                    'email' => 'maria.santos@empresa.com',
                    'password' => Hash::make('password'),
                ],
                'position' => 'Supervisora',
                'department' => 'Produção',
                'phone' => '(11) 91234-5678',
            ],
            [
                'user' => [
                    'name' => 'Pedro Oliveira',
                    'email' => 'pedro.oliveira@empresa.com',
                    'password' => Hash::make('password'),
                ],
                'position' => 'Operador',
                'department' => 'Produção',
                'phone' => '(11) 94567-8901',
            ],
        ];

        foreach ($employees as $employeeData) {
            $user = User::create($employeeData['user']);

            $employee = new Employee();
            $employee->user_id = $user->id;
            $employee->position = $employeeData['position'];
            $employee->department = $employeeData['department'];
            $employee->phone = $employeeData['phone'];
            $employee->save();
        }
    }
}
