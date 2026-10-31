<?php

namespace App\Models;

use App\Services\GoogleSheetsService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class InspectionActivityLog extends Model
{
    protected $fillable = [
        'inspection_id',
        'user_id',
        'activity',
        'description',
    ];

    /**
     * Setiap log baru otomatis dikirim ke Google Sheets.
     *
     * - Dikirim setelah respons ke pengguna selesai, jadi halaman tidak melambat.
     * - Kegagalan Sheets (internet putus, kuota, dll.) hanya dicatat di
     *   storage/logs dan tidak pernah mengganggu proses inspeksi.
     * - Kalau Google Sheets belum dikonfigurasi, langkah ini dilewati.
     */
    protected static function booted(): void
    {
        static::created(function (self $log) {
            app()->terminating(function () use ($log) {
                try {
                    $sheets = app(GoogleSheetsService::class);

                    if (! $sheets->isConfigured()) {
                        return;
                    }

                    // Lewati jika transaksi database ternyata dibatalkan.
                    if (! static::whereKey($log->id)->exists()) {
                        return;
                    }

                    $sheets->append(static::sheetName(), $log->toSheetRow());
                } catch (\Throwable $e) {
                    Log::warning(
                        'Gagal mengirim log aktivitas ke Google Sheets: ' . $e->getMessage(),
                        ['activity_log_id' => $log->id]
                    );
                }
            });
        });
    }

    public static function sheetName(): string
    {
        return env('GOOGLE_SHEETS_ACTIVITY_SHEET', 'LogAktivitas');
    }

    /**
     * Alamat spreadsheet untuk tombol "Buka di Spreadsheet".
     * Mengembalikan null jika ID spreadsheet belum diisi di .env.
     */
    public static function sheetUrl(): ?string
    {
        $id = env('GOOGLE_SHEETS_SPREADSHEET_ID');

        if (empty($id)) {
            return null;
        }

        return 'https://docs.google.com/spreadsheets/d/' . rawurlencode($id) . '/edit';
    }

    /** @return array<int, string> */
    public static function sheetHeader(): array
    {
        return ['ID', 'Waktu', 'Pengguna', 'Nomor Inspeksi', 'Aktivitas', 'Deskripsi'];
    }

    /** @return array<int, mixed> */
    public function toSheetRow(): array
    {
        $this->loadMissing('user', 'inspection');

        $inspectionNumber = $this->inspection?->inspection_number
            ?? ($this->inspection_id ? 'INS-' . $this->inspection_id : '-');

        return [
            $this->id,
            $this->created_at?->format('Y-m-d H:i:s') ?? '',
            static::safeCell($this->user?->name ?? '-'),
            static::safeCell($inspectionNumber),
            static::safeCell((string) $this->activity),
            static::safeCell((string) ($this->description ?? '')),
        ];
    }

    /**
     * Cegah teks yang diawali = + - @ dibaca sebagai rumus oleh Google Sheets.
     */
    protected static function safeCell(string $value): string
    {
        return preg_match('/^[=+\-@]/', $value) ? "'" . $value : $value;
    }

    public function inspection(): BelongsTo
    {
        return $this->belongsTo(Inspection::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}