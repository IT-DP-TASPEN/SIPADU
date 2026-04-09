<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = json_decode(file_get_contents(database_path('seeders/data/users_final.json')), true, flags: JSON_THROW_ON_ERROR);

        foreach ($users as $user) {
            DB::table('users')->updateOrInsert(
                ['employee_id' => $user['employee_id']],
                $user,
            );
        }
    }
}
