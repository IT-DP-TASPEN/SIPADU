<?php

namespace Database\Factories;

use App\Models\PortalApplication;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<PortalApplication>
 */
class PortalApplicationFactory extends Factory
{
    protected $model = PortalApplication::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => Str::title($name),
            'slug' => Str::slug($name),
            'description' => fake()->sentence(),
            'url' => fake()->url(),
            'sso_login_url' => fake()->url(),
            'badge' => fake()->randomElement(['Operasional', 'Support', 'Compliance']),
            'icon' => fake()->randomElement(['bank', 'chart', 'shield', 'document']),
            'accent_color' => fake()->randomElement(['brand', 'emerald', 'teal', 'lime']),
            'keywords' => ['alpha', 'beta'],
            'sort_order' => fake()->numberBetween(1, 100),
            'is_frequent' => false,
            'is_active' => true,
            'launch_mode' => 'sso',
            'sso_enabled' => true,
            'open_in_new_tab' => true,
            'sso_audience' => fake()->slug(),
            'sso_shared_secret' => null,
        ];
    }
}
