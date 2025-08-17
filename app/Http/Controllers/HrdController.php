<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\LeaveRequest;
use App\Models\PerformanceReview;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HrdController extends Controller
{
    /**
     * Display the HRD dashboard.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Common stats for all users
        $stats = [
            'totalEmployees' => User::where('status', 'active')->count(),
            'todayAttendance' => Attendance::whereDate('date', today())->count(),
            'pendingLeaveRequests' => LeaveRequest::where('status', 'pending')->count(),
            'activeJobPostings' => JobPosting::where('status', 'published')->count(),
        ];

        // Role-specific data
        $dashboardData = [];
        
        if ($user->isHr()) {
            $dashboardData = [
                'recentApplications' => JobApplication::with('jobPosting')
                    ->latest('applied_at')
                    ->take(5)
                    ->get(),
                'pendingReviews' => PerformanceReview::with(['employee', 'reviewer'])
                    ->where('status', 'draft')
                    ->count(),
                'departmentStats' => User::where('status', 'active')
                    ->selectRaw('department, COUNT(*) as count')
                    ->groupBy('department')
                    ->whereNotNull('department')
                    ->get(),
                'monthlyHires' => User::whereMonth('hire_date', now()->month)
                    ->whereYear('hire_date', now()->year)
                    ->count(),
            ];
        } elseif ($user->isManager()) {
            $teamMembers = $user->directReports()->where('status', 'active')->get();
            $dashboardData = [
                'teamMembers' => $teamMembers,
                'teamAttendance' => Attendance::whereIn('user_id', $teamMembers->pluck('id'))
                    ->whereDate('date', today())
                    ->count(),
                'teamLeaveRequests' => LeaveRequest::whereIn('employee_id', $teamMembers->pluck('id'))
                    ->where('status', 'pending')
                    ->with('employee')
                    ->get(),
                'reviewsDue' => PerformanceReview::where('reviewer_id', $user->id)
                    ->where('status', 'draft')
                    ->with('employee')
                    ->get(),
            ];
        } else {
            // Employee dashboard
            $dashboardData = [
                'todayAttendance' => $user->attendances()->whereDate('date', today())->first(),
                'leaveBalance' => $this->calculateLeaveBalance($user),
                'recentLeaveRequests' => $user->leaveRequests()
                    ->latest()
                    ->take(5)
                    ->get(),
                'upcomingReview' => $user->performanceReviews()
                    ->where('status', 'draft')
                    ->with('reviewer')
                    ->first(),
                'thisMonthAttendance' => $user->attendances()
                    ->whereYear('date', now()->year)
                    ->whereMonth('date', now()->month)
                    ->count(),
            ];
        }

        return Inertia::render('dashboard', [
            'user' => $user,
            'stats' => $stats,
            'dashboardData' => $dashboardData,
            'userRole' => $user->role,
        ]);
    }

    /**
     * Calculate leave balance for an employee.
     */
    protected function calculateLeaveBalance(User $user): array
    {
        $annualLeave = 25; // Annual leave days
        $usedLeave = $user->leaveRequests()
            ->where('status', 'approved')
            ->whereYear('start_date', now()->year)
            ->sum('days_requested');
        
        return [
            'annual' => $annualLeave,
            'used' => $usedLeave,
            'remaining' => max(0, $annualLeave - $usedLeave),
        ];
    }
}