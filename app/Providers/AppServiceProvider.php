<?php

namespace App\Providers;

use App\Models\SecurityEvent;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // KSI - Autentikasi: kebijakan password kuat (dilonggarkan hanya saat testing).
        Password::defaults(function () {
            $rule = Password::min(8);

            return app()->environment('testing')
                ? $rule
                : Password::min(10)->mixedCase()->numbers()->symbols();
        });

        // KSI - Keamanan jaringan: paksa HTTPS di production.
        if (app()->isProduction()) {
            URL::forceScheme('https');
        }

        // KSI - Accounting: catat kejadian autentikasi ke tabel security_events.
        Event::listen(Login::class, function (Login $event) {
            SecurityEvent::record('login_success', $event->user->getAuthIdentifier(), 'Login berhasil.');
        });

        Event::listen(Failed::class, function (Failed $event) {
            $email = (string) ($event->credentials['email'] ?? '-');

            SecurityEvent::record(
                'login_failed',
                $event->user?->getAuthIdentifier(),
                'Percobaan login gagal untuk email: ' . mb_substr($email, 0, 100)
            );
        });

        Event::listen(Logout::class, function (Logout $event) {
            if ($event->user) {
                SecurityEvent::record('logout', $event->user->getAuthIdentifier(), 'Logout.');
            }
        });

        Event::listen(Lockout::class, function (Lockout $event) {
            SecurityEvent::record(
                'lockout',
                null,
                'Terlalu banyak percobaan login untuk email: ' . mb_substr((string) $event->request->input('email'), 0, 100),
                $event->request
            );
        });
    }
}
