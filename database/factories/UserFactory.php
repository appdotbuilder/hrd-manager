<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $departments = ['Engineering', 'Marketing', 'Sales', 'HR', 'Finance', 'Operations'];
        $positions = [
            'Engineering' => ['Senior Developer', 'Frontend Developer', 'Backend Developer', 'DevOps Engineer'],
            'Marketing' => ['Marketing Manager', 'Content Creator', 'Digital Marketing Specialist'],
            'Sales' => ['Sales Representative', 'Account Manager', 'Business Development'],
            'HR' => ['HR Generalist', 'Recruiter', 'HR Manager'],
            'Finance' => ['Accountant', 'Financial Analyst', 'Controller'],
            'Operations' => ['Operations Manager', 'Project Manager', 'Coordinator'],
        ];

        $department = fake()->randomElement($departments);
        $position = fake()->randomElement($positions[$department] ?? ['Specialist']);

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => fake()->randomElement(['hr', 'manager', 'employee']),
            'employee_id' => fake()->unique()->regexify('[A-Z]{3}[0-9]{3}'),
            'department' => $department,
            'position' => $position,
            'phone' => fake()->phoneNumber(),
            'hire_date' => fake()->dateTimeBetween('-5 years', '-1 month'),
            'salary' => fake()->numberBetween(40000, 120000),
            'status' => fake()->randomElement(['active', 'inactive']),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
