<?php

namespace App\Application\Services;

use App\Domain\Interfaces\CompanyRepositoryInterface;
use App\Domain\Interfaces\UnitOfWorkInterface;
use App\Application\DTOs\CompanyDto;
use Illuminate\Support\Facades\Log;

class CompanyService
{
    public function __construct(
        private CompanyRepositoryInterface $companyRepository,
        private UnitOfWorkInterface $unitOfWork
    ) {}

    public function getAll()
    {
        return $this->companyRepository->getAll();
    }

    public function getById($id)
    {
        return $this->companyRepository->getById($id);
    }

    public function create(array $data)
    {
        $dto = CompanyDto::fromArray($data);
        $company = $this->companyRepository->create($dto->toArray());
        Log::info("Creación de una compañía: ID {$company->id} - {$company->name}");
        return $company;
    }

    public function update($id, array $data)
    {
        $company = $this->companyRepository->update($id, $data);
        Log::info("Actualización de una compañía: ID {$id}");
        return $company;
    }

    public function delete($id)
    {
        $this->companyRepository->delete($id);
        Log::info("Eliminación de una compañía: ID {$id}");
        return true;
    }

    public function getEmployees($companyId)
    {
        $company = $this->companyRepository->getById($companyId);
        if (!$company) {
            return null;
        }
        return $company->employees;
    }

    public function createCompanyWithEmployees(array $data)
    {
        $dto = CompanyDto::fromArray($data);

        try {
            $this->unitOfWork->beginTransaction();

            $company = $this->companyRepository->create($dto->toArray());
            Log::info("Creación de una compañía: ID {$company->id} - {$company->name} (dentro de transacción)");

            if (!empty($dto->employees)) {
                foreach ($dto->employees as $emp) {
                    $company->employees()->create([
                        'name'     => $emp['name'],
                        'lastname' => $emp['lastname'],
                        'email'    => $emp['email'],
                        'position' => $emp['position'],
                        'salary'   => $emp['salary'],
                    ]);
                    Log::info("Creación de un empleado: {$emp['name']} {$emp['lastname']} (dentro de transacción)");
                }
            }

            $this->unitOfWork->commit();
            Log::info("Confirmación de una transacción - Compañía ID {$company->id} y empleados creados correctamente");
            
            return $company->load('employees');

        } catch (\Exception $e) {
            $this->unitOfWork->rollback();
            Log::error('Errores de base de datos / inesperados en transaccion: ' . $e->getMessage());
            throw $e;
        }
    }
}
