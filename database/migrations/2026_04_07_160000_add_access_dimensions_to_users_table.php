<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('division_name')->nullable()->after('unit_name');
            $table->string('office_type')->nullable()->after('division_name');
            $table->string('branch_code')->nullable()->after('office_type');
            $table->string('branch_name')->nullable()->after('branch_code');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['division_name', 'office_type', 'branch_code', 'branch_name']);
        });
    }
};
