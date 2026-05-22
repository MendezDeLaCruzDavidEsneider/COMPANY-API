<?php

namespace App\Application\DTOs;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class EmployeeDto
{
    public function __construct(
        public string $name,
        public string $lastname,
        public string $email,
        public string $position,
        public float $salary,
        public int $company_id
    ) {}

    public static function fromArray(array $data): self
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'position' => 'required|string|max:255',
            'salary' => 'required|numeric|min:0',
            'company_id' => 'required|integer|exists:companies,id',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return new self(
            name: $data['name'],
            lastname: $data['lastname'],
            email: $data['email'],
            position: $data['position'],
            salary: (float)$data['salary'],
            company_id: (int)$data['company_id']
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'position' => $this->position,
            'salary' => $this->salary,
            'company_id' => $this->company_id,
        ];
    }
}
