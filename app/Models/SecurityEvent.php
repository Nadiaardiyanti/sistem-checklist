<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SecurityEvent extends Model
{
    protected $fillable = [
        'user_id',
        'event',
        'ip_address',
        'user_agent',
        'description',
    ];

    public const LABELS = [
        'login_success' => 'Login berhasil',
        'login_failed'  => 'Login gagal',
        'logout'        => 'Logout',
        'lockout'       => 'Akun dikunci sementara (terlalu banyak percobaan)',
        'access_denied' => 'Akses ditolak (otorisasi)',
        'etl_run'       => 'Proses ETL dijalankan',
        'role_changed'  => 'Peran pengguna diubah',
        'user_created'  => 'Akun pengguna dibuat',
    ];

    /**
     * Catat satu kejadian keamanan. Tidak pernah melempar error
     * agar tidak mengganggu alur utama aplikasi.
     */
    public static function record(
        string $event,
        ?int $userId = null,
        ?string $description = null,
        ?Request $request = null
    ): void {
        try {
            $request ??= request();

            static::create([
                'user_id'     => $userId,
                'event'       => $event,
                'ip_address'  => $request?->ip(),
                'user_agent'  => Str::limit((string) $request?->userAgent(), 250, ''),
                'description' => $description,
            ]);
        } catch (\Throwable $e) {
            report($e);
        }
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
