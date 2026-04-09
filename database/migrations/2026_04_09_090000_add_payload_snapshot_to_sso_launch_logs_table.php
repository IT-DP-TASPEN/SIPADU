<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sso_launch_logs', function (Blueprint $table) {
            $table->json('payload_snapshot')->nullable()->after('user_agent');
        });
    }

    public function down(): void
    {
        Schema::table('sso_launch_logs', function (Blueprint $table) {
            $table->dropColumn('payload_snapshot');
        });
    }
};
