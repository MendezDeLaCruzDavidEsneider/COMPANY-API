<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domain\Entities\Company;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        Company::create(['name' => 'Tech Solutions', 'address' => 'Calle 10', 'phone' => '3001111111']);
        Company::create(['name' => 'Global Corp',    'address' => 'Calle 20', 'phone' => '3002222222']);
        Company::create(['name' => 'Innovate SAS',   'address' => 'Calle 30', 'phone' => '3003333333']);
    }
}
