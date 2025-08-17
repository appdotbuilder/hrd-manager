import React from 'react';
import { Head } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';

interface User {
    id: number;
    name: string;
    email: string;
    role: string;
    department?: string;
    position?: string;
    employee_id?: string;
}

interface Stats {
    totalEmployees: number;
    todayAttendance: number;
    pendingLeaveRequests: number;
    activeJobPostings: number;
}

interface DashboardData {
    recentApplications?: Array<{
        candidate_name: string;
        job_posting?: { title: string };
        status: string;
    }>;
    departmentStats?: Array<{
        department: string;
        count: number;
    }>;
    teamMembers?: Array<{
        id: number;
        name: string;
        position: string;
        status: string;
    }>;
    teamLeaveRequests?: Array<{
        employee: { name: string };
        type: string;
        days_requested: number;
    }>;
    todayAttendance?: {
        clock_in?: string;
        clock_out?: string;
        hours_worked?: number;
    };
    leaveBalance?: {
        annual: number;
        used: number;
        remaining: number;
    };
}

interface Props {
    user: User;
    stats: Stats;
    dashboardData: DashboardData;
    userRole: string;
    [key: string]: unknown;
}

export default function Dashboard({ user, stats, dashboardData, userRole }: Props) {
    const getRoleTitle = (role: string) => {
        switch (role) {
            case 'hr': return 'HR Staff';
            case 'manager': return 'Manager';
            case 'employee': return 'Employee';
            default: return 'User';
        }
    };

    const getRoleEmoji = (role: string) => {
        switch (role) {
            case 'hr': return '👥';
            case 'manager': return '👔';
            case 'employee': return '💼';
            default: return '👤';
        }
    };

    return (
        <AppShell>
            <Head title="HRD Dashboard" />
            
            <div className="p-6">
                {/* Header */}
                <div className="mb-8">
                    <div className="flex items-center gap-3 mb-2">
                        <span className="text-2xl">{getRoleEmoji(user.role)}</span>
                        <h1 className="text-3xl font-bold text-gray-900 dark:text-gray-100">
                            HRD Dashboard
                        </h1>
                    </div>
                    <p className="text-gray-600 dark:text-gray-400">
                        Welcome back, {user.name} ({getRoleTitle(user.role)})
                        {user.department && ` - ${user.department}`}
                    </p>
                </div>

                {/* Stats Cards */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div className="bg-white rounded-xl p-6 shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Total Employees</p>
                                <p className="text-2xl font-bold text-gray-900 dark:text-gray-100">{stats.totalEmployees}</p>
                            </div>
                            <div className="text-3xl">👥</div>
                        </div>
                    </div>

                    <div className="bg-white rounded-xl p-6 shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Today's Attendance</p>
                                <p className="text-2xl font-bold text-gray-900 dark:text-gray-100">{stats.todayAttendance}</p>
                            </div>
                            <div className="text-3xl">⏰</div>
                        </div>
                    </div>

                    <div className="bg-white rounded-xl p-6 shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Pending Leaves</p>
                                <p className="text-2xl font-bold text-gray-900 dark:text-gray-100">{stats.pendingLeaveRequests}</p>
                            </div>
                            <div className="text-3xl">🏖️</div>
                        </div>
                    </div>

                    <div className="bg-white rounded-xl p-6 shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm font-medium text-gray-600 dark:text-gray-400">Active Jobs</p>
                                <p className="text-2xl font-bold text-gray-900 dark:text-gray-100">{stats.activeJobPostings}</p>
                            </div>
                            <div className="text-3xl">🎯</div>
                        </div>
                    </div>
                </div>

                {/* Role-specific content */}
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {userRole === 'hr' && (
                        <>
                            <div className="bg-white rounded-xl p-6 shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                                <h2 className="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">
                                    📝 Recent Applications
                                </h2>
                                <div className="space-y-3">
                                    {dashboardData.recentApplications && dashboardData.recentApplications.length > 0 ? (
                                        dashboardData.recentApplications.map((application, index: number) => (
                                            <div key={index} className="flex justify-between items-center p-3 bg-gray-50 rounded-lg dark:bg-gray-700">
                                                <div>
                                                    <p className="font-medium text-gray-900 dark:text-gray-100">{application.candidate_name}</p>
                                                    <p className="text-sm text-gray-600 dark:text-gray-400">{application.job_posting?.title}</p>
                                                </div>
                                                <span className={`px-2 py-1 rounded-full text-xs font-medium ${
                                                    application.status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                                                    application.status === 'interview' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' :
                                                    'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'
                                                }`}>
                                                    {application.status}
                                                </span>
                                            </div>
                                        ))
                                    ) : (
                                        <p className="text-gray-500 dark:text-gray-400">No recent applications</p>
                                    )}
                                </div>
                            </div>

                            <div className="bg-white rounded-xl p-6 shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                                <h2 className="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">
                                    📊 Department Overview
                                </h2>
                                <div className="space-y-3">
                                    {dashboardData.departmentStats && dashboardData.departmentStats.length > 0 ? (
                                        dashboardData.departmentStats.map((dept, index: number) => (
                                            <div key={index} className="flex justify-between items-center">
                                                <span className="text-gray-900 dark:text-gray-100">{dept.department || 'Unassigned'}</span>
                                                <span className="font-semibold text-blue-600 dark:text-blue-400">{dept.count}</span>
                                            </div>
                                        ))
                                    ) : (
                                        <p className="text-gray-500 dark:text-gray-400">No department data available</p>
                                    )}
                                </div>
                            </div>
                        </>
                    )}

                    {userRole === 'manager' && (
                        <>
                            <div className="bg-white rounded-xl p-6 shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                                <h2 className="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">
                                    👥 Team Members ({dashboardData.teamMembers?.length || 0})
                                </h2>
                                <div className="space-y-3">
                                    {dashboardData.teamMembers && dashboardData.teamMembers.length > 0 ? (
                                        dashboardData.teamMembers.slice(0, 5).map((member, index: number) => (
                                            <div key={index} className="flex justify-between items-center p-3 bg-gray-50 rounded-lg dark:bg-gray-700">
                                                <div>
                                                    <p className="font-medium text-gray-900 dark:text-gray-100">{member.name}</p>
                                                    <p className="text-sm text-gray-600 dark:text-gray-400">{member.position}</p>
                                                </div>
                                                <span className={`px-2 py-1 rounded-full text-xs font-medium ${
                                                    member.status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                                                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
                                                }`}>
                                                    {member.status}
                                                </span>
                                            </div>
                                        ))
                                    ) : (
                                        <p className="text-gray-500 dark:text-gray-400">No team members assigned</p>
                                    )}
                                </div>
                            </div>

                            <div className="bg-white rounded-xl p-6 shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                                <h2 className="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">
                                    🏖️ Pending Leave Requests
                                </h2>
                                <div className="space-y-3">
                                    {dashboardData.teamLeaveRequests && dashboardData.teamLeaveRequests.length > 0 ? (
                                        dashboardData.teamLeaveRequests.map((request, index: number) => (
                                            <div key={index} className="flex justify-between items-center p-3 bg-gray-50 rounded-lg dark:bg-gray-700">
                                                <div>
                                                    <p className="font-medium text-gray-900 dark:text-gray-100">{request.employee.name}</p>
                                                    <p className="text-sm text-gray-600 dark:text-gray-400">
                                                        {request.type} - {request.days_requested} days
                                                    </p>
                                                </div>
                                                <span className="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium dark:bg-yellow-900 dark:text-yellow-200">
                                                    Pending
                                                </span>
                                            </div>
                                        ))
                                    ) : (
                                        <p className="text-gray-500 dark:text-gray-400">No pending leave requests</p>
                                    )}
                                </div>
                            </div>
                        </>
                    )}

                    {userRole === 'employee' && (
                        <>
                            <div className="bg-white rounded-xl p-6 shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                                <h2 className="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">
                                    ⏰ Today's Attendance
                                </h2>
                                {dashboardData.todayAttendance ? (
                                    <div className="space-y-3">
                                        <div className="flex justify-between">
                                            <span className="text-gray-600 dark:text-gray-400">Clock In</span>
                                            <span className="font-medium text-gray-900 dark:text-gray-100">
                                                {dashboardData.todayAttendance.clock_in ? 
                                                    new Date(dashboardData.todayAttendance.clock_in).toLocaleTimeString() : 
                                                    'Not clocked in'
                                                }
                                            </span>
                                        </div>
                                        <div className="flex justify-between">
                                            <span className="text-gray-600 dark:text-gray-400">Clock Out</span>
                                            <span className="font-medium text-gray-900 dark:text-gray-100">
                                                {dashboardData.todayAttendance.clock_out ? 
                                                    new Date(dashboardData.todayAttendance.clock_out).toLocaleTimeString() : 
                                                    'Not clocked out'
                                                }
                                            </span>
                                        </div>
                                        <div className="flex justify-between">
                                            <span className="text-gray-600 dark:text-gray-400">Hours Worked</span>
                                            <span className="font-medium text-gray-900 dark:text-gray-100">
                                                {dashboardData.todayAttendance.hours_worked || '0'} hours
                                            </span>
                                        </div>
                                    </div>
                                ) : (
                                    <div className="text-center py-4">
                                        <p className="text-gray-500 dark:text-gray-400 mb-4">No attendance record for today</p>
                                        <button className="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                            Clock In
                                        </button>
                                    </div>
                                )}
                            </div>

                            <div className="bg-white rounded-xl p-6 shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                                <h2 className="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">
                                    🏖️ Leave Balance
                                </h2>
                                <div className="space-y-4">
                                    <div className="flex justify-between items-center">
                                        <span className="text-gray-600 dark:text-gray-400">Annual Leave</span>
                                        <span className="font-medium text-gray-900 dark:text-gray-100">{dashboardData.leaveBalance?.annual || 25} days</span>
                                    </div>
                                    <div className="flex justify-between items-center">
                                        <span className="text-gray-600 dark:text-gray-400">Used</span>
                                        <span className="font-medium text-red-600 dark:text-red-400">{dashboardData.leaveBalance?.used || 0} days</span>
                                    </div>
                                    <div className="flex justify-between items-center">
                                        <span className="text-gray-600 dark:text-gray-400">Remaining</span>
                                        <span className="font-medium text-green-600 dark:text-green-400">{dashboardData.leaveBalance?.remaining || 25} days</span>
                                    </div>
                                    <div className="w-full bg-gray-200 rounded-full h-2 dark:bg-gray-700">
                                        <div 
                                            className="bg-blue-600 h-2 rounded-full" 
                                            style={{ 
                                                width: `${((dashboardData.leaveBalance?.remaining || 25) / (dashboardData.leaveBalance?.annual || 25)) * 100}%` 
                                            }}
                                        ></div>
                                    </div>
                                </div>
                            </div>
                        </>
                    )}
                </div>

                {/* Quick Actions */}
                <div className="mt-8">
                    <h2 className="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">
                        ⚡ Quick Actions
                    </h2>
                    <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                        {userRole === 'hr' && (
                            <>
                                <button className="bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-xl transition-colors">
                                    <div className="text-2xl mb-2">👤</div>
                                    <div className="text-sm font-medium">Manage Employees</div>
                                </button>
                                <button className="bg-green-600 hover:bg-green-700 text-white p-4 rounded-xl transition-colors">
                                    <div className="text-2xl mb-2">🎯</div>
                                    <div className="text-sm font-medium">Job Postings</div>
                                </button>
                                <button className="bg-purple-600 hover:bg-purple-700 text-white p-4 rounded-xl transition-colors">
                                    <div className="text-2xl mb-2">📊</div>
                                    <div className="text-sm font-medium">Reports</div>
                                </button>
                                <button className="bg-orange-600 hover:bg-orange-700 text-white p-4 rounded-xl transition-colors">
                                    <div className="text-2xl mb-2">📝</div>
                                    <div className="text-sm font-medium">Applications</div>
                                </button>
                            </>
                        )}
                        
                        {userRole === 'manager' && (
                            <>
                                <button className="bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-xl transition-colors">
                                    <div className="text-2xl mb-2">👥</div>
                                    <div className="text-sm font-medium">Team Overview</div>
                                </button>
                                <button className="bg-green-600 hover:bg-green-700 text-white p-4 rounded-xl transition-colors">
                                    <div className="text-2xl mb-2">✅</div>
                                    <div className="text-sm font-medium">Approve Leaves</div>
                                </button>
                                <button className="bg-purple-600 hover:bg-purple-700 text-white p-4 rounded-xl transition-colors">
                                    <div className="text-2xl mb-2">📋</div>
                                    <div className="text-sm font-medium">Performance Reviews</div>
                                </button>
                                <button className="bg-orange-600 hover:bg-orange-700 text-white p-4 rounded-xl transition-colors">
                                    <div className="text-2xl mb-2">⏰</div>
                                    <div className="text-sm font-medium">Attendance Report</div>
                                </button>
                            </>
                        )}
                        
                        {userRole === 'employee' && (
                            <>
                                <button className="bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-xl transition-colors">
                                    <div className="text-2xl mb-2">⏰</div>
                                    <div className="text-sm font-medium">Clock In/Out</div>
                                </button>
                                <button className="bg-green-600 hover:bg-green-700 text-white p-4 rounded-xl transition-colors">
                                    <div className="text-2xl mb-2">🏖️</div>
                                    <div className="text-sm font-medium">Request Leave</div>
                                </button>
                                <button className="bg-purple-600 hover:bg-purple-700 text-white p-4 rounded-xl transition-colors">
                                    <div className="text-2xl mb-2">👤</div>
                                    <div className="text-sm font-medium">My Profile</div>
                                </button>
                                <button className="bg-orange-600 hover:bg-orange-700 text-white p-4 rounded-xl transition-colors">
                                    <div className="text-2xl mb-2">📋</div>
                                    <div className="text-sm font-medium">My Reviews</div>
                                </button>
                            </>
                        )}
                    </div>
                </div>
            </div>
        </AppShell>
    );
}