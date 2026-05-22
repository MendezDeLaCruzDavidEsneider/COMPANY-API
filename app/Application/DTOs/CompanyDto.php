<?php

namespace App\Application\DTOs;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CompanyDto
{
    public function __construct(
        public string $name,
        public string $address,
        public string $phone,
        public ?array $employees = null
    ) {}

    public static function fromArray(array $data): self
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'employees' => 'nullable|array',
            'employees.*.name' => 'required|string|max:255',
            'employees.*.lastname' => 'required|string|max:255',
            'employees.*.email' => 'required|email|max:255',
            'employees.*.position' => 'required|string|max:255',
            'employees.*.salary' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return new self(
            name: $data['name'],
            address: $data['address'],
            phone: $data['phone'],
            employees: $data['employees'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'address' => $this->address,
            'phone' => $this->phone,
        ];
    }
}
