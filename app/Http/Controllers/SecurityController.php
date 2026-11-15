<?php

namespace App\Http\Controllers;

use App\Models\SecurityEvent;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/**
 * KSI - Audit keamanan dan manajemen peran.
 */
class SecurityController extends Controller
{
    /** Audit keamanan (admin). */
    public function audit(Request $request): View
    {
        $filters = $request->validate([
            'event' => ['nullable', Rule::in(array_keys(SecurityEvent::LABELS))],
        ]);

        $events = SecurityEvent::with('user')
            ->when($filters['event'] ?? null, fn ($q, $e) => $q->where('event', $e))
            ->latest()
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        $users = User::orderBy('name')->get(['id', 'name', 'email', 'role']);

        $failedToday = SecurityEvent::where('event', 'login_failed')
            ->where('created_at', '>=', now()->startOfDay())
            ->count();

        return view('security.audit', compact('events', 'users', 'failedToday'));
    }

    /** Buat akun pengguna baru (admin). Pengganti registrasi publik. */
    public function storeUser(Request $request): RedirectResponse
    {
        $data = $request->validateWithBag('createUser', [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role'     => ['required', Rule::in([User::ROLE_ADMIN, User::ROLE_INSPECTOR])],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // role tidak ada di $fillable (anti mass assignment), jadi diisi eksplisit.
        $user->forceFill([
            'role'              => $data['role'],
            'email_verified_at' => now(),
        ])->save();

        SecurityEvent::record(
            'user_created',
            $request->user()->id,
            "Akun {$user->email} dibuat dengan peran {$data['role']}.",
            $request
        );

        return back()->with('success', 'Akun pengguna berhasil dibuat.');
    }

    /** Ubah peran pengguna (admin). */
    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'role' => ['required', Rule::in([User::ROLE_ADMIN, User::ROLE_INSPECTOR])],
        ]);

        if ($user->id === $request->user()->id && $data['role'] !== User::ROLE_ADMIN) {
            return back()->withErrors(['role' => 'Anda tidak dapat menurunkan peran akun sendiri.']);
        }

        $user->forceFill(['role' => $data['role']])->save();

        SecurityEvent::record(
            'role_changed',
            $request->user()->id,
            "Peran {$user->email} diubah menjadi {$data['role']}.",
            $request
        );

        return back()->with('success', 'Peran pengguna berhasil diperbarui.');
    }
}
