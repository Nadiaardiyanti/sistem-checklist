<?php

namespace App\Services;

use Google\Client;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;
use RuntimeException;

class GoogleSheetsService
{
    protected ?Sheets $service = null;
    protected string $spreadsheetId = '';

    public function __construct()
    {
        $credentialsPath = storage_path(
            env(
                'GOOGLE_SHEETS_CREDENTIALS',
                'app/google/credentials.json'
            )
        );

        $spreadsheetId = env('GOOGLE_SHEETS_SPREADSHEET_ID');

        // Jangan inisialisasi Google Sheets jika konfigurasi belum tersedia.
        if (
            !is_file($credentialsPath) ||
            empty($spreadsheetId)
        ) {
            return;
        }

        $client = new Client();

        $client->setApplicationName('SPS');
        $client->setScopes([
            Sheets::SPREADSHEETS,
        ]);

        $client->setAuthConfig($credentialsPath);

        $this->service = new Sheets($client);
        $this->spreadsheetId = $spreadsheetId;
    }

    protected function getService(): Sheets
    {
        if ($this->service === null) {
            throw new RuntimeException(
                'Google Sheets belum dikonfigurasi. Periksa credentials.json dan GOOGLE_SHEETS_SPREADSHEET_ID.'
            );
        }

        return $this->service;
    }

    public function append(
        string $sheetName,
        array $values
    ): void {
        $body = new ValueRange([
            'values' => [$values],
        ]);

        $this->getService()
            ->spreadsheets_values
            ->append(
                $this->spreadsheetId,
                $sheetName,
                $body,
                [
                    'valueInputOption' => 'USER_ENTERED',
                    'insertDataOption' => 'INSERT_ROWS',
                ]
            );
    }

    public function findRowById(
        string $sheetName,
        string|int $id
    ): ?int {
        $response = $this->getService()
            ->spreadsheets_values
            ->get(
                $this->spreadsheetId,
                $sheetName . '!A:A'
            );

        $values = $response->getValues();

        if (empty($values)) {
            return null;
        }

        foreach ($values as $index => $row) {
            if ($index === 0) {
                continue;
            }

            if (
                isset($row[0]) &&
                (string) $row[0] === (string) $id
            ) {
                return $index + 1;
            }
        }

        return null;
    }

    public function updateRow(
        string $sheetName,
        int $rowNumber,
        array $values
    ): void {
        $lastColumn = $this->columnLetter(count($values));

        $range = $sheetName
            . '!A' . $rowNumber
            . ':' . $lastColumn . $rowNumber;

        $body = new ValueRange([
            'values' => [$values],
        ]);

        $this->getService()
            ->spreadsheets_values
            ->update(
                $this->spreadsheetId,
                $range,
                $body,
                [
                    'valueInputOption' => 'USER_ENTERED',
                ]
            );
    }

    public function updateById(
        string $sheetName,
        string|int $id,
        array $values
    ): bool {
        $rowNumber = $this->findRowById(
            $sheetName,
            $id
        );

        if ($rowNumber === null) {
            return false;
        }

        $this->updateRow(
            $sheetName,
            $rowNumber,
            $values
        );

        return true;
    }

    /**
     * Apakah kredensial dan ID spreadsheet sudah terkonfigurasi.
     */
    public function isConfigured(): bool
    {
        return $this->service !== null;
    }

    /**
     * Baca seluruh isi kolom A pada sebuah sheet (termasuk baris header).
     *
     * @return array<int, string>
     */
    public function readColumn(string $sheetName): array
    {
        $response = $this->getService()
            ->spreadsheets_values
            ->get(
                $this->spreadsheetId,
                $sheetName . '!A:A'
            );

        return array_map(
            fn ($row) => (string) ($row[0] ?? ''),
            $response->getValues() ?? []
        );
    }

    /**
     * Tambahkan beberapa baris sekaligus dalam satu panggilan API.
     *
     * @param array<int, array<int, mixed>> $rows
     */
    public function appendMany(
        string $sheetName,
        array $rows
    ): void {
        if ($rows === []) {
            return;
        }

        $body = new ValueRange([
            'values' => array_values($rows),
        ]);

        $this->getService()
            ->spreadsheets_values
            ->append(
                $this->spreadsheetId,
                $sheetName,
                $body,
                [
                    'valueInputOption' => 'USER_ENTERED',
                    'insertDataOption' => 'INSERT_ROWS',
                ]
            );
    }

    private function columnLetter(
        int $columnNumber
    ): string {
        $columnLetter = '';

        while ($columnNumber > 0) {
            $remainder = ($columnNumber - 1) % 26;

            $columnLetter = chr(65 + $remainder)
                . $columnLetter;

            $columnNumber = intdiv(
                $columnNumber - 1,
                26
            );
        }

        return $columnLetter;
    }
}