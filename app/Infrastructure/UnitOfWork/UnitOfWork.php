<?php

namespace App\Infrastructure\UnitOfWork;

use App\Domain\Interfaces\UnitOfWorkInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UnitOfWork implements UnitOfWorkInterface
{
    public function beginTransaction(): void
    {
        DB::beginTransaction();
        Log::info('Inicio de una transacción');
    }

    public function commit(): void
    {
        DB::commit();
        Log::info('Confirmación de una transacción');
    }

    public function rollback(): void
    {
        DB::rollBack();
        Log::warning('Rollback de una transacción');
    }
}
