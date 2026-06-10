<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('status')->default('active')->after('is_admin');
            $table->date('active_from')->nullable()->after('status');
            $table->date('active_until')->nullable()->after('active_from');
            $table->timestamp('password_changed_at')->nullable()->after('active_until');
            $table->boolean('must_change_password')->default(false)->after('password_changed_at');
            $table->unsignedInteger('login_failure_count')->default(0)->after('must_change_password');
            $table->timestamp('locked_at')->nullable()->after('login_failure_count');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'status', 'active_from', 'active_until',
                'password_changed_at', 'must_change_password',
                'login_failure_count', 'locked_at',
            ]);
        });
    }
};
