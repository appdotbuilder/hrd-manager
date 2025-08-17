<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EmployeeController extends Controller
{
    /**
     * Display a listing of employees.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        if (!$user->isHr() && !$user->isManager()) {
            abort(403, 'Unauthorized access');
        }

        $query = User::query()
            ->when($user->isManager() && !$user->isHr(), function ($q) use ($user) {
                return $q->where('manager_id', $user->id)
                        ->orWhere('id', $user->id);
            })
            ->when($request->search, function ($q, $search) {
                return $q->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%")
                          ->orWhere('employee_id', 'like', "%{$search}%")
                          ->orWhere('department', 'like', "%{$search}%");
                });
            })
            ->when($request->department, function ($q, $department) {
                return $q->where('department', $department);
            })
            ->when($request->role, function ($q, $role) {
                return $q->where('role', $role);
            })
            ->active();

        $employees = $query->with('manager')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $departments = User::active()
            ->whereNotNull('department')
            ->distinct()
            ->pluck('department');

        return Inertia::render('employees/index', [
            'employees' => $employees,
            'departments' => $departments,
            'filters' => $request->only(['search', 'department', 'role']),
            'canManageAll' => $user->isHr(),
        ]);
    }

    /**
     * Show the form for creating a new employee.
     */
    public function create(Request $request)
    {
        if (!$request->user()?->isHr()) {
            abort(403, 'Unauthorized');
        }

        $managers = User::managers()->active()->get(['id', 'name']);
        
        return Inertia::render('employees/create', [
            'managers' => $managers,
        ]);
    }

    /**
     * Store a newly created employee in storage.
     */
    public function store(StoreEmployeeRequest $request)
    {
        $employee = User::create($request->validated());

        return redirect()->route('employees.show', $employee)
            ->with('success', 'Employee created successfully.');
    }

    /**
     * Display the specified employee.
     */
    public function show(Request $request, User $employee)
    {
        $user = $request->user();
        
        // Check permissions
        if (!$user->isHr() && $employee->id !== $user->id) {
            if (!$user->isManager() || $employee->manager_id !== $user->id) {
                abort(403, 'Unauthorized access');
            }
        }

        $employee->load(['manager', 'directReports']);
        
        // Get recent attendance
        $recentAttendance = $employee->attendances()
            ->latest('date')
            ->take(7)
            ->get();
        
        // Get recent leave requests
        $recentLeaves = $employee->leaveRequests()
            ->with('approver')
            ->latest()
            ->take(5)
            ->get();

        return Inertia::render('employees/show', [
            'employee' => $employee,
            'recentAttendance' => $recentAttendance,
            'recentLeaves' => $recentLeaves,
            'canEdit' => $user->isHr() || $employee->id === $user->id,
        ]);
    }

    /**
     * Show the form for editing the specified employee.
     */
    public function edit(Request $request, User $employee)
    {
        $user = $request->user();
        if (!$user?->isHr() && $employee->id !== $user?->id) {
            abort(403, 'Unauthorized');
        }

        $managers = User::managers()
            ->active()
            ->where('id', '!=', $employee->id)
            ->get(['id', 'name']);
        
        return Inertia::render('employees/edit', [
            'employee' => $employee,
            'managers' => $managers,
        ]);
    }

    /**
     * Update the specified employee in storage.
     */
    public function update(UpdateEmployeeRequest $request, User $employee)
    {
        $employee->update($request->validated());

        return redirect()->route('employees.show', $employee)
            ->with('success', 'Employee updated successfully.');
    }

    /**
     * Remove the specified employee from storage.
     */
    public function destroy(Request $request, User $employee)
    {
        if (!$request->user()?->isHr()) {
            abort(403, 'Unauthorized');
        }

        $employee->update(['status' => 'terminated']);

        return redirect()->route('employees.index')
            ->with('success', 'Employee status updated to terminated.');
    }
}