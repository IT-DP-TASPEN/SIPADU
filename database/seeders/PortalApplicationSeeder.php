<?php

namespace Database\Seeders;

use App\Models\PortalApplication;
use Illuminate\Support\Arr;
use Illuminate\Database\Seeder;

class PortalApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $applications = json_decode(file_get_contents(database_path('seeders/data/portal-applications.json')), true, flags: JSON_THROW_ON_ERROR);

        foreach ($applications as $application) {
            $accessRules = Arr::pull($application, 'access_rules', []);

            $portalApplication = PortalApplication::query()->updateOrCreate(
                ['slug' => $application['slug']],
                $application,
            );

            $portalApplication->accessRules()->delete();

            foreach ($accessRules as $rule) {
                $portalApplication->accessRules()->create($rule);
            }
        }
    }
}
