@php
    $inputClass = 'block w-full rounded-xl border-slate-200 bg-white py-3 pl-11 pr-11 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100';
    $lockIcon = 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z';
@endphp

<section>
    <form
        method="post"
        action="{{ route('password.update') }}"
        class="grid items-start gap-5 sm:grid-cols-2"
        x-data="{
            pw: '',
            conf: '',
            showCur: false,
            showNew: false,
            showConf: false,
            get rules() {
                return [
                    { ok: this.pw.length >= 8, label: 'Minimal 8 karakter' },
                    { ok: /[a-z]/.test(this.pw) && /[A-Z]/.test(this.pw), label: 'Huruf besar & kecil' },
                    { ok: /[0-9]/.test(this.pw), label: 'Mengandung angka' },
                    { ok: /[^A-Za-z0-9]/.test(this.pw), label: 'Mengandung simbol' },
                ];
            },
            get score() { return this.pw.length ? this.rules.filter(r => r.ok).length : 0; },
            get label() { return ['', 'Lemah', 'Cukup', 'Baik', 'Kuat'][this.score]; },
            get barColor() { return ['', 'bg-rose-500', 'bg-amber-500', 'bg-sky-500', 'bg-emerald-500'][this.score]; },
            get textColor() { return ['', 'text-rose-600', 'text-amber-600', 'text-sky-600', 'text-emerald-600'][this.score]; },
            get matches() { return this.conf.length > 0 && this.conf === this.pw; }
        }"
    >
        @csrf
        @method('put')

        {{-- Password Saat Ini --}}
        <div class="sm:col-span-2 sm:w-1/2 sm:pr-2.5">
            <x-input-label
                for="update_password_current_password"
                value="Password Saat Ini"
                class="mb-2 !text-sm !font-semibold !text-slate-700"
            />

            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-[18px] w-[18px]" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $lockIcon }}"/>
                    </svg>
                </div>

                <input
                    id="update_password_current_password"
                    name="current_password"
                    :type="showCur ? 'text' : 'password'"
                    type="password"
                    autocomplete="current-password"
                    placeholder="Masukkan password saat ini"
                    class="{{ $inputClass }}"
                />

                <button type="button" x-on:click="showCur = !showCur"
                        class="absolute inset-y-0 right-0 flex items-center rounded-r-xl px-3.5 text-slate-400 transition hover:text-slate-700 focus:outline-none focus-visible:text-blue-700"
                        :aria-label="showCur ? 'Sembunyikan password' : 'Tampilkan password'">
                    @include('profile.partials.eye-icon', ['model' => 'showCur'])
                </button>
            </div>

            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        {{-- Password Baru + meter kekuatan --}}
        <div>
            <x-input-label
                for="update_password_password"
                value="Password Baru"
                class="mb-2 !text-sm !font-semibold !text-slate-700"
            />

            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-[18px] w-[18px]" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $lockIcon }}"/>
                    </svg>
                </div>

                <input
                    id="update_password_password"
                    name="password"
                    :type="showNew ? 'text' : 'password'"
                    type="password"
                    autocomplete="new-password"
                    placeholder="Masukkan password baru"
                    x-model="pw"
                    class="{{ $inputClass }}"
                />

                <button type="button" x-on:click="showNew = !showNew"
                        class="absolute inset-y-0 right-0 flex items-center rounded-r-xl px-3.5 text-slate-400 transition hover:text-slate-700 focus:outline-none focus-visible:text-blue-700"
                        :aria-label="showNew ? 'Sembunyikan password' : 'Tampilkan password'">
                    @include('profile.partials.eye-icon', ['model' => 'showNew'])
                </button>
            </div>

            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />

            {{-- Meter kekuatan --}}
            <div class="mt-3" x-show="pw.length > 0" x-transition.opacity style="display: none;">
                <div class="flex items-center gap-3">
                    <div class="grid flex-1 grid-cols-4 gap-1.5">
                        <template x-for="i in 4" :key="i">
                            <span class="h-1.5 rounded-full transition-colors duration-300"
                                  :class="score >= i ? barColor : 'bg-slate-200'"></span>
                        </template>
                    </div>
                    <span class="w-14 text-right text-xs font-bold" :class="textColor" x-text="label"></span>
                </div>

                <ul class="mt-3 grid grid-cols-1 gap-y-1.5">
                    <template x-for="r in rules" :key="r.label">
                        <li class="flex items-center gap-2 text-xs transition-colors"
                            :class="r.ok ? 'text-emerald-700' : 'text-slate-400'">
                            <span class="flex h-4 w-4 shrink-0 items-center justify-center rounded-full transition-colors"
                                  :class="r.ok ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-300'">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-2.5 w-2.5" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor" stroke-width="3.5" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </span>
                            <span x-text="r.label"></span>
                        </li>
                    </template>
                </ul>
            </div>
        </div>

        {{-- Konfirmasi Password --}}
        <div>
            <x-input-label
                for="update_password_password_confirmation"
                value="Konfirmasi Password Baru"
                class="mb-2 !text-sm !font-semibold !text-slate-700"
            />

            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-[18px] w-[18px]" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $lockIcon }}"/>
                    </svg>
                </div>

                <input
                    id="update_password_password_confirmation"
                    name="password_confirmation"
                    :type="showConf ? 'text' : 'password'"
                    type="password"
                    autocomplete="new-password"
                    placeholder="Masukkan ulang password baru"
                    x-model="conf"
                    class="{{ $inputClass }}"
                />

                <button type="button" x-on:click="showConf = !showConf"
                        class="absolute inset-y-0 right-0 flex items-center rounded-r-xl px-3.5 text-slate-400 transition hover:text-slate-700 focus:outline-none focus-visible:text-blue-700"
                        :aria-label="showConf ? 'Sembunyikan password' : 'Tampilkan password'">
                    @include('profile.partials.eye-icon', ['model' => 'showConf'])
                </button>
            </div>

            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />

            <p x-show="conf.length > 0" x-transition.opacity style="display: none;"
               class="mt-2 flex items-center gap-1.5 text-xs font-semibold"
               :class="matches ? 'text-emerald-600' : 'text-rose-500'"
               x-text="matches ? '✓ Password cocok' : 'Password belum cocok'"></p>
        </div>

        {{-- Tombol Simpan --}}
        <div class="flex flex-wrap items-center gap-3 border-t border-slate-100 pt-5 sm:col-span-2">
            <button
                type="submit"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#17365D] px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#204675] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $lockIcon }}"/>
                </svg>
                Simpan Password
            </button>

            @if (session('status') === 'password-updated')
                <span
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
                    Password berhasil diperbarui.
                </span>
            @endif
        </div>
    </form>
</section>
