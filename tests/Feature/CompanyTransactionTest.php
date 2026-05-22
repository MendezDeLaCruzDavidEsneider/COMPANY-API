<?php

namespace Tests\Feature;

use App\Domain\Entities\Company;
use App\Domain\Entities\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_company_and_employees_successfully()
    {
        $payload = [
            'name' => 'Tech Solutions S.A.S',
            'address' => 'Calle 45 # 10-20',
            'phone' => '3001234567',
            'employees' => [
                [
                    'name' => 'Ana',
                    'lastname' => 'Gómez',
                    'email' => 'ana.gomez@tech.com',
                    'position' => 'Desarrolladora',
                    'salary' => 3500000
                ],
                [
                    'name' => 'Carlos',
                    'lastname' => 'Rojas',
                    'email' => 'carlos.rojas@tech.com',
                    'position' => 'Tester',
                    'salary' => 2800000
                ]
            ]
        ];

        $response = $this->postJson('/api/companias/con-empleados', $payload);

        $response->assertStatus(201);
        $response->assertJsonPath('name', 'Tech Solutions S.A.S');
        $this->assertDatabaseHas('companies', ['name' => 'Tech Solutions S.A.S']);
        $this->assertDatabaseHas('employees', ['email' => 'ana.gomez@tech.com']);
        $this->assertDatabaseHas('employees', ['email' => 'carlos.rojas@tech.com']);
    }

    public function test_it_rolls_back_entire_transaction_on_validation_failure()
    {
        // One employee lacks email (validation failure in DTO)
        $payload = [
            'name' => 'Failed Tech S.A.S',
            'address' => 'Calle 123',
            'phone' => '3009999999',
            'employees' => [
                [
                    'name' => 'Ana',
                    'lastname' => 'Gómez',
                    'email' => 'ana.gomez@tech.com',
                    'position' => 'Desarrolladora',
                    'salary' => 3500000
                ],
                [
                    'name' => 'Carlos',
                    'lastname' => 'Rojas',
                    'position' => 'Tester',
                    'salary' => 2800000
                    // Missing email
                ]
            ]
        ];

        $response = $this->postJson('/api/companias/con-empleados', $payload);

        $response->assertStatus(400); // Validation error
        $this->assertDatabaseMissing('companies', ['name' => 'Failed Tech S.A.S']);
        $this->assertDatabaseMissing('employees', ['name' => 'Ana', 'lastname' => 'Gómez']);
    }

    public function test_it_rolls_back_entire_transaction_on_database_exception()
    {
        // First, create an employee with email 'duplicate@tech.com'
        $company = Company::create(['name' => 'Existing Company', 'address' => 'Street 1', 'phone' => '1234567']);
        $company->employees()->create([
            'name' => 'John',
            'lastname' => 'Doe',
            'email' => 'duplicate@tech.com',
            'position' => 'Admin',
            'salary' => 1000000
        ]);

        // Now attempt to create a new company with an employee using the duplicate email.
        // DTO will pass validation, but database insert will throw Unique Constraint violation.
        $payload = [
            'name' => 'Transactional Rollback Co',
            'address' => 'Calle 50',
            'phone' => '3007654321',
            'employees' => [
                [
                    'name' => 'Valid Employee',
                    'lastname' => 'Smith',
                    'email' => 'unique@tech.com',
                    'position' => 'Desarrolladora',
                    'salary' => 3500000
                ],
                [
                    'name' => 'Duplicate Employee',
                    'lastname' => 'Rojas',
                    'email' => 'duplicate@tech.com', // Already exists in DB!
                    'position' => 'Tester',
                    'salary' => 2800000
                ]
            ]
        ];

        $response = $this->postJson('/api/companias/con-empleados', $payload);

        $response->assertStatus(500); // Database constraint error
        $this->assertDatabaseMissing('companies', ['name' => 'Transactional Rollback Co']);
        $this->assertDatabaseMissing('employees', ['email' => 'unique@tech.com']);
    }
}
