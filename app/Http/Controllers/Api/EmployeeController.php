<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Employee::where('is_active', true)->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info('Intentando registrar trabajador', ['request_data' => $request->all()]);

        $data = $request->validate([
            'full_name' => 'required|string|min:3',
            'role' => 'required|string|min:3',
            'project' => 'required|string|min:3',
        ]);

        $employee = Employee::create($data);

        return response()->json($employee, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(Employee::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $data = $request->validate([
            'full_name' => 'required|string|min:3',
            'role' => 'required|string|min:3',
            'project' => 'required|string|min:3',
        ]);

        $employee->update($data);

        return response()->json([
            'message' => 'Trabajador actualizado con éxito',
            'data' => $employee
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $employee = Employee::findOrFail($id);
        $employee->update(['is_active' => false]);
        return response()->json(['message' => 'Empleado desactivado correctamente']);
    }
}
