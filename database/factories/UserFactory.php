<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
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
        return [
            'name' => fake()->name(),
            'username' => fake()->unique()->userName(),
            'employee_id' => 'BDT'.fake()->unique()->numerify('####'),
            'title' => fake()->randomElement(['Relationship Manager', 'Operations Officer', 'Supervisor', 'Branch Manager']),
            'unit_name' => fake()->randomElement(['Operasional Cabang', 'Kredit', 'Treasury', 'Kepatuhan']),
            'division_name' => fake()->randomElement(['IT', 'Business', 'Accounting', 'Legal & Compliance']),
            'office_type' => fake()->randomElement(['head_office', 'branch']),
            'branch_code' => fake()->numerify('BR###'),
            'branch_name' => fake()->city(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'last_login_at' => now()->subHours(fake()->numberBetween(1, 48)),
            'remember_token' => Str::random(10),
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
