<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'position' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'hire_date' => 'required|date',
            'document_id' => 'required|string|max:255|unique:employees',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Criar usuário
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            // Criar funcionário
            Employee::create([
                'user_id' => $user->id,
                'position' => $validated['position'],
                'department' => $validated['department'],
                'hire_date' => $validated['hire_date'],
                'document_id' => $validated['document_id'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'emergency_contact' => $validated['emergency_contact'],
                'notes' => $validated['notes'],
            ]);

            DB::commit();
            return redirect()->route('employees.index')->with('success', 'Funcionário cadastrado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages(['error' => 'Erro ao cadastrar funcionário. ' . $e->getMessage()]);
        }
    }

    public function show(Employee $employee)
    {
        $employee->load(['user', 'serviceOrders', 'stockMovements']);
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $employee->user_id,
            'position' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'hire_date' => 'required|date',
            'document_id' => 'required|string|max:255|unique:employees,document_id,' . $employee->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Atualizar usuário
            $employee->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            // Atualizar funcionário
            $employee->update([
                'position' => $validated['position'],
                'department' => $validated['department'],
                'hire_date' => $validated['hire_date'],
                'document_id' => $validated['document_id'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'emergency_contact' => $validated['emergency_contact'],
                'notes' => $validated['notes'],
            ]);

            DB::commit();
            return redirect()->route('employees.index')->with('success', 'Funcionário atualizado com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages(['error' => 'Erro ao atualizar funcionário. ' . $e->getMessage()]);
        }
    }

    public function destroy(Employee $employee)
    {
        if ($employee->serviceOrders()->exists()) {
            return back()->with('error', 'Não é possível excluir um funcionário que possui ordens de serviço.');
        }

        if ($employee->stockMovements()->exists()) {
            return back()->with('error', 'Não é possível excluir um funcionário que possui movimentações de estoque.');
        }

        DB::beginTransaction();
        try {
            $employee->user->delete(); // Isso também excluirá o funcionário devido à relação onDelete('cascade')
            DB::commit();
            return redirect()->route('employees.index')->with('success', 'Funcionário excluído com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages(['error' => 'Erro ao excluir funcionário. ' . $e->getMessage()]);
        }
    }
}
