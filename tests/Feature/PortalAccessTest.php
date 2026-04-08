<?php

namespace Tests\Feature;

use App\Models\PortalApplication;
use App\Models\SsoLaunchLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortalAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/portal')->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_portal(): void
    {
        $user = User::factory()->create();

        PortalApplication::query()->create([
            'name' => 'Core Banking',
            'slug' => 'core-banking',
            'url' => 'http://core-banking.test',
            'sso_login_url' => 'http://core-banking.test/sso/login',
            'description' => 'Portal transaksi inti.',
            'keywords' => ['core', 'transaksi'],
            'badge' => 'Operasional',
            'icon' => 'bank',
            'accent_color' => 'brand',
        ]);

        $this->actingAs($user)
            ->get('/portal')
            ->assertOk()
            ->assertSee('SIPADU')
            ->assertSee('Core Banking');
    }

    public function test_search_endpoint_returns_matching_application(): void
    {
        $user = User::factory()->create();

        PortalApplication::query()->create([
            'name' => 'Kredit Pintar',
            'slug' => 'kredit-pintar',
            'url' => 'http://kredit-pintar.test',
            'sso_login_url' => 'http://kredit-pintar.test/sso/login',
            'description' => 'Analisa pengajuan kredit.',
            'keywords' => ['kredit', 'pinjaman'],
            'badge' => 'Bisnis',
            'icon' => 'chart',
            'accent_color' => 'emerald',
        ]);

        $this->actingAs($user)
            ->getJson('/portal/search?q=kredit')
            ->assertOk()
            ->assertJsonPath('data.0.slug', 'kredit-pintar');
    }

    public function test_launch_endpoint_redirects_to_target_with_sso_token(): void
    {
        $user = User::factory()->create();

        $application = PortalApplication::query()->create([
            'name' => 'Pelaporan OJK',
            'slug' => 'pelaporan-ojk',
            'url' => 'http://pelaporan-ojk.test',
            'sso_login_url' => 'http://pelaporan-ojk.test/sso/login',
            'description' => 'Kepatuhan pelaporan.',
            'keywords' => ['ojk'],
            'badge' => 'Compliance',
            'icon' => 'document',
            'accent_color' => 'lime',
            'launch_mode' => 'sso',
        ]);

        $response = $this->actingAs($user)->get(route('portal.launch', $application));

        $response->assertRedirect();
        $this->assertStringContainsString('sso_token=', $response->headers->get('Location'));
        $this->assertStringContainsString('issuer=', $response->headers->get('Location'));
    }

    public function test_launch_only_application_redirects_without_sso_token(): void
    {
        $user = User::factory()->create();

        $application = PortalApplication::query()->create([
            'name' => 'Core Banking',
            'slug' => 'core-banking',
            'url' => 'http://core-banking.test/login',
            'sso_login_url' => 'http://core-banking.test/sso/login',
            'description' => 'Sistem inti.',
            'keywords' => ['core'],
            'badge' => 'Operasional',
            'icon' => 'bank',
            'accent_color' => 'brand',
            'launch_mode' => 'launch_only',
        ]);

        $response = $this->actingAs($user)->get(route('portal.launch', $application));

        $response->assertRedirect('http://core-banking.test/login');
        $this->assertStringNotContainsString('sso_token=', $response->headers->get('Location'));
    }

    public function test_authenticated_user_can_view_application_management_page(): void
    {
        $user = User::factory()->create(['is_admin' => true]);

        PortalApplication::factory()->create([
            'name' => 'HR Connect',
            'slug' => 'hr-connect',
        ]);

        $this->actingAs($user)
            ->get(route('portal-applications.index'))
            ->assertOk()
            ->assertSee('Kelola aplikasi');
    }

    public function test_authenticated_user_can_create_launch_only_application(): void
    {
        $user = User::factory()->create(['is_admin' => true]);

        $this->actingAs($user)
            ->post(route('portal-applications.store'), [
                'name' => 'Core Banking',
                'slug' => 'core-banking',
                'description' => 'Sistem inti bank.',
                'url' => 'http://core-banking.test/login',
                'badge' => 'Operasional',
                'icon' => 'bank',
                'accent_color' => 'brand',
                'keywords' => 'core, transaksi',
                'sort_order' => 10,
                'launch_mode' => 'launch_only',
                'is_active' => '1',
                'open_in_new_tab' => '1',
            ])
            ->assertRedirect(route('portal-applications.index'));

        $this->assertDatabaseHas('portal_applications', [
            'slug' => 'core-banking',
            'launch_mode' => 'launch_only',
            'sso_enabled' => false,
            'sso_login_url' => null,
        ]);
    }

    public function test_authenticated_user_can_update_application_launch_mode(): void
    {
        $user = User::factory()->create(['is_admin' => true]);

        $application = PortalApplication::factory()->create([
            'launch_mode' => 'launch_only',
            'sso_enabled' => false,
            'sso_login_url' => null,
        ]);

        $this->actingAs($user)
            ->put(route('portal-applications.update', $application), [
                'name' => $application->name,
                'slug' => $application->slug,
                'description' => $application->description,
                'url' => $application->url,
                'sso_login_url' => 'http://example.test/sso/login',
                'badge' => $application->badge,
                'icon' => $application->icon,
                'accent_color' => $application->accent_color,
                'keywords' => 'alpha, beta',
                'sort_order' => 1,
                'launch_mode' => 'sso',
                'sso_audience' => 'example-app',
                'is_active' => '1',
                'open_in_new_tab' => '1',
            ])
            ->assertRedirect(route('portal-applications.index'));

        $this->assertDatabaseHas('portal_applications', [
            'id' => $application->id,
            'launch_mode' => 'sso',
            'sso_enabled' => true,
            'sso_audience' => 'example-app',
        ]);
    }

    public function test_portal_only_shows_applications_matching_user_access_rules(): void
    {
        $user = User::factory()->create([
            'division_name' => 'IT',
            'title' => 'IT Development Staff',
            'office_type' => 'head_office',
            'branch_code' => null,
            'branch_name' => null,
        ]);

        $allowedApplication = PortalApplication::factory()->create([
            'name' => 'DevOps Console',
            'slug' => 'devops-console',
        ]);

        $blockedApplication = PortalApplication::factory()->create([
            'name' => 'Legal Workspace',
            'slug' => 'legal-workspace',
        ]);

        $allowedApplication->accessRules()->create([
            'division_name' => 'IT',
            'job_title' => 'IT Development Staff',
            'office_type' => 'head_office',
            'priority' => 1,
        ]);

        $blockedApplication->accessRules()->create([
            'division_name' => 'Legal & Compliance',
            'job_title' => 'Legal and Compliance Staff',
            'office_type' => 'head_office',
            'priority' => 1,
        ]);

        $this->actingAs($user)
            ->get(route('portal.index'))
            ->assertOk()
            ->assertSee('DevOps Console')
            ->assertDontSee('Legal Workspace');
    }

    public function test_launch_is_forbidden_when_user_does_not_match_access_rules(): void
    {
        $user = User::factory()->create([
            'division_name' => 'IT',
            'title' => 'IT Development Staff',
            'office_type' => 'head_office',
        ]);

        $application = PortalApplication::factory()->create([
            'launch_mode' => 'launch_only',
            'sso_enabled' => false,
        ]);

        $application->accessRules()->create([
            'division_name' => 'Business',
            'job_title' => 'Funding Staff',
            'office_type' => 'branch',
            'priority' => 1,
        ]);

        $this->actingAs($user)
            ->get(route('portal.launch', $application))
            ->assertForbidden();
    }

    public function test_authenticated_user_can_create_application_with_access_rules(): void
    {
        $user = User::factory()->create(['is_admin' => true]);

        $this->actingAs($user)
            ->post(route('portal-applications.store'), [
                'name' => 'IT Knowledge Hub',
                'slug' => 'it-knowledge-hub',
                'description' => 'Pusat knowledge internal.',
                'url' => 'http://it-knowledge-hub.test',
                'sso_login_url' => 'http://it-knowledge-hub.test/sso/login',
                'badge' => 'Knowledge',
                'icon' => 'document',
                'accent_color' => 'sky',
                'keywords' => 'wiki, knowledge',
                'sort_order' => 20,
                'launch_mode' => 'sso',
                'sso_audience' => 'it-knowledge-hub',
                'is_active' => '1',
                'open_in_new_tab' => '1',
                'access_rules' => "IT | IT Development Staff | head_office | * | *\nIT | IT Operations Staff | head_office | * | *",
            ])
            ->assertRedirect(route('portal-applications.index'));

        $application = PortalApplication::query()->where('slug', 'it-knowledge-hub')->firstOrFail();

        $this->assertDatabaseHas('portal_application_access_rules', [
            'portal_application_id' => $application->id,
            'division_name' => 'IT',
            'job_title' => 'IT Development Staff',
            'office_type' => 'head_office',
        ]);
    }

    public function test_sso_application_generates_shared_secret_automatically(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->post(route('portal-applications.store'), [
                'name' => 'Audit Surrounding',
                'slug' => 'audit-surrounding',
                'description' => 'Aplikasi audit.',
                'url' => 'http://audit-surrounding.test',
                'sso_login_url' => 'http://audit-surrounding.test/sso/login',
                'badge' => 'Audit',
                'icon' => 'shield',
                'accent_color' => 'rose',
                'keywords' => 'audit, temuan',
                'sort_order' => 10,
                'launch_mode' => 'sso',
                'sso_audience' => 'audit-surrounding',
                'is_active' => '1',
                'open_in_new_tab' => '1',
            ])
            ->assertRedirect(route('portal-applications.index'));

        $application = PortalApplication::query()->where('slug', 'audit-surrounding')->firstOrFail();

        $this->assertNotNull($application->sso_shared_secret);
        $this->assertTrue(strlen($application->sso_shared_secret) >= 32);
    }

    public function test_authenticated_user_can_view_user_management_page(): void
    {
        $user = User::factory()->create(['is_admin' => true]);

        $this->actingAs($user)
            ->get(route('users.index'))
            ->assertOk()
            ->assertSee('Kelola user SIPADU');
    }

    public function test_authenticated_user_can_create_user_with_access_attributes(): void
    {
        $user = User::factory()->create(['is_admin' => true]);

        $this->actingAs($user)
            ->post(route('users.store'), [
                'name' => 'User Baru',
                'username' => '9999999_Baru',
                'employee_id' => '9999999',
                'email' => 'baru@example.com',
                'phone' => '081234567890',
                'password' => 'password123',
                'title' => 'IT Development Staff',
                'unit_name' => 'IT Development Staff',
                'division_name' => 'IT',
                'office_type' => 'head_office',
                'branch_code' => null,
                'branch_name' => null,
            ])
            ->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', [
            'username' => '9999999_Baru',
            'employee_id' => '9999999',
            'division_name' => 'IT',
            'office_type' => 'head_office',
        ]);
    }

    public function test_authenticated_user_can_update_user_access_attributes(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $managedUser = User::factory()->create([
            'division_name' => 'IT',
            'office_type' => 'head_office',
            'branch_code' => null,
            'branch_name' => null,
        ]);

        $this->actingAs($admin)
            ->put(route('users.update', $managedUser), [
                'name' => $managedUser->name,
                'username' => $managedUser->username,
                'employee_id' => $managedUser->employee_id,
                'email' => $managedUser->email,
                'phone' => '082200000000',
                'password' => '',
                'title' => 'Funding Staff',
                'unit_name' => 'Funding Staff',
                'division_name' => 'Business',
                'office_type' => 'branch',
                'branch_code' => 'BR001',
                'branch_name' => 'Cabang Bandung',
            ])
            ->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', [
            'id' => $managedUser->id,
            'division_name' => 'Business',
            'office_type' => 'branch',
            'branch_code' => 'BR001',
            'branch_name' => 'Cabang Bandung',
        ]);
    }

    public function test_admin_can_promote_another_user_to_admin(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $managedUser = User::factory()->create(['is_admin' => false]);

        $this->actingAs($admin)
            ->put(route('users.update', $managedUser), [
                'name' => $managedUser->name,
                'username' => $managedUser->username,
                'employee_id' => $managedUser->employee_id,
                'email' => $managedUser->email,
                'phone' => $managedUser->phone,
                'password' => '',
                'title' => $managedUser->title,
                'unit_name' => $managedUser->unit_name,
                'division_name' => $managedUser->division_name,
                'office_type' => $managedUser->office_type,
                'branch_code' => $managedUser->branch_code,
                'branch_name' => $managedUser->branch_name,
                'is_admin' => '1',
            ])
            ->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', [
            'id' => $managedUser->id,
            'is_admin' => true,
        ]);
    }

    public function test_admin_cannot_remove_admin_flag_from_current_account(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->from(route('users.edit', $admin))
            ->put(route('users.update', $admin), [
                'name' => $admin->name,
                'username' => $admin->username,
                'employee_id' => $admin->employee_id,
                'email' => $admin->email,
                'phone' => $admin->phone,
                'password' => '',
                'title' => $admin->title,
                'unit_name' => $admin->unit_name,
                'division_name' => $admin->division_name,
                'office_type' => $admin->office_type,
                'branch_code' => $admin->branch_code,
                'branch_name' => $admin->branch_name,
            ])
            ->assertRedirect(route('users.edit', $admin));

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
            'is_admin' => true,
        ]);
    }

    public function test_non_admin_user_cannot_access_application_management_page(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get(route('portal-applications.index'))
            ->assertForbidden();
    }

    public function test_non_admin_user_cannot_access_user_management_page(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get(route('users.index'))
            ->assertForbidden();
    }

    public function test_admin_can_export_users_as_excel_friendly_file(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $managedUser = User::factory()->create([
            'name' => 'Ferrian Muhammad Fatichin',
            'employee_id' => '2907997',
        ]);

        $this->actingAs($admin)
            ->get(route('users.export'))
            ->assertOk()
            ->assertHeader('content-type', 'application/vnd.ms-excel; charset=UTF-8')
            ->assertSee('Daftar User SIPADU')
            ->assertSee($managedUser->employee_id);
    }

    public function test_non_admin_user_cannot_export_users(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get(route('users.export'))
            ->assertForbidden();
    }

    public function test_admin_can_view_dashboard_monitoring_page(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $application = PortalApplication::factory()->create([
            'name' => 'HR Connect',
            'slug' => 'hr-connect',
        ]);

        SsoLaunchLog::query()->create([
            'user_id' => $admin->id,
            'portal_application_id' => $application->id,
            'target_url' => $application->url,
            'token_id' => 'b32cef06-0288-4ae4-9c97-1e89d8cb923a',
            'token_expires_at' => now()->addMinutes(5),
            'launched_at' => now()->subMinute(),
            'ip_address' => '10.10.10.10',
            'user_agent' => 'PHPUnit',
        ]);

        $this->actingAs($admin)
            ->get(route('dashboard.index'))
            ->assertOk()
            ->assertSee('Dashboard aktivitas SIPADU')
            ->assertSee('HR Connect');
    }

    public function test_dashboard_shows_top_users_and_top_applications_from_launch_logs(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $activeUser = User::factory()->create([
            'name' => 'Ferrian Muhammad Fatichin',
            'employee_id' => '2907997',
        ]);
        $secondUser = User::factory()->create([
            'name' => 'Rhomandani Mustika Budiarti',
            'employee_id' => '0803992',
        ]);

        $coreBanking = PortalApplication::factory()->create([
            'name' => 'Core Banking',
            'slug' => 'core-banking',
            'launch_mode' => 'launch_only',
            'sso_enabled' => false,
        ]);
        $hrConnect = PortalApplication::factory()->create([
            'name' => 'HR Connect',
            'slug' => 'hr-connect',
        ]);

        SsoLaunchLog::query()->create([
            'user_id' => $activeUser->id,
            'portal_application_id' => $coreBanking->id,
            'target_url' => $coreBanking->url,
            'token_id' => null,
            'token_expires_at' => null,
            'launched_at' => now()->subMinutes(15),
            'ip_address' => '10.10.10.1',
            'user_agent' => 'PHPUnit',
        ]);

        SsoLaunchLog::query()->create([
            'user_id' => $activeUser->id,
            'portal_application_id' => $coreBanking->id,
            'target_url' => $coreBanking->url,
            'token_id' => null,
            'token_expires_at' => null,
            'launched_at' => now()->subMinutes(10),
            'ip_address' => '10.10.10.1',
            'user_agent' => 'PHPUnit',
        ]);

        SsoLaunchLog::query()->create([
            'user_id' => $secondUser->id,
            'portal_application_id' => $hrConnect->id,
            'target_url' => $hrConnect->url,
            'token_id' => 'b2b44d3f-2c57-44cb-8c5c-7fdf3c33c198',
            'token_expires_at' => now()->addMinutes(5),
            'launched_at' => now()->subMinutes(5),
            'ip_address' => '10.10.10.2',
            'user_agent' => 'PHPUnit',
        ]);

        $this->actingAs($admin)
            ->get(route('dashboard.index'))
            ->assertOk()
            ->assertSee('Ferrian Muhammad Fatichin')
            ->assertSee('Core Banking')
            ->assertSee('Launch Hari Ini')
            ->assertSee('3');
    }

    public function test_non_admin_user_cannot_access_dashboard_monitoring_page(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get(route('dashboard.index'))
            ->assertForbidden();
    }

    public function test_admin_can_view_dynamic_sso_guide_with_application_configuration(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $application = PortalApplication::factory()->create([
            'name' => 'DMTL Audit',
            'slug' => 'dmtl-audit',
            'launch_mode' => 'sso',
            'sso_audience' => 'dmtl-audit',
            'sso_shared_secret' => 'secret-dmtl-audit',
            'sso_login_url' => 'http://dmtl-audit.test/sso/login',
        ]);

        $this->actingAs($admin)
            ->get(route('docs.sso.concise.html'))
            ->assertOk()
            ->assertSee('DMTL Audit')
            ->assertSee('secret-dmtl-audit')
            ->assertSee($application->sso_audience);
    }

    public function test_non_admin_user_cannot_access_dynamic_sso_guide(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)
            ->get(route('docs.sso.concise.html'))
            ->assertForbidden();
    }

    public function test_ferrian_is_seeded_as_admin(): void
    {
        $this->seed(\Database\Seeders\UserSeeder::class);

        $this->assertDatabaseHas('users', [
            'employee_id' => '2907997',
            'username' => '2907997_Ferrian',
            'is_admin' => true,
        ]);
    }

    public function test_authenticated_user_can_view_profile_page(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('profile.edit'))
            ->assertOk()
            ->assertSee('Profil Saya');
    }

    public function test_authenticated_user_can_update_profile(): void
    {
        $user = User::factory()->create([
            'division_name' => 'IT',
            'office_type' => 'head_office',
        ]);

        $this->actingAs($user)
            ->put(route('profile.update'), [
                'name' => $user->name,
                'username' => $user->username,
                'employee_id' => $user->employee_id,
                'email' => $user->email,
                'phone' => '081111111111',
                'password' => '',
                'title' => 'IT Development Staff',
                'unit_name' => 'IT Development Staff',
                'division_name' => 'IT',
                'office_type' => 'branch',
                'branch_code' => 'BR007',
                'branch_name' => 'Cabang Surabaya',
            ])
            ->assertRedirect(route('profile.edit'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'office_type' => 'branch',
            'branch_code' => 'BR007',
            'branch_name' => 'Cabang Surabaya',
        ]);
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }
}
