import React from 'react';
import { Head, router } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';

interface User {
    id: number;
    name: string;
    employee_id?: string;
    department?: string;
}

interface Attendance {
    id: number;
    user: User;
    date: string;
    clock_in?: string;
    clock_out?: string;
    hours_worked?: number;
    status: string;
    break_minutes: number;
}

interface PaginationLink {
    url?: string;
    label: string;
    active: boolean;
}

interface PaginationMeta {
    from: number;
    to: number;
    total: number;
    last_page: number;
}

interface Props {
    attendances: {
        data: Attendance[];
        links: PaginationLink[];
        meta: PaginationMeta;
    };
    canManageAll: boolean;
    [key: string]: unknown;
}

export default function AttendanceIndex({ attendances, canManageAll }: Props) {
    const handleClockInOut = () => {
        router.post(route('attendance.store'), {}, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    const getStatusColor = (status: string) => {
        switch (status) {
            case 'present': return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
            case 'absent': return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
            case 'late': return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200';
            case 'half_day': return 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200';
            default: return 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200';
        }
    };

    const formatTime = (timeString?: string) => {
        if (!timeString) return '-';
        return new Date(timeString).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    };

    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleDateString([], { 
            weekday: 'short',
            month: 'short', 
            day: 'numeric',
            year: 'numeric'
        });
    };

    // Check if user can clock in/out today
    const todayAttendance = attendances.data.find(att => 
        att.date === new Date().toISOString().split('T')[0]
    );

    return (
        <AppShell>
            <Head title="Attendance Management" />
            
            <div className="p-6">
                <div className="flex justify-between items-center mb-6">
                    <div>
                        <h1 className="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                            ⏰ Attendance Management
                        </h1>
                        <p className="text-gray-600 dark:text-gray-400">
                            Track and manage employee attendance records
                        </p>
                    </div>

                    {!canManageAll && (
                        <div className="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                            <div className="text-center">
                                <div className="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                    Today: {formatDate(new Date().toISOString())}
                                </div>
                                {todayAttendance ? (
                                    <div className="space-y-2">
                                        <div className="grid grid-cols-2 gap-4 text-sm">
                                            <div>
                                                <span className="text-gray-600 dark:text-gray-400">In:</span>
                                                <span className="ml-1 font-medium">
                                                    {formatTime(todayAttendance.clock_in)}
                                                </span>
                                            </div>
                                            <div>
                                                <span className="text-gray-600 dark:text-gray-400">Out:</span>
                                                <span className="ml-1 font-medium">
                                                    {formatTime(todayAttendance.clock_out)}
                                                </span>
                                            </div>
                                        </div>
                                        {!todayAttendance.clock_out && (
                                            <button
                                                onClick={handleClockInOut}
                                                className="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors"
                                            >
                                                Clock Out
                                            </button>
                                        )}
                                    </div>
                                ) : (
                                    <button
                                        onClick={handleClockInOut}
                                        className="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors"
                                    >
                                        Clock In
                                    </button>
                                )}
                            </div>
                        </div>
                    )}
                </div>

                {/* Attendance Records */}
                <div className="bg-white rounded-xl shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700 overflow-hidden">
                    <div className="overflow-x-auto">
                        <table className="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead className="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    {canManageAll && (
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Employee
                                        </th>
                                    )}
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Clock In
                                    </th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Clock Out
                                    </th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Hours
                                    </th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Break
                                    </th>
                                    <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Status
                                    </th>
                                </tr>
                            </thead>
                            <tbody className="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                {attendances.data.map((attendance) => (
                                    <tr key={attendance.id} className="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        {canManageAll && (
                                            <td className="px-6 py-4 whitespace-nowrap">
                                                <div>
                                                    <div className="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {attendance.user.name}
                                                    </div>
                                                    <div className="text-sm text-gray-500 dark:text-gray-400">
                                                        {attendance.user.employee_id} • {attendance.user.department}
                                                    </div>
                                                </div>
                                            </td>
                                        )}
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <div className="text-sm text-gray-900 dark:text-gray-100">
                                                {formatDate(attendance.date)}
                                            </div>
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {formatTime(attendance.clock_in)}
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {formatTime(attendance.clock_out)}
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {attendance.hours_worked ? `${attendance.hours_worked}h` : '-'}
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {attendance.break_minutes}m
                                        </td>
                                        <td className="px-6 py-4 whitespace-nowrap">
                                            <span className={`inline-flex px-2 py-1 text-xs font-semibold rounded-full ${getStatusColor(attendance.status)}`}>
                                                {attendance.status.replace('_', ' ')}
                                            </span>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>

                    {attendances.data.length === 0 && (
                        <div className="text-center py-12">
                            <div className="text-gray-400 text-6xl mb-4">⏰</div>
                            <h3 className="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                                No attendance records found
                            </h3>
                            <p className="text-gray-500 dark:text-gray-400">
                                Start tracking attendance by clocking in.
                            </p>
                        </div>
                    )}
                </div>

                {/* Pagination */}
                {attendances.meta.last_page > 1 && (
                    <div className="mt-6 flex justify-between items-center">
                        <div className="text-sm text-gray-700 dark:text-gray-300">
                            Showing {attendances.meta.from} to {attendances.meta.to} of {attendances.meta.total} records
                        </div>
                        <div className="flex space-x-1">
                            {attendances.links.map((link, index: number) => (
                                <button
                                    key={index}
                                    onClick={() => link.url && router.get(link.url)}
                                    disabled={!link.url}
                                    className={`px-3 py-2 rounded-lg text-sm font-medium transition-colors ${
                                        link.active
                                            ? 'bg-blue-600 text-white'
                                            : link.url
                                            ? 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 border border-gray-300 dark:border-gray-600'
                                            : 'bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 cursor-not-allowed'
                                    }`}
                                    dangerouslySetInnerHTML={{ __html: link.label }}
                                />
                            ))}
                        </div>
                    </div>
                )}
            </div>
        </AppShell>
    );
}