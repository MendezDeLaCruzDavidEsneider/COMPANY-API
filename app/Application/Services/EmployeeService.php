<?php

namespace App\Application\Services;

use App\Domain\Interfaces\EmployeeRepositoryInterface;
use App\Domain\Interfaces\UnitOfWorkInterface;
use App\Application\DTOs\EmployeeDto;
use Illuminate\Support\Facades\Log;

class EmployeeService
{
    public function __construct(
        private EmployeeRepositoryInterface $employeeRepository,
        private UnitOfWorkInterface $unitOfWork
    ) {}

    public function getAll()
    {
        return $this->employeeRepository->getAll();
    }

    public function getById($id)
    {
        return $this->employeeRepository->getById($id);
    }

    public function create(array $data)
    {
        $dto = EmployeeDto::fromArray($data);
        $employee = $this->employeeRepository->create($dto->toArray());
        Log::info("Creación de un empleado: ID {$employee->id} - {$employee->name} {$employee->lastname}");
        return $employee;
    }

    public function update($id, array $data)
    {
        $employee = $this->employeeRepository->update($id, $data);
        Log::info("Actualización de un empleado: ID {$id}");
        return $employee;
    }

    public function delete($id)
    {
        $this->employeeRepository->delete($id);
        Log::info("Eliminación de un empleado: ID {$id}");
        return true;
    }
}
