<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AttendanceController extends Controller
{
    /**
     * Display a listing of attendance records.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        $query = Attendance::with('user')
            ->when(!$user->isHr() && !$user->isManager(), function ($q) use ($user) {
                return $q->where('user_id', $user->id);
            })
            ->when($user->isManager() && !$user->isHr(), function ($q) use ($user) {
                $teamMemberIds = $user->directReports()->pluck('id')->push($user->id);
                return $q->whereIn('user_id', $teamMemberIds);
            });

        $attendances = $query->latest('date')
            ->paginate(15);

        return Inertia::render('attendance/index', [
            'attendances' => $attendances,
            'canManageAll' => $user->isHr(),
        ]);
    }

    /**
     * Store a new attendance record (clock in/out).
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $today = Carbon::today();
        
        $attendance = Attendance::firstOrCreate(
            [
                'user_id' => $user->id,
                'date' => $today,
            ],
            [
                'status' => 'present',
            ]
        );

        if (!$attendance->clock_in) {
            // Clock in
            $attendance->update([
                'clock_in' => now(),
                'status' => 'present',
            ]);
            
            $message = 'Clocked in successfully';
        } elseif (!$attendance->clock_out) {
            // Clock out
            $clockOut = now();
            $hoursWorked = $clockOut->diffInMinutes($attendance->clock_in) / 60;
            $hoursWorked = max(0, $hoursWorked - ($attendance->break_minutes / 60));
            
            $attendance->update([
                'clock_out' => $clockOut,
                'hours_worked' => round($hoursWorked, 2),
            ]);
            
            $message = 'Clocked out successfully';
        } else {
            return back()->with('error', 'You have already completed your attendance for today');
        }

        return back()->with('success', $message);
    }

    /**
     * Show attendance for a specific user.
     */
    public function show(Request $request, $userId = null)
    {
        $user = $request->user();
        $targetUserId = $userId ?: $user->id;
        
        // Check permissions
        if ($targetUserId !== $user->id && !$user->isHr() && !$user->isManager()) {
            abort(403, 'Unauthorized access to attendance records');
        }
        
        if ($user->isManager() && !$user->isHr()) {
            $teamMemberIds = $user->directReports()->pluck('id')->push($user->id);
            if (!$teamMemberIds->contains($targetUserId)) {
                abort(403, 'Unauthorized access to attendance records');
            }
        }

        $attendances = Attendance::where('user_id', $targetUserId)
            ->with('user')
            ->latest('date')
            ->paginate(20);

        return Inertia::render('attendance/show', [
            'attendances' => $attendances,
            'targetUser' => $attendances->first()?->user,
        ]);
    }
}