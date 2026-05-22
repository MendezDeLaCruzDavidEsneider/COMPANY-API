<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Interfaces\EmployeeRepositoryInterface;
use App\Domain\Entities\Employee;

class EmployeeRepository implements EmployeeRepositoryInterface
{
    public function getAll()
    {
        return Employee::with('company')->get();
    }

    public function getById($id)
    {
        return Employee::with('company')->find($id);
    }

    public function create(array $data)
    {
        return Employee::create($data);
    }

    public function update($id, array $data)
    {
        $employee = Employee::find($id);
        if ($employee) {
            $employee->update($data);
        }
        return $employee;
    }

    public function delete($id)
    {
        return Employee::destroy($id);
    }

    public function findByCondition(array $conditions)
    {
        return Employee::where($conditions)->with('company')->get();
    }
}
