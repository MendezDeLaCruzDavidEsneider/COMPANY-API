<?php

namespace App\Http\Controllers\Api;

use App\Application\Services\CompanyService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class CompanyController extends Controller
{
    public function __construct(private CompanyService $service) {}

    public function index()
    {
        try {
            return response()->json($this->service->getAll(), 200);
        } catch (\Exception $e) {
            Log::error('Error inesperado al listar compañías: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }

    public function show($id)
    {
        try {
            $company = $this->service->getById($id);
            if (!$company) {
                return response()->json(['message' => 'Recurso no encontrado'], 404);
            }
            return response()->json($company, 200);
        } catch (\Exception $e) {
            Log::error('Error inesperado al buscar compañía ID ' . $id . ': ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $company = $this->service->create($request->all());
            return response()->json($company, 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 400);
        } catch (\Exception $e) {
            Log::error('Error inesperado al crear compañía: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $company = $this->service->getById($id);
            if (!$company) {
                return response()->json(['message' => 'Recurso no encontrado'], 404);
            }
            
            $updated = $this->service->update($id, $request->all());
            return response()->json($updated, 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 400);
        } catch (\Exception $e) {
            Log::error('Error inesperado al actualizar compañía ID ' . $id . ': ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $company = $this->service->getById($id);
            if (!$company) {
                return response()->json(['message' => 'Recurso no encontrado'], 404);
            }
            
            $this->service->delete($id);
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('Error inesperado al eliminar compañía ID ' . $id . ': ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }

    public function createWithEmployees(Request $request)
    {
        try {
            $result = $this->service->createCompanyWithEmployees($request->all());
            return response()->json($result, 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 400);
        } catch (\Exception $e) {
            Log::error('Error inesperado en transacción: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getEmployees($id)
    {
        try {
            $employees = $this->service->getEmployees($id);
            if ($employees === null) {
                return response()->json(['message' => 'Recurso no encontrado'], 404);
            }
            return response()->json($employees, 200);
        } catch (\Exception $e) {
            Log::error('Error inesperado al listar empleados de compañía ID ' . $id . ': ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }
}
