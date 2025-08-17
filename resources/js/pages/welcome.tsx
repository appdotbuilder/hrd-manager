import { type SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';

export default function Welcome() {
    const { auth } = usePage<SharedData>().props;

    return (
        <>
            <Head title="HRD Management System">
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
            </Head>
            <div className="flex min-h-screen flex-col items-center bg-gradient-to-br from-blue-50 to-indigo-100 p-6 text-gray-900 lg:justify-center lg:p-8 dark:from-gray-900 dark:to-gray-800 dark:text-gray-100">
                <header className="mb-6 w-full max-w-[335px] text-sm not-has-[nav]:hidden lg:max-w-6xl">
                    <nav className="flex items-center justify-end gap-4">
                        {auth.user ? (
                            <Link
                                href={route('dashboard')}
                                className="inline-block rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-blue-700 transition-colors"
                            >
                                Go to Dashboard
                            </Link>
                        ) : (
                            <>
                                <Link
                                    href={route('login')}
                                    className="inline-block rounded-lg border border-blue-600 px-6 py-2.5 text-sm font-medium text-blue-600 hover:bg-blue-50 transition-colors dark:border-blue-400 dark:text-blue-400 dark:hover:bg-gray-800"
                                >
                                    Log in
                                </Link>
                                <Link
                                    href={route('register')}
                                    className="inline-block rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white hover:bg-blue-700 transition-colors"
                                >
                                    Register
                                </Link>
                            </>
                        )}
                    </nav>
                </header>
                
                <div className="flex w-full items-center justify-center opacity-100 transition-opacity duration-750 lg:grow starting:opacity-0">
                    <main className="flex w-full max-w-6xl flex-col lg:flex-row gap-12 items-center">
                        {/* Left side - Content */}
                        <div className="flex-1 text-center lg:text-left">
                            <div className="mb-8">
                                <h1 className="text-4xl lg:text-6xl font-bold mb-4 text-gray-900 dark:text-gray-100">
                                    👥 HRD Management
                                </h1>
                                <p className="text-xl lg:text-2xl text-gray-600 dark:text-gray-300 mb-8">
                                    Complete human resource development solution for modern organizations
                                </p>
                            </div>

                            <div className="grid gap-6 md:grid-cols-2 mb-12">
                                <div className="bg-white rounded-xl p-6 shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                                    <div className="text-3xl mb-3">👤</div>
                                    <h3 className="font-semibold text-lg mb-2 text-gray-900 dark:text-gray-100">Employee Management</h3>
                                    <p className="text-gray-600 dark:text-gray-400 text-sm">
                                        Comprehensive employee profiles, role management, and organizational structure
                                    </p>
                                </div>
                                <div className="bg-white rounded-xl p-6 shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                                    <div className="text-3xl mb-3">⏰</div>
                                    <h3 className="font-semibold text-lg mb-2 text-gray-900 dark:text-gray-100">Attendance Tracking</h3>
                                    <p className="text-gray-600 dark:text-gray-400 text-sm">
                                        Digital clock in/out system with real-time tracking and detailed reports
                                    </p>
                                </div>
                                <div className="bg-white rounded-xl p-6 shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                                    <div className="text-3xl mb-3">📝</div>
                                    <h3 className="font-semibold text-lg mb-2 text-gray-900 dark:text-gray-100">Performance Reviews</h3>
                                    <p className="text-gray-600 dark:text-gray-400 text-sm">
                                        Structured performance evaluations and goal tracking for career development
                                    </p>
                                </div>
                                <div className="bg-white rounded-xl p-6 shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                                    <div className="text-3xl mb-3">🏖️</div>
                                    <h3 className="font-semibold text-lg mb-2 text-gray-900 dark:text-gray-100">Leave Management</h3>
                                    <p className="text-gray-600 dark:text-gray-400 text-sm">
                                        Streamlined leave requests with approval workflows and balance tracking
                                    </p>
                                </div>
                                <div className="bg-white rounded-xl p-6 shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                                    <div className="text-3xl mb-3">🎯</div>
                                    <h3 className="font-semibold text-lg mb-2 text-gray-900 dark:text-gray-100">Recruitment</h3>
                                    <p className="text-gray-600 dark:text-gray-400 text-sm">
                                        Job posting management, application tracking, and candidate evaluation
                                    </p>
                                </div>
                                <div className="bg-white rounded-xl p-6 shadow-sm border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                                    <div className="text-3xl mb-3">📊</div>
                                    <h3 className="font-semibold text-lg mb-2 text-gray-900 dark:text-gray-100">Analytics & Reports</h3>
                                    <p className="text-gray-600 dark:text-gray-400 text-sm">
                                        Comprehensive reporting with insights for data-driven HR decisions
                                    </p>
                                </div>
                            </div>

                            {!auth.user && (
                                <div className="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                                    <Link
                                        href={route('register')}
                                        className="inline-block rounded-lg bg-blue-600 px-8 py-3 text-base font-medium text-white hover:bg-blue-700 transition-colors"
                                    >
                                        Get Started
                                    </Link>
                                    <Link
                                        href={route('login')}
                                        className="inline-block rounded-lg border border-gray-300 px-8 py-3 text-base font-medium text-gray-700 hover:bg-gray-50 transition-colors dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800"
                                    >
                                        Sign In
                                    </Link>
                                </div>
                            )}
                        </div>

                        {/* Right side - Visual */}
                        <div className="flex-1 max-w-lg">
                            <div className="bg-white rounded-2xl shadow-xl p-8 dark:bg-gray-800">
                                <div className="space-y-6">
                                    <div className="text-center">
                                        <div className="text-6xl mb-4">🏢</div>
                                        <h3 className="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                            Enterprise Ready
                                        </h3>
                                        <p className="text-gray-600 dark:text-gray-400">
                                            Built for organizations of all sizes with role-based access control
                                        </p>
                                    </div>
                                    
                                    <div className="grid grid-cols-3 gap-4 pt-6">
                                        <div className="text-center">
                                            <div className="bg-blue-100 rounded-lg p-3 mb-2 dark:bg-blue-900">
                                                <span className="text-blue-600 dark:text-blue-300 font-semibold">HR</span>
                                            </div>
                                            <p className="text-xs text-gray-600 dark:text-gray-400">Full Access</p>
                                        </div>
                                        <div className="text-center">
                                            <div className="bg-green-100 rounded-lg p-3 mb-2 dark:bg-green-900">
                                                <span className="text-green-600 dark:text-green-300 font-semibold">MGR</span>
                                            </div>
                                            <p className="text-xs text-gray-600 dark:text-gray-400">Team Management</p>
                                        </div>
                                        <div className="text-center">
                                            <div className="bg-purple-100 rounded-lg p-3 mb-2 dark:bg-purple-900">
                                                <span className="text-purple-600 dark:text-purple-300 font-semibold">EMP</span>
                                            </div>
                                            <p className="text-xs text-gray-600 dark:text-gray-400">Self Service</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
                
                <footer className="mt-12 text-sm text-gray-500 dark:text-gray-400 text-center">
                    Streamlining HR processes for better workplace efficiency
                </footer>
            </div>
        </>
    );
}