<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sso_launch_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('portal_application_id')->constrained('portal_applications')->cascadeOnDelete();
            $table->text('target_url');
            $table->uuid('token_id')->nullable()->index();
            $table->timestamp('token_expires_at')->nullable();
            $table->timestamp('launched_at');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sso_launch_logs');
    }
};
