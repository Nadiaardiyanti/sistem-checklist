<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Daftar header keamanan: nama => [nilai, fungsi]. Dipakai middleware
     * dan ditampilkan di halaman Audit Keamanan.
     *
     * @return array<string, array{0: string, 1: string}>
     */
    public static function headers(?Request $request = null): array
    {
        $request ??= request();

        $headers = [
            'Content-Security-Policy'    => [self::csp(), 'Membatasi sumber skrip, gaya, gambar, dan form (anti XSS dan injeksi konten)'],
            'X-Frame-Options'            => ['SAMEORIGIN', 'Anti clickjacking'],
            'X-Content-Type-Options'     => ['nosniff', 'Anti MIME sniffing'],
            'Referrer-Policy'            => ['strict-origin-when-cross-origin', 'Membatasi informasi referrer'],
            'Permissions-Policy'         => ['camera=(), microphone=(), geolocation=()', 'Menonaktifkan fitur browser yang tidak diperlukan'],
            'Cross-Origin-Opener-Policy' => ['same-origin', 'Mengisolasi konteks jendela browser'],
        ];

        if ($request->isSecure()) {
            $headers['Strict-Transport-Security'] = ['max-age=31536000; includeSubDomains', 'Memaksa HTTPS'];
        }

        return $headers;
    }

    /**
     * CSP disesuaikan dengan aplikasi: Alpine.js (butuh unsafe-eval),
     * skrip/gaya inline pada beberapa halaman, dan font dari fonts.bunny.net.
     */
    public static function csp(): string
    {
        $script = "'self' 'unsafe-inline' 'unsafe-eval'";
        $style = "'self' 'unsafe-inline' https://fonts.bunny.net";
        $connect = "'self'";

        // Saat pengembangan (public/hot ada = `npm run dev` berjalan), izinkan server
        // Vite. Alamat IPv6 seperti [::1] tidak valid di sintaks CSP, jadi dipakai
        // skema umum http:/ws:. Tidak berlaku di production.
        if (! app()->isProduction() && is_file(public_path('hot'))) {
            $script .= ' http:';
            $style .= ' http:';
            $connect .= ' http: ws:';
        }

        return implode('; ', [
            "default-src 'self'",
            "script-src {$script}",
            "style-src {$style}",
            "font-src 'self' https://fonts.bunny.net data:",
            "img-src 'self' data: blob:",
            "connect-src {$connect}",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'self'",
        ]);
    }

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        foreach (self::headers($request) as $name => [$value]) {
            $response->headers->set($name, $value);
        }

        return $response;
    }
}