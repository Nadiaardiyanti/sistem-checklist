@php
    $user = $user ?? auth()->user();

    // Warna avatar konsisten per pengguna (berdasarkan email)
    $avatarGradients = [
        'from-sky-400 to-blue-600',
        'from-emerald-400 to-teal-600',
        'from-violet-400 to-indigo-600',
        'from-amber-400 to-orange-600',
        'from-rose-400 to-pink-600',
        'from-cyan-400 to-blue-700',
    ];
    $avatarGradient = $avatarGradients[abs(crc32((string) $user->email)) % count($avatarGradients)];

    $initial = strtoupper(substr($user->name ?? 'U', 0, 1));
    $memberSince = $user->created_at?->locale('id')->translatedFormat('d F Y');
    $updatedAt = $user->updated_at?->locale('id')->translatedFormat('d F Y');

    // Buka tab Keamanan otomatis bila form password baru saja dikirim
    $startTab = ($errors->updatePassword->any() || session('status') === 'password-updated')
        ? 'security'
        : 'profile';
@endphp

<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-slate-800">
                Profile
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Kelola informasi akun Anda.
            </p>
        </div>
    </x-slot>

    <style>
        @@keyframes pf-rise {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .pf-rise { animation: pf-rise .5s cubic-bezier(.2, .7, .2, 1) both; animation-delay: var(--d, 0ms); }

        @@media (prefers-reduced-motion: reduce) {
            .pf-rise { animation: none; }
        }
    </style>

    <div>

        {{-- ==============================
             BANNER
        =============================== --}}
        <div class="relative h-36 overflow-hidden rounded-2xl bg-gradient-to-r from-[#17365D] via-[#204675] to-[#28558A] sm:h-44">
            <svg class="absolute inset-0 h-full w-full" viewBox="0 0 1200 220" preserveAspectRatio="xMidYMid slice" aria-hidden="true">
                <g fill="#ffffff">
                    <circle cx="140" cy="40" r="170" fill-opacity=".05"/>
                    <polygon points="420,0 640,0 530,170" fill-opacity=".06"/>
                    <polygon points="530,170 640,0 750,170" fill-opacity=".10"/>
                    <polygon points="750,0 960,0 855,150" fill-opacity=".05"/>
                    <polygon points="900,220 1030,60 1160,220" fill-opacity=".08"/>
                    <circle cx="1090" cy="40" r="120" fill-opacity=".06"/>
                </g>
            </svg>
        </div>

        {{-- ==============================
             KONTEN (menumpuk sedikit di atas banner)
        =============================== --}}
        <div class="relative z-10 -mt-16 grid items-start gap-6 px-2 sm:-mt-20 sm:px-4 lg:grid-cols-[280px_minmax(0,1fr)]">

            {{-- Kartu identitas --}}
            <section
                x-data="{
                    name: @js($user->name),
                    get initial() { return ((this.name || '').trim().charAt(0) || 'U').toUpperCase(); }
                }"
                x-on:profile-name.window="name = $event.detail"
                class="pf-rise overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
            >
                <div class="px-6 pb-6 pt-8 text-center">
                    <div class="relative mx-auto h-24 w-24">
                        <div class="flex h-full w-full items-center justify-center rounded-full bg-gradient-to-br {{ $avatarGradient }} text-4xl font-extrabold text-white shadow-lg ring-4 ring-white">
                            <span x-text="initial">{{ $initial }}</span>
                        </div>
                        <span class="absolute bottom-1 right-1 flex h-5 w-5 items-center justify-center rounded-full bg-white">
                            <span class="h-3.5 w-3.5 rounded-full bg-emerald-500"></span>
                        </span>
                    </div>

                    <h3 class="mt-4 break-words text-lg font-bold text-slate-800"
                        x-text="name">{{ $user->name }}</h3>
                    <p class="mt-0.5 break-all text-sm text-slate-500">{{ $user->email }}</p>
                </div>

                <dl class="border-t border-slate-100 text-sm">
                    <div class="flex items-center justify-between px-6 py-3.5">
                        <dt class="text-slate-500">Status</dt>
                        <dd>
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-700">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                Aktif
                            </span>
                        </dd>
                    </div>

                    @if ($memberSince)
                        <div class="flex items-center justify-between border-t border-slate-100 px-6 py-3.5">
                            <dt class="text-slate-500">Bergabung</dt>
                            <dd class="font-semibold text-slate-700">{{ $memberSince }}</dd>
                        </div>
                    @endif

                    @if ($updatedAt)
                        <div class="flex items-center justify-between border-t border-slate-100 px-6 py-3.5">
                            <dt class="text-slate-500">Diperbarui</dt>
                            <dd class="font-semibold text-slate-700">{{ $updatedAt }}</dd>
                        </div>
                    @endif
                </dl>

                <div class="border-t border-slate-100 p-5">
                    <a href="{{ route('dashboard') }}"
                       class="flex w-full items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:border-slate-300 hover:bg-slate-50 hover:text-slate-800">
                        Kembali ke Dashboard
                    </a>
                </div>
            </section>

            {{-- Kartu form bertab --}}
            <section
                x-data="{ tab: '{{ $startTab }}' }"
                style="--d: 80ms"
                class="pf-rise overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
            >
                <div class="border-b border-slate-200 px-6 sm:px-8">
                    <nav class="-mb-px flex gap-7" role="tablist">
                        <button type="button" role="tab"
                                x-on:click="tab = 'profile'"
                                :aria-selected="tab === 'profile'"
                                :class="tab === 'profile' ? 'border-[#17365D] text-[#17365D]' : 'border-transparent text-slate-500 hover:text-slate-800'"
                                class="border-b-2 py-4 text-sm font-semibold transition focus:outline-none focus-visible:text-blue-700">
                            Informasi Akun
                        </button>

                        <button type="button" role="tab"
                                x-on:click="tab = 'security'"
                                :aria-selected="tab === 'security'"
                                :class="tab === 'security' ? 'border-[#17365D] text-[#17365D]' : 'border-transparent text-slate-500 hover:text-slate-800'"
                                class="border-b-2 py-4 text-sm font-semibold transition focus:outline-none focus-visible:text-blue-700">
                            Ubah Password
                        </button>
                    </nav>
                </div>

                {{-- Panel: Informasi Akun --}}
                <div x-show="tab === 'profile'"
                     x-transition:enter="transition duration-300 ease-out"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     @if ($startTab !== 'profile') style="display: none;" @endif
                     class="px-6 py-7 sm:px-8">
                    @include('profile.partials.update-profile-information-form')
                </div>

                {{-- Panel: Ubah Password --}}
                <div x-show="tab === 'security'"
                     x-transition:enter="transition duration-300 ease-out"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     @if ($startTab !== 'security') style="display: none;" @endif
                     class="px-6 py-7 sm:px-8">
                    @include('profile.partials.update-password-form')
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
