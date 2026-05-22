<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domain\Entities\Employee;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        Employee::create(['name' => 'Ana',     'lastname' => 'Gomez',    'email' => 'ana@test.com',     'position' => 'Developer', 'salary' => 3500000, 'company_id' => 1]);
        Employee::create(['name' => 'Luis',    'lastname' => 'Perez',    'email' => 'luis@test.com',    'position' => 'Designer',  'salary' => 3000000, 'company_id' => 1]);
        Employee::create(['name' => 'Maria',   'lastname' => 'Torres',   'email' => 'maria@test.com',   'position' => 'Manager',   'salary' => 4500000, 'company_id' => 2]);
        Employee::create(['name' => 'Carlos',  'lastname' => 'Rios',     'email' => 'carlos@test.com',  'position' => 'Developer', 'salary' => 3800000, 'company_id' => 2]);
        Employee::create(['name' => 'Sofia',   'lastname' => 'Vargas',   'email' => 'sofia@test.com',   'position' => 'QA',        'salary' => 2800000, 'company_id' => 3]);
        Employee::create(['name' => 'Jorge',   'lastname' => 'Mendoza',  'email' => 'jorge@test.com',   'position' => 'Architect', 'salary' => 6000000, 'company_id' => 1]);
        Employee::create(['name' => 'Elena',   'lastname' => 'Castro',   'email' => 'elena@test.com',   'position' => 'HR Specialist', 'salary' => 3200000, 'company_id' => 2]);
        Employee::create(['name' => 'Pedro',   'lastname' => 'Ramirez',  'email' => 'pedro@test.com',   'position' => 'Support',   'salary' => 2200000, 'company_id' => 3]);
        Employee::create(['name' => 'Lucia',   'lastname' => 'Ortega',   'email' => 'lucia@test.com',   'position' => 'Product Owner', 'salary' => 5000000, 'company_id' => 1]);
        Employee::create(['name' => 'Ricardo', 'lastname' => 'Salgado',  'email' => 'ricardo@test.com', 'position' => 'Scrum Master', 'salary' => 4200000, 'company_id' => 3]);
    }
}
