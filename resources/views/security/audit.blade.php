@php
    use App\Models\SecurityEvent;
    $badge = [
        'login_success' => 'bg-emerald-50 text-emerald-700',
        'login_failed'  => 'bg-red-50 text-red-700',
        'lockout'       => 'bg-red-100 text-red-800',
        'access_denied' => 'bg-amber-50 text-amber-800',
    ];
@endphp

<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-slate-800">Audit Keamanan</h2>
            <p class="mt-1 text-sm text-slate-500">Jejak autentikasi, otorisasi, dan pengelolaan peran pengguna.</p>
        </div>
    </x-slot>

    <div class="space-y-6">

        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        @error('role')
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-800">{{ $message }}</div>
        @enderror

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Login gagal hari ini</p>
            <p class="mt-1 text-3xl font-bold {{ $failedToday > 0 ? 'text-red-600' : 'text-slate-800' }}">{{ $failedToday }}</p>
        </section>

        {{-- ============ PERAN PENGGUNA ============ --}}
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h3 class="text-base font-bold text-slate-800">Peran Pengguna (RBAC)</h3>
            <p class="mb-4 text-xs text-slate-500">
                Admin: audit keamanan, log aktivitas, ETL, dan mengubah semua inspeksi.
                Inspector: membuat dan mengubah inspeksi miliknya sendiri.
            </p>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500">
                        <tr><th class="py-2 pr-3">Nama</th><th class="py-2 pr-3">Email</th><th class="py-2">Peran</th></tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($users as $u)
                            <tr>
                                <td class="py-2 pr-3 font-semibold text-slate-800">{{ $u->name }}</td>
                                <td class="py-2 pr-3 text-slate-600">{{ $u->email }}</td>
                                <td class="py-2">
                                    <form method="POST" action="{{ route('security.users.role', $u) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="role" aria-label="Peran {{ $u->name }}"
                                                class="rounded-lg border-slate-300 py-1.5 text-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option value="admin" @selected($u->role === 'admin')>admin</option>
                                            <option value="inspector" @selected($u->role === 'inspector')>inspector</option>
                                        </select>
                                        <button type="submit"
                                                class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">
                                            Simpan
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        {{-- ============ TAMBAH PENGGUNA ============ --}}
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h3 class="text-base font-bold text-slate-800">Tambah Pengguna</h3>
            <p class="mb-4 text-xs text-slate-500">
                Registrasi publik dinonaktifkan. Akun baru hanya bisa dibuat oleh admin.
            </p>

            <form method="POST" action="{{ route('security.users.store') }}" class="grid gap-3 sm:grid-cols-2">
                @csrf

                <div>
                    <label for="new_name" class="text-xs font-semibold text-slate-600">Nama</label>
                    <input id="new_name" name="name" type="text" value="{{ old('name') }}" required autocomplete="off"
                           class="mt-1 w-full rounded-lg border-slate-300 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('name', 'createUser')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="new_email" class="text-xs font-semibold text-slate-600">Email</label>
                    <input id="new_email" name="email" type="email" value="{{ old('email') }}" required autocomplete="off"
                           class="mt-1 w-full rounded-lg border-slate-300 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('email', 'createUser')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="new_password" class="text-xs font-semibold text-slate-600">Password</label>
                    <input id="new_password" name="password" type="password" required autocomplete="new-password"
                           class="mt-1 w-full rounded-lg border-slate-300 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('password', 'createUser')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="new_password_confirmation" class="text-xs font-semibold text-slate-600">Konfirmasi Password</label>
                    <input id="new_password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                           class="mt-1 w-full rounded-lg border-slate-300 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label for="new_role" class="text-xs font-semibold text-slate-600">Peran</label>
                    <select id="new_role" name="role"
                            class="mt-1 w-full rounded-lg border-slate-300 py-2 text-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="inspector" @selected(old('role', 'inspector') === 'inspector')>inspector</option>
                        <option value="admin" @selected(old('role') === 'admin')>admin</option>
                    </select>
                    @error('role', 'createUser')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-end">
                    <button type="submit"
                            class="rounded-lg bg-[#17365D] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[#122B4D]">
                        Buat Akun
                    </button>
                </div>
            </form>
        </section>

        {{-- ============ LOG KEJADIAN ============ --}}
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <h3 class="text-base font-bold text-slate-800">Kejadian Keamanan</h3>

                <form method="GET" action="{{ route('security.audit') }}" class="flex items-center gap-2">
                    <select name="event" aria-label="Filter kejadian"
                            class="rounded-lg border-slate-300 py-1.5 text-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Semua kejadian</option>
                        @foreach (SecurityEvent::LABELS as $key => $label)
                            <option value="{{ $key }}" @selected(request('event') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button type="submit"
                            class="rounded-lg bg-[#17365D] px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-[#122B4D]">
                        Filter
                    </button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="py-2 pr-3">Waktu</th>
                            <th class="py-2 pr-3">Kejadian</th>
                            <th class="py-2 pr-3">Pengguna</th>
                            <th class="py-2 pr-3">IP</th>
                            <th class="py-2">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($events as $e)
                            <tr>
                                <td class="whitespace-nowrap py-2 pr-3 text-slate-600">{{ $e->created_at?->format('d/m/Y H:i:s') }}</td>
                                <td class="py-2 pr-3">
                                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $badge[$e->event] ?? 'bg-slate-100 text-slate-700' }}">
                                        {{ SecurityEvent::LABELS[$e->event] ?? $e->event }}
                                    </span>
                                </td>
                                <td class="py-2 pr-3 text-slate-700">{{ $e->user?->name ?? '-' }}</td>
                                <td class="py-2 pr-3 font-mono text-xs text-slate-600">{{ $e->ip_address ?? '-' }}</td>
                                <td class="py-2 text-slate-600">{{ $e->description }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-6 text-center text-slate-500">Belum ada kejadian tercatat.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $events->links() }}</div>
        </section>
    </div>
</x-app-layout>
