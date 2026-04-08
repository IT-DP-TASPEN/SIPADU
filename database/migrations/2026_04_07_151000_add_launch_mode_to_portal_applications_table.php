<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('portal_applications', function (Blueprint $table) {
            $table->string('launch_mode')->default('sso')->after('is_active');
        });

        DB::table('portal_applications')
            ->where('slug', 'core-banking')
            ->update(['launch_mode' => 'launch_only']);
    }

    public function down(): void
    {
        Schema::table('portal_applications', function (Blueprint $table) {
            $table->dropColumn('launch_mode');
        });
    }
};
