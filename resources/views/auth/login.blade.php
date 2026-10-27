<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4 py-10 sm:px-6">

        <div class="w-full max-w-5xl overflow-hidden rounded-2xl bg-white shadow-2xl lg:grid lg:grid-cols-2">

            {{-- PANEL KIRI --}}
            <div class="relative flex flex-col justify-between overflow-hidden bg-[#172554] p-8 text-white sm:p-12">

                {{-- Dekorasi --}}
                <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full border-[35px] border-white/5"></div>
                <div class="absolute -bottom-24 -left-20 h-72 w-72 rounded-full border-[40px] border-white/5"></div>

                <div class="relative z-10">
                    <div class="mb-12 flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white text-[#172554]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 3
                                    c-2.755 0-5.286.932-7.294 2.5
                                    L4 6v5c0 5.25 3.438 9.75 8 11
                                    4.562-1.25 8-5.75 8-11V6l-.382-.016z" />
                            </svg>
                        </div>

                        <div>
                            <h1 class="text-lg font-bold tracking-wide">INSPECTION SYSTEM</h1>
                            <p class="text-xs text-gray-300">Gerai Inspection Management</p>
                        </div>
                    </div>

                    <p class="mb-4 text-sm font-semibold uppercase tracking-[0.2em] text-gray-300">
                        Welcome Back
                    </p>

                    <h2 class="max-w-md text-3xl font-bold leading-tight sm:text-4xl">
                        Kelola Inspeksi Gerai dengan Lebih Mudah
                    </h2>

                    <p class="mt-5 max-w-md text-sm leading-7 text-gray-300 sm:text-base">
                        Pantau hasil pemeriksaan, dokumentasikan temuan, dan kelola
                        checklist inspeksi gerai dalam satu sistem yang terintegrasi.
                    </p>

                    <div class="mt-10 space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <span class="text-sm text-gray-200">Checklist inspeksi terstruktur</span>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 17v-2a4 4 0 018 0v2M9 7a3 3 0 106 0 3 3 0 00-6 0zM4 20h16" />
                                </svg>
                            </div>
                            <span class="text-sm text-gray-200">Dokumentasi hasil pemeriksaan</span>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 17v-2a4 4 0 018 0v2M12 3v4m0 0l-2-2m2 2l2-2M4 21h16" />
                                </svg>
                            </div>
                            <span class="text-sm text-gray-200">Pemantauan hasil inspeksi</span>
                        </div>
                    </div>
                </div>

                <div class="relative z-10 mt-12 border-t border-white/20 pt-5">
                    <p class="text-xs text-gray-300">
                        Sistem Checklist Inspeksi Gerai
                    </p>
                </div>
            </div>

            {{-- PANEL KANAN: FORM LOGIN --}}
            <div class="flex items-center justify-center p-8 sm:p-12 lg:p-14">

                <div class="w-full max-w-md">

                    <div class="mb-8">
                        <p class="text-sm font-semibold uppercase tracking-widest text-gray-500">
                            Account Access
                        </p>

                        <h2 class="mt-2 text-3xl font-bold text-[#172554]">
                            Masuk ke Akun
                        </h2>

                        <p class="mt-3 text-sm leading-6 text-gray-500">
                            Silakan masukkan email dan password untuk melanjutkan ke sistem.
                        </p>
                    </div>

                    {{-- Status Session --}}
                    <x-auth-session-status
                        class="mb-4"
                        :status="session('status')"
                    />

                    {{-- Form Login --}}
                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        {{-- Email --}}
                        <div>
                            <x-input-label
                                for="email"
                                value="Email"
                                class="mb-2 !text-sm !font-semibold !text-gray-700"
                            />

                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-gray-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>

                                <x-text-input
                                    id="email"
                                    class="block w-full rounded-lg border-gray-300 py-3 pl-11 pr-4 text-sm shadow-sm focus:border-[#172554] focus:ring-[#172554]"
                                    type="email"
                                    name="email"
                                    :value="old('email')"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    placeholder="nama@email.com"
                                />
                            </div>

                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        {{-- Password --}}
                        <div>
                            <x-input-label
                                for="password"
                                value="Password"
                                class="mb-2 !text-sm !font-semibold !text-gray-700"
                            />

                            <div class="relative">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 text-gray-400"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M7 10V7a5 5 0 0110 0v3m-11 0h12a2 2 0 012 2v7a2 2 0 01-2 2H6a2 2 0 01-2-2v-7a2 2 0 012-2z" />
                                    </svg>
                                </div>

                                <x-text-input
                                    id="password"
                                    class="block w-full rounded-lg border-gray-300 py-3 pl-11 pr-4 text-sm shadow-sm focus:border-[#172554] focus:ring-[#172554]"
                                    type="password"
                                    name="password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="Masukkan password"
                                />
                            </div>

                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        {{-- Remember Me dan Lupa Password --}}
                        <div class="flex flex-wrap items-center justify-between gap-3">

                            <label for="remember_me" class="inline-flex cursor-pointer items-center">
                                <input
                                    id="remember_me"
                                    type="checkbox"
                                    name="remember"
                                    class="rounded border-gray-300 text-[#172554] shadow-sm focus:ring-[#172554]"
                                >

                                <span class="ms-2 text-sm text-gray-600">
                                    Ingat saya
                                </span>
                            </label>

                            @if (Route::has('password.request'))
                                <a
                                    href="{{ route('password.request') }}"
                                    class="text-sm font-medium text-[#172554] transition hover:text-gray-500"
                                >
                                    Lupa password?
                                </a>
                            @endif
                        </div>

                        {{-- Tombol Login --}}
                        <button
                            type="submit"
                            class="flex w-full items-center justify-center gap-2 rounded-lg bg-[#172554] px-4 py-3.5 text-sm font-semibold text-white shadow-md transition duration-200 hover:bg-[#1E3A5F] focus:outline-none focus:ring-2 focus:ring-[#172554] focus:ring-offset-2"
                        >
                            Masuk ke Sistem

                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M5 12h14m-6-6l6 6-6 6" />
                            </svg>
                        </button>
                    </form>

                    <div class="mt-8 border-t border-gray-200 pt-5 text-center">
                        <p class="text-xs text-gray-400">
                            &copy; {{ date('Y') }} Sistem Checklist Inspeksi Gerai
                        </p>
                        <p class="mt-1 text-xs text-gray-400">
                            All rights reserved.
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-guest-layout>