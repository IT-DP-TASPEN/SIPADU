<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portal_application_access_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portal_application_id')->constrained()->cascadeOnDelete();
            $table->string('division_name')->nullable();
            $table->string('job_title')->nullable();
            $table->string('office_type')->nullable();
            $table->string('branch_code')->nullable();
            $table->string('branch_name')->nullable();
            $table->unsignedInteger('priority')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portal_application_access_rules');
    }
};
