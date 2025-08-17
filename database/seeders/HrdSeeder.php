<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\LeaveRequest;
use App\Models\PerformanceReview;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class HrdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create HR Manager
        $hrManager = User::create([
            'name' => 'Sarah Johnson',
            'email' => 'hr@company.com',
            'password' => Hash::make('password'),
            'role' => 'hr',
            'employee_id' => 'HR001',
            'department' => 'Human Resources',
            'position' => 'HR Manager',
            'phone' => '+1234567890',
            'hire_date' => Carbon::now()->subYears(3),
            'salary' => 75000,
            'status' => 'active',
        ]);

        // Create Department Managers
        $managers = [];
        $managerData = [
            [
                'name' => 'John Smith',
                'email' => 'john.smith@company.com',
                'department' => 'Engineering',
                'position' => 'Engineering Manager',
                'employee_id' => 'ENG001',
                'salary' => 85000,
            ],
            [
                'name' => 'Emily Davis',
                'email' => 'emily.davis@company.com',
                'department' => 'Marketing',
                'position' => 'Marketing Manager',
                'employee_id' => 'MKT001',
                'salary' => 70000,
            ],
            [
                'name' => 'Michael Brown',
                'email' => 'michael.brown@company.com',
                'department' => 'Sales',
                'position' => 'Sales Manager',
                'employee_id' => 'SAL001',
                'salary' => 80000,
            ],
        ];

        foreach ($managerData as $data) {
            $managers[] = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'role' => 'manager',
                'employee_id' => $data['employee_id'],
                'department' => $data['department'],
                'position' => $data['position'],
                'phone' => '+' . random_int(1000000000, 9999999999),
                'hire_date' => Carbon::now()->subYears(random_int(1, 4)),
                'salary' => $data['salary'],
                'status' => 'active',
            ]);
        }

        // Create Employees
        $employees = [];
        $employeeData = [
            // Engineering Team
            [
                'name' => 'Alex Wilson',
                'email' => 'alex.wilson@company.com',
                'department' => 'Engineering',
                'position' => 'Senior Developer',
                'employee_id' => 'ENG002',
                'manager_id' => $managers[0]->id,
                'salary' => 75000,
            ],
            [
                'name' => 'Lisa Chen',
                'email' => 'lisa.chen@company.com',
                'department' => 'Engineering',
                'position' => 'Frontend Developer',
                'employee_id' => 'ENG003',
                'manager_id' => $managers[0]->id,
                'salary' => 65000,
            ],
            [
                'name' => 'David Miller',
                'email' => 'david.miller@company.com',
                'department' => 'Engineering',
                'position' => 'Backend Developer',
                'employee_id' => 'ENG004',
                'manager_id' => $managers[0]->id,
                'salary' => 68000,
            ],
            // Marketing Team
            [
                'name' => 'Jessica Taylor',
                'email' => 'jessica.taylor@company.com',
                'department' => 'Marketing',
                'position' => 'Marketing Specialist',
                'employee_id' => 'MKT002',
                'manager_id' => $managers[1]->id,
                'salary' => 55000,
            ],
            [
                'name' => 'Robert Garcia',
                'email' => 'robert.garcia@company.com',
                'department' => 'Marketing',
                'position' => 'Content Creator',
                'employee_id' => 'MKT003',
                'manager_id' => $managers[1]->id,
                'salary' => 50000,
            ],
            // Sales Team
            [
                'name' => 'Amanda White',
                'email' => 'amanda.white@company.com',
                'department' => 'Sales',
                'position' => 'Sales Representative',
                'employee_id' => 'SAL002',
                'manager_id' => $managers[2]->id,
                'salary' => 60000,
            ],
            [
                'name' => 'Kevin Anderson',
                'email' => 'kevin.anderson@company.com',
                'department' => 'Sales',
                'position' => 'Sales Representative',
                'employee_id' => 'SAL003',
                'manager_id' => $managers[2]->id,
                'salary' => 58000,
            ],
        ];

        foreach ($employeeData as $data) {
            $employees[] = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'role' => 'employee',
                'employee_id' => $data['employee_id'],
                'department' => $data['department'],
                'position' => $data['position'],
                'phone' => '+' . random_int(1000000000, 9999999999),
                'hire_date' => Carbon::now()->subYears(random_int(1, 3)),
                'manager_id' => $data['manager_id'],
                'salary' => $data['salary'],
                'status' => 'active',
            ]);
        }

        // Create Attendance Records
        $allUsers = collect([$hrManager])->merge($managers)->merge($employees);
        foreach ($allUsers as $user) {
            // Create attendance for last 30 days
            for ($i = 30; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                
                // Skip weekends
                if ($date->isWeekend()) {
                    continue;
                }
                
                // 90% attendance rate
                if (random_int(1, 10) > 9) {
                    continue;
                }
                
                $clockIn = $date->copy()->setTime(8, random_int(0, 60));
                $clockOut = $clockIn->copy()->addHours(8)->addMinutes(random_int(0, 60));
                $hoursWorked = $clockOut->diffInMinutes($clockIn) / 60;
                $breakMinutes = random_int(30, 60);
                $hoursWorked = max(0, $hoursWorked - ($breakMinutes / 60));
                
                Attendance::create([
                    'user_id' => $user->id,
                    'date' => $date,
                    'clock_in' => $clockIn,
                    'clock_out' => $clockOut,
                    'break_minutes' => $breakMinutes,
                    'hours_worked' => round($hoursWorked, 2),
                    'status' => 'present',
                ]);
            }
        }

        // Create Job Postings
        $jobPostings = [
            [
                'title' => 'Senior Full Stack Developer',
                'department' => 'Engineering',
                'description' => 'We are looking for a Senior Full Stack Developer to join our growing engineering team.',
                'requirements' => '5+ years of experience with React, Node.js, and PostgreSQL. Strong problem-solving skills.',
                'salary_min' => 80000,
                'salary_max' => 100000,
                'employment_type' => 'full_time',
                'location' => 'Remote',
                'status' => 'published',
                'application_deadline' => Carbon::now()->addMonth(),
                'created_by' => $hrManager->id,
            ],
            [
                'title' => 'Marketing Coordinator',
                'department' => 'Marketing',
                'description' => 'Join our marketing team to help drive brand awareness and lead generation.',
                'requirements' => '2+ years of marketing experience. Experience with digital marketing tools.',
                'salary_min' => 45000,
                'salary_max' => 55000,
                'employment_type' => 'full_time',
                'location' => 'New York, NY',
                'status' => 'published',
                'application_deadline' => Carbon::now()->addWeeks(3),
                'created_by' => $hrManager->id,
            ],
            [
                'title' => 'Sales Development Representative',
                'department' => 'Sales',
                'description' => 'Help us grow our customer base by identifying and qualifying potential leads.',
                'requirements' => '1+ years of sales or customer service experience. Excellent communication skills.',
                'salary_min' => 40000,
                'salary_max' => 50000,
                'employment_type' => 'full_time',
                'location' => 'Chicago, IL',
                'status' => 'published',
                'application_deadline' => Carbon::now()->addWeeks(2),
                'created_by' => $hrManager->id,
            ],
        ];

        foreach ($jobPostings as $posting) {
            $jobPosting = JobPosting::create($posting);
            
            // Create some applications for each job posting
            $applicationCount = random_int(3, 8);
            for ($i = 0; $i < $applicationCount; $i++) {
                JobApplication::create([
                    'job_posting_id' => $jobPosting->id,
                    'candidate_name' => fake()->name(),
                    'candidate_email' => fake()->unique()->safeEmail(),
                    'candidate_phone' => fake()->phoneNumber(),
                    'cover_letter' => fake()->paragraph(3),
                    'status' => fake()->randomElement(['pending', 'reviewing', 'interview', 'rejected']),
                    'applied_at' => fake()->dateTimeBetween('-1 month', 'now'),
                ]);
            }
        }

        // Create Leave Requests
        foreach ($employees as $employee) {
            // Create 2-4 leave requests per employee
            $leaveCount = random_int(2, 4);
            for ($i = 0; $i < $leaveCount; $i++) {
                $startDate = fake()->dateTimeBetween('-6 months', '+3 months');
                $daysRequested = random_int(1, 10);
                $endDate = Carbon::parse($startDate)->addDays($daysRequested - 1);
                
                $status = fake()->randomElement(['pending', 'approved', 'rejected']);
                
                LeaveRequest::create([
                    'employee_id' => $employee->id,
                    'type' => fake()->randomElement(['vacation', 'sick', 'personal']),
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'days_requested' => $daysRequested,
                    'reason' => fake()->sentence(),
                    'status' => $status,
                    'approved_by' => $status !== 'pending' ? $employee->manager_id : null,
                    'approved_at' => $status !== 'pending' ? fake()->dateTimeBetween($startDate, 'now') : null,
                ]);
            }
        }

        // Create Performance Reviews
        foreach ($employees as $employee) {
            if ($employee->manager_id) {
                // Create annual review
                PerformanceReview::create([
                    'employee_id' => $employee->id,
                    'reviewer_id' => $employee->manager_id,
                    'review_period_start' => Carbon::now()->subYear()->startOfYear(),
                    'review_period_end' => Carbon::now()->subYear()->endOfYear(),
                    'performance_score' => random_int(6, 10),
                    'goals_achieved' => 'Successfully completed all assigned projects and exceeded sales targets.',
                    'areas_for_improvement' => 'Could benefit from additional training in leadership skills.',
                    'manager_comments' => 'Excellent performance throughout the year. Shows great potential.',
                    'employee_comments' => 'I appreciate the feedback and look forward to taking on more responsibilities.',
                    'status' => 'completed',
                    'completed_at' => fake()->dateTimeBetween('-3 months', '-1 month'),
                ]);
            }
        }

        $this->command->info('HRD system seeded with sample data!');
        $this->command->info('Login credentials:');
        $this->command->info('HR: hr@company.com / password');
        $this->command->info('Manager: john.smith@company.com / password');
        $this->command->info('Employee: alex.wilson@company.com / password');
    }
}