<?php

namespace App\Services;

use Google\Client;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;

class GoogleSheetsService
{
    protected Sheets $service;
    protected string $spreadsheetId;

    public function __construct()
    {
        $client = new Client();

        $client->setApplicationName('Komersial BA');

        $client->setScopes([
            Sheets::SPREADSHEETS,
        ]);

        $credentialsPath = storage_path(
            env(
                'GOOGLE_SHEETS_CREDENTIALS',
                'app/google/credentials.json'
            )
        );

        $client->setAuthConfig($credentialsPath);

        $this->service = new Sheets($client);

        $this->spreadsheetId = env('GOOGLE_SHEETS_SPREADSHEET_ID');
    }

    /**
     * Menambahkan data baru ke Google Sheets.
     */
    public function append(
        string $sheetName,
        array $values
    ): void {
        $body = new ValueRange([
            'values' => [$values],
        ]);

        $this->service->spreadsheets_values->append(
            $this->spreadsheetId,
            $sheetName,
            $body,
            [
                'valueInputOption' => 'USER_ENTERED',
                'insertDataOption' => 'INSERT_ROWS',
            ]
        );
    }

    /**
     * Mencari nomor baris berdasarkan ID BA
     * yang berada di kolom A.
     */
    public function findRowById(
        string $sheetName,
        string|int $id
    ): ?int {
        $response = $this->service
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

            // Lewati header
            if ($index === 0) {
                continue;
            }

            if (isset($row[0]) && (string) $row[0] === (string) $id) {
                // Google Sheets menggunakan nomor baris mulai dari 1
                return $index + 1;
            }
        }

        return null;
    }

    /**
     * Mengupdate satu baris berdasarkan nomor baris.
     */
    public function updateRow(
        string $sheetName,
        int $rowNumber,
        array $values
    ): void {
        $lastColumn = $this->columnLetter(count($values));

        $range = $sheetName . '!A' . $rowNumber . ':' . $lastColumn . $rowNumber;

        $body = new ValueRange([
            'values' => [$values],
        ]);

        $this->service
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

    /**
     * Mencari ID BA lalu mengupdate datanya.
     */
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
     * Mengubah jumlah kolom menjadi huruf Excel/Google Sheets.
     *
     * Contoh:
     * 1  = A
     * 10 = J
     * 26 = Z
     * 27 = AA
     */
    private function columnLetter(int $columnNumber): string
    {
        $columnLetter = '';

        while ($columnNumber > 0) {
            $remainder = ($columnNumber - 1) % 26;

            $columnLetter = chr(65 + $remainder) . $columnLetter;

            $columnNumber = intdiv(
                $columnNumber - 1,
                26
            );
        }

        return $columnLetter;
    }
}