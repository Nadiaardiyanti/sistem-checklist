<section>
    {{-- Form tersembunyi untuk kirim ulang verifikasi email --}}
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form
        method="post"
        action="{{ route('profile.update') }}"
        class="grid items-start gap-5 sm:grid-cols-2"
        x-data="{
            name: @js(old('name', $user->name)),
            email: @js(old('email', $user->email)),
            origName: @js($user->name),
            origEmail: @js($user->email),
            get dirty() { return this.name !== this.origName || this.email !== this.origEmail; },
            reset() {
                this.name = this.origName;
                this.email = this.origEmail;
                this.$dispatch('profile-name', this.origName);
            }
        }"
    >
        @csrf
        @method('patch')

        {{-- Nama Pengguna --}}
        <div>
            <x-input-label
                for="name"
                value="Nama Pengguna"
                class="mb-2 !text-sm !font-semibold !text-slate-700"
            />

            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-[18px] w-[18px]" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>

                <x-text-input
                    id="name"
                    name="name"
                    type="text"
                    class="block w-full rounded-xl border-slate-200 bg-white py-3 pl-11 pr-4 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    :value="old('name', $user->name)"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Masukkan nama pengguna"
                    x-model="name"
                    x-on:input="$dispatch('profile-name', $event.target.value)"
                />
            </div>

            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- Alamat Email --}}
        <div>
            <x-input-label
                for="email"
                value="Alamat Email"
                class="mb-2 !text-sm !font-semibold !text-slate-700"
            />

            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-[18px] w-[18px]" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 8l9 6 9-6M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                    </svg>
                </div>

                <x-text-input
                    id="email"
                    name="email"
                    type="email"
                    class="block w-full rounded-xl border-slate-200 bg-white py-3 pl-11 pr-4 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                    :value="old('email', $user->email)"
                    required
                    autocomplete="username"
                    placeholder="nama@perusahaan.com"
                    x-model="email"
                />
            </div>

            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        {{-- Email belum diverifikasi --}}
        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 sm:col-span-2">
                <p class="text-sm leading-6 text-amber-800">
                    Alamat email Anda belum diverifikasi.
                </p>

                <button
                    form="send-verification"
                    type="submit"
                    class="mt-2 text-sm font-semibold text-blue-700 underline underline-offset-4 hover:text-blue-900"
                >
                    Kirim ulang email verifikasi
                </button>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 text-sm font-medium text-emerald-700">
                        Tautan verifikasi baru telah dikirim.
                    </p>
                @endif
            </div>
        @endif

        {{-- Tombol --}}
        <div class="flex flex-wrap items-center gap-3 border-t border-slate-100 pt-5 sm:col-span-2">
            <button
                type="submit"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#17365D] px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#204675] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Perubahan
            </button>

            <button
                type="button"
                x-show="dirty"
                x-transition.opacity
                x-on:click="reset()"
                style="display: none;"
                class="rounded-xl px-4 py-3 text-sm font-semibold text-slate-500 transition hover:bg-slate-100 hover:text-slate-800"
            >
                Batalkan
            </button>

            <p
                x-show="dirty"
                x-transition.opacity
                style="display: none;"
                class="flex items-center gap-2 text-xs font-medium text-amber-600"
            >
                <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                Ada perubahan yang belum disimpan
            </p>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3500)"
                    class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1.5 text-sm font-medium text-emerald-700"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Perubahan profil berhasil disimpan.
                </p>
            @endif
        </div>
    </form>
</section>
