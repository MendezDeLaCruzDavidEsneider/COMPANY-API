<?php

namespace App\Domain\Entities;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'companies';

    protected $fillable = ['name', 'address', 'phone'];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
