<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Interfaces\CompanyRepositoryInterface;
use App\Domain\Entities\Company;

class CompanyRepository implements CompanyRepositoryInterface
{
    public function getAll()
    {
        return Company::with('employees')->get();
    }

    public function getById($id)
    {
        return Company::with('employees')->find($id);
    }

    public function create(array $data)
    {
        return Company::create($data);
    }

    public function update($id, array $data)
    {
        $company = Company::find($id);
        if ($company) {
            $company->update($data);
        }
        return $company;
    }

    public function delete($id)
    {
        return Company::destroy($id);
    }

    public function findByCondition(array $conditions)
    {
        return Company::where($conditions)->with('employees')->get();
    }
}
