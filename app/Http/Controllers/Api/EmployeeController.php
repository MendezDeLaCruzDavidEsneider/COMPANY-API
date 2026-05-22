<?php

namespace App\Http\Controllers\Api;

use App\Application\Services\EmployeeService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    public function __construct(private EmployeeService $service) {}

    public function index()
    {
        try {
            return response()->json($this->service->getAll(), 200);
        } catch (\Exception $e) {
            Log::error('Error inesperado al listar empleados: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }

    public function show($id)
    {
        try {
            $employee = $this->service->getById($id);
            if (!$employee) {
                return response()->json(['message' => 'Recurso no encontrado'], 404);
            }
            return response()->json($employee, 200);
        } catch (\Exception $e) {
            Log::error('Error inesperado al buscar empleado ID ' . $id . ': ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $employee = $this->service->create($request->all());
            return response()->json($employee, 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 400);
        } catch (\Exception $e) {
            Log::error('Error inesperado al crear empleado: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $employee = $this->service->getById($id);
            if (!$employee) {
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
            Log::error('Error inesperado al actualizar empleado ID ' . $id . ': ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $employee = $this->service->getById($id);
            if (!$employee) {
                return response()->json(['message' => 'Recurso no encontrado'], 404);
            }

            $this->service->delete($id);
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('Error inesperado al eliminar empleado ID ' . $id . ': ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }
}
