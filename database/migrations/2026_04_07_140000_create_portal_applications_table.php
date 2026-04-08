<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portal_applications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('badge')->nullable();
            $table->string('icon')->nullable();
            $table->string('accent_color')->nullable();
            $table->string('url');
            $table->string('sso_login_url')->nullable();
            $table->text('description')->nullable();
            $table->json('keywords')->nullable();
            $table->string('sso_audience')->nullable();
            $table->string('sso_shared_secret')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_frequent')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('sso_enabled')->default(true);
            $table->boolean('open_in_new_tab')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portal_applications');
    }
};
