<?php

namespace App\Domain\Entities;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'employees';

    protected $fillable = [
        'name', 'lastname', 'email',
        'position', 'salary', 'company_id'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
