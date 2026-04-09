<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sso_launch_logs', function (Blueprint $table) {
            $table->string('application_name_snapshot')->nullable()->after('portal_application_id');
            $table->string('application_slug_snapshot')->nullable()->after('application_name_snapshot');
            $table->string('launch_mode_snapshot')->nullable()->after('application_slug_snapshot');
            $table->string('issuer_snapshot')->nullable()->after('launch_mode_snapshot');
            $table->string('audience_snapshot')->nullable()->after('issuer_snapshot');
        });

        DB::table('sso_launch_logs')
            ->leftJoin('portal_applications', 'portal_applications.id', '=', 'sso_launch_logs.portal_application_id')
            ->select([
                'sso_launch_logs.id',
                'portal_applications.name',
                'portal_applications.slug',
                'portal_applications.launch_mode',
                'portal_applications.sso_audience',
            ])
            ->orderBy('sso_launch_logs.id')
            ->get()
            ->each(function (object $log): void {
                DB::table('sso_launch_logs')
                    ->where('id', $log->id)
                    ->update([
                        'application_name_snapshot' => $log->name,
                        'application_slug_snapshot' => $log->slug,
                        'launch_mode_snapshot' => $log->launch_mode,
                        'audience_snapshot' => $log->launch_mode === 'sso'
                            ? ($log->sso_audience ?: $log->slug)
                            : null,
                    ]);
            });
    }

    public function down(): void
    {
        Schema::table('sso_launch_logs', function (Blueprint $table) {
            $table->dropColumn([
                'application_name_snapshot',
                'application_slug_snapshot',
                'launch_mode_snapshot',
                'issuer_snapshot',
                'audience_snapshot',
            ]);
        });
    }
};
