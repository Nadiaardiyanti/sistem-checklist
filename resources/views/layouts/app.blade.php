<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistem Checklist Inspeksi Gerai') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap"
          rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 font-sans antialiased text-slate-800">

    @php
        $user = Auth::user();
    @endphp

    <div x-data="{ sidebarOpen: false }" class="min-h-screen">

        {{-- Overlay untuk tampilan mobile --}}
        <div
            x-show="sidebarOpen"
            x-transition.opacity
            @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-slate-950/50 lg:hidden"
            style="display: none;"
        ></div>

        {{-- SIDEBAR --}}
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col
                   bg-[#17365D] text-white transition-transform duration-300
                   lg:translate-x-0"
        >

            {{-- Logo dan nama aplikasi --}}
            <div class="flex h-20 items-center gap-3 border-b border-white/10 px-6">

                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/10">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-7 w-7 text-white"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 12l2 2 4-4"/>
                    </svg>
                </div>

                <div>
                    <h1 class="text-base font-bold tracking-wide">
                        CHECKLIST
                    </h1>
                    <p class="mt-0.5 text-xs text-blue-200">
                        Inspeksi Gerai
                    </p>
                </div>

                <button
                    @click="sidebarOpen = false"
                    class="ml-auto rounded-lg p-2 text-blue-100 hover:bg-white/10 lg:hidden"
                    aria-label="Tutup menu"
                >
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

            </div>

            {{-- Navigasi --}}
            <div class="flex-1 overflow-y-auto px-4 py-7">

                <p class="mb-4 px-3 text-xs font-semibold uppercase tracking-widest text-blue-200/70">
                    Menu Utama
                </p>

                <nav class="space-y-2">

                    {{-- Dashboard --}}
                    <a href="{{ route('dashboard') }}"
                       @click="sidebarOpen = false"
                       class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition
                       {{ request()->routeIs('dashboard')
                            ? 'bg-white text-[#17365D] shadow-lg'
                            : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5 shrink-0"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v4h8V3h-8z"/>
                        </svg>

                        <span>Dashboard</span>
                    </a>

                    {{-- Inspeksi Gerai --}}
                    <a href="{{ route('inspections.index') }}"
                       @click="sidebarOpen = false"
                       class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition
                       {{ request()->routeIs('inspections.*')
                            ? 'bg-white text-[#17365D] shadow-lg'
                            : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5 shrink-0"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 12l2 2 4-4"/>
                        </svg>

                        <span>Inspeksi Gerai</span>
                    </a>

                    {{-- Log Aktivitas --}}
                    <a href="{{ route('activity-logs.index') }}"
                       @click="sidebarOpen = false"
                       class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition
                       {{ request()->routeIs('activity-logs.*')
                            ? 'bg-white text-[#17365D] shadow-lg'
                            : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5 shrink-0"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>

                        <span>Log Aktivitas</span>
                    </a>

                </nav>

                {{-- Informasi sidebar --}}
                <div class="mt-10 rounded-2xl border border-white/10 bg-white/5 p-4">
                    <p class="text-sm font-semibold text-white">
                        Inspeksi Lebih Teratur
                    </p>

                    <p class="mt-2 text-xs leading-5 text-blue-200">
                        Kelola checklist dan pantau hasil inspeksi gerai melalui satu aplikasi.
                    </p>
                </div>

            </div>

            {{-- Akun di bagian bawah sidebar --}}
            <div class="border-t border-white/10 p-4">

                <a href="{{ route('profile.edit') }}"
                   class="flex items-center gap-3 rounded-xl p-3 transition hover:bg-white/10">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white/15 text-sm font-bold text-white">
                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                    </div>

                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold text-white">
                            {{ $user->name ?? 'Pengguna' }}
                        </p>

                        <p class="truncate text-xs text-blue-200">
                            {{ $user->email ?? '' }}
                        </p>
                    </div>

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-4 w-4 shrink-0 text-blue-200"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>

                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit"
                            class="mt-2 flex w-full items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-blue-100 transition hover:bg-red-500/15 hover:text-white">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M18 15l3-3m0 0l-3-3m3 3H9"/>
                        </svg>

                        <span>Keluar</span>
                    </button>
                </form>

            </div>

        </aside>

        {{-- KONTEN UTAMA --}}
        <div class="min-h-screen lg:pl-72">

            {{-- HEADER --}}
            <header class="sticky top-0 z-30 flex min-h-24 items-center justify-between border-b border-slate-200 bg-white/95 px-5 py-4 backdrop-blur sm:px-8 sm:py-5">

                <div class="flex items-center gap-3">

                    <button
                        @click="sidebarOpen = true"
                        class="rounded-xl border border-slate-200 p-2 text-slate-600 hover:bg-slate-100 lg:hidden"
                        aria-label="Buka menu"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <div class="py-1">
    <p class="text-xs font-semibold tracking-wider text-slate-500">
        SISTEM MANAJEMEN
    </p>

    <h2 class="mt-2 text-xl font-bold leading-tight text-slate-800 sm:text-2xl">
    @isset($header)
        {{ $header }}
    @else
        Dashboard
    @endisset
</h2>

<p class="mt-1 text-sm text-slate-500">
    Ringkasan kegiatan inspeksi gerai
</p>
</div>

                </div>

                <div class="hidden items-center gap-3 sm:flex">

                    <div class="text-right">
                        <p class="text-sm font-semibold text-slate-700">
                            {{ $user->name ?? 'Pengguna' }}
                        </p>

                        <p class="text-xs text-slate-500">
                            Sistem Checklist Inspeksi Gerai
                        </p>
                    </div>

                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-50 font-bold text-[#17365D] ring-1 ring-blue-100">
                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                    </div>

                </div>

            </header>

            {{-- ISI HALAMAN --}}
            <main class="px-4 py-8 sm:px-6 sm:py-10 lg:px-8">
    <div class="mx-auto max-w-7xl">
        {{ $slot }}
    </div>
</main>

            {{-- FOOTER --}}
            <footer class="px-4 pb-6 text-center text-xs text-slate-400 sm:px-8">
                &copy; {{ date('Y') }} Sistem Checklist Inspeksi Gerai.
                All rights reserved.
            </footer>

        </div>

    </div>

</body>
</html>