<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\PasswordComplexity;
use App\Services\AuditService;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function __construct(
        protected AuditService $audit,
    ) {}

    public function index(Request $request): View
    {
        $search = trim((string) $request->string('q'));
        $statusFilter = $request->string('status')->value();

        $users = User::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($builder) use ($search) {
                    $builder
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('employee_id', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('division_name', 'like', "%{$search}%")
                        ->orWhere('title', 'like', "%{$search}%");
                });
            })
            ->when($statusFilter !== '' && in_array($statusFilter, User::STATUSES), function ($query) use ($statusFilter) {
                $query->where('status', $statusFilter);
            })
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('users.index', [
            'users' => $users,
            'search' => $search,
            'statusFilter' => $statusFilter,
        ]);
    }

    public function create(): View
    {
        return view('users.create', [
            'userModel' => new User([
                'office_type' => 'head_office',
                'status' => 'active',
            ]),
            'canManageAdminFlag' => true,
        ]);
    }

    public function export(): Response
    {
        $users = User::query()
            ->orderBy('name')
            ->get();

        $content = view('users.export', [
            'users' => $users,
            'generatedAt' => now(),
        ])->render();

        return response($content, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="sipadu-users-export.xls"',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['password'] = Hash::make($data['password']);
        $data['is_admin'] = $request->boolean('is_admin');
        $data['must_change_password'] = true; // Force change on first login
        $data['password_changed_at'] = now();
        $data['status'] = $request->input('status', User::STATUS_ACTIVE);

        $user = User::query()->create($data);

        // Save initial password to history
        $user->passwordHistories()->create([
            'password' => $user->getRawOriginal('password'),
            'created_at' => now(),
        ]);

        $this->audit->log('User Created', $user, "User {$user->name} ({$user->username}) created by admin");

        return redirect()
            ->route('users.index')
            ->with('status', "User {$user->name} berhasil ditambahkan.");
    }

    public function edit(User $user): View
    {
        return view('users.edit', [
            'userModel' => $user,
            'canManageAdminFlag' => true,
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $this->validatedData($request, $user, false);

        if ($request->user()->is($user) && ! $request->boolean('is_admin')) {
            return back()
                ->withErrors(['is_admin' => 'Anda tidak dapat mencabut hak admin dari akun yang sedang digunakan.'])
                ->withInput();
        }

        $passwordChanged = false;
        if (! empty($data['password'])) {
            $oldHash = $user->getRawOriginal('password');
            $data['password'] = Hash::make($data['password']);
            $data['password_changed_at'] = now();
            $passwordChanged = true;

            // Save old password to history
            $user->passwordHistories()->create([
                'password' => $oldHash,
                'created_at' => now(),
            ]);
        } else {
            unset($data['password']);
        }

        $data['is_admin'] = $request->boolean('is_admin');

        $newStatus = $request->input('status', $user->status);
        if ($newStatus !== $user->status) {
            $this->audit->log('User Status Changed', $user, "Status: {$user->status} -> {$newStatus}");
        }
        $data['status'] = $newStatus;

        $user->update($data);

        $this->audit->log('User Updated', $user, "User {$user->name} updated by admin" . ($passwordChanged ? ' (password changed)' : ''));

        return redirect()
            ->route('users.index')
            ->with('status', "User {$user->name} berhasil diperbarui.");
    }

    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        $defaultPassword = config('uam.reset_password_default', 'DPT@SP3n');
        $oldHash = $user->getRawOriginal('password');

        $user->forceFill([
            'password' => Hash::make($defaultPassword),
            'must_change_password' => true,
            'password_changed_at' => now(),
            'login_failure_count' => 0,
            'locked_at' => null,
            'status' => $user->isLocked() ? User::STATUS_ACTIVE : $user->status,
        ])->save();

        // Save old password to history
        $user->passwordHistories()->create([
            'password' => $oldHash,
            'created_at' => now(),
        ]);

        $this->audit->log('Password Reset by Admin', $user, "Password reset by {$request->user()->name} ({$request->user()->username})");

        // Create notification
        $user->uamNotifications()->create([
            'type' => 'security',
            'title' => 'Password Anda Telah Di-reset',
            'body' => 'Password Anda telah di-reset oleh Administrator. Anda wajib mengganti password saat login berikutnya.',
            'priority' => 'high',
            'created_at' => now(),
        ]);

        return redirect()
            ->route('users.index')
            ->with('status', "Password untuk {$user->name} berhasil di-reset. User wajib mengganti password saat login berikutnya.");
    }

    public function unlock(Request $request, User $user): RedirectResponse
    {
        if (! $user->isLocked()) {
            return back()->with('warning', 'Akun ini tidak dalam status terkunci.');
        }

        $user->forceFill([
            'status' => User::STATUS_ACTIVE,
            'login_failure_count' => 0,
            'locked_at' => null,
        ])->save();

        $this->audit->log('Account Unlocked', $user, "Unlocked by {$request->user()->name}");

        $user->uamNotifications()->create([
            'type' => 'security',
            'title' => 'Akun Anda Telah Dibuka',
            'body' => 'Akun Anda telah dibuka kembali oleh Administrator.',
            'priority' => 'high',
            'created_at' => now(),
        ]);

        return redirect()
            ->route('users.index')
            ->with('status', "Akun {$user->name} berhasil dibuka kembali.");
    }

    private function validatedData(Request $request, ?User $user = null, bool $passwordRequired = true): array
    {
        // When creating a user, admin sets initial password; user must change it on first login
        // where full complexity + history rules are enforced via ForcePasswordChangeController.
        // When updating, enforce complexity since admin is actively setting a new password.
        $passwordRules = $passwordRequired
            ? ['required', 'string', 'min:6']
            : ['nullable', 'string', new PasswordComplexity(['user_id' => $user?->id, 'current_password_hash' => $user?->getRawOriginal('password')])];

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($user?->id)],
            'employee_id' => ['required', 'string', 'max:255', Rule::unique('users', 'employee_id')->ignore($user?->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user?->id)],
            'phone' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'unit_name' => ['nullable', 'string', 'max:255'],
            'division_name' => ['nullable', 'string', 'max:255'],
            'office_type' => ['required', Rule::in(['head_office', 'branch'])],
            'branch_code' => ['nullable', 'string', 'max:255'],
            'branch_name' => ['nullable', 'string', 'max:255'],
            'is_admin' => ['nullable', 'boolean'],
            'status' => ['nullable', Rule::in(User::STATUSES)],
            'active_from' => ['nullable', 'date'],
            'active_until' => ['nullable', 'date', 'after:active_from'],
            'password' => $passwordRules,
        ]);
    }
}
