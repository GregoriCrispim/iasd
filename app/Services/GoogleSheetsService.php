<?php

namespace App\Services;

use Google\Client as GoogleClient;
use Google\Service\Sheets;
use Google\Service\Sheets\AddSheetRequest;
use Google\Service\Sheets\BatchUpdateSpreadsheetRequest;
use Google\Service\Sheets\Request as SheetsRequest;
use Google\Service\Sheets\ValueRange;
use Google\Service\Exception as GoogleServiceException;
use Illuminate\Support\Arr;
use RuntimeException;

class GoogleSheetsService
{
    private Sheets $sheets;
    private string $spreadsheetId;
    private string $sheetName;

    public function __construct()
    {
        $credentialsPath = (string) config('services.google_sheets.credentials_path', '');
        $this->spreadsheetId = (string) config('services.google_sheets.spreadsheet_id', '');
        $this->sheetName = (string) config('services.google_sheets.sheet_name', 'Respostas');

        if ($credentialsPath === '' || $this->spreadsheetId === '') {
            throw new RuntimeException('Google Sheets não configurado (credenciais ou spreadsheet id ausentes).');
        }

        $resolvedPath = $this->resolveCredentialsPath($credentialsPath);
        if (!is_file($resolvedPath)) {
            throw new RuntimeException('Arquivo de credenciais do Google Sheets não encontrado.');
        }

        $client = new GoogleClient();
        $client->setApplicationName((string) config('app.name', 'Laravel'));
        $client->setScopes([Sheets::SPREADSHEETS]);
        $client->setAuthConfig($resolvedPath);

        $this->sheets = new Sheets($client);
    }

    /**
     * @param  array<int, mixed>  $row
     */
    public function appendRow(array $row): void
    {
        $body = new ValueRange([
            'values' => [Arr::wrap($row)],
        ]);

        $params = [
            'valueInputOption' => 'USER_ENTERED',
            'insertDataOption' => 'INSERT_ROWS',
        ];

        try {
            $this->sheets->spreadsheets_values->append(
                $this->spreadsheetId,
                $this->rangeForSheet($this->sheetName),
                $body,
                $params
            );
        } catch (GoogleServiceException $e) {
            // Se o nome da aba estiver diferente do configurado (ex.: "Página1"),
            // tenta automaticamente a primeira aba do arquivo.
            $firstSheet = $this->getFirstSheetTitle();
            if ($firstSheet === '' || $firstSheet === $this->sheetName) {
                throw $e;
            }

            $this->sheets->spreadsheets_values->append(
                $this->spreadsheetId,
                $this->rangeForSheet($firstSheet),
                $body,
                $params
            );
        }
    }

    /**
     * @param  array<int, mixed>  $row
     */
    public function appendRowToSheet(string $sheetName, array $row): void
    {
        $sheetName = trim($sheetName);
        if ($sheetName === '') {
            throw new RuntimeException('Nome da aba do Google Sheets não pode ser vazio.');
        }

        $this->ensureSheetExists($sheetName);

        $body = new ValueRange([
            'values' => [Arr::wrap($row)],
        ]);

        $params = [
            'valueInputOption' => 'USER_ENTERED',
            'insertDataOption' => 'INSERT_ROWS',
        ];

        $this->sheets->spreadsheets_values->append(
            $this->spreadsheetId,
            $this->rangeForSheet($sheetName),
            $body,
            $params
        );
    }

    private function rangeForSheet(string $sheetName): string
    {
        $sheetName = trim($sheetName);
        if ($sheetName === '') {
            $sheetName = 'Página1';
        }

        // Sempre envolve com aspas simples para suportar espaços e caracteres especiais.
        // Aspas simples dentro do nome da aba precisam ser duplicadas.
        $escaped = str_replace("'", "''", $sheetName);
        return "'{$escaped}'!A:Z";
    }

    private function getFirstSheetTitle(): string
    {
        $spreadsheet = $this->sheets->spreadsheets->get($this->spreadsheetId);
        $sheets = $spreadsheet->getSheets();
        if (!is_array($sheets) || empty($sheets)) {
            return '';
        }

        $props = $sheets[0]->getProperties();
        return $props ? (string) $props->getTitle() : '';
    }

    private function ensureSheetExists(string $sheetTitle): void
    {
        $spreadsheet = $this->sheets->spreadsheets->get($this->spreadsheetId);
        $sheets = $spreadsheet->getSheets();

        if (is_array($sheets)) {
            foreach ($sheets as $sheet) {
                $props = $sheet->getProperties();
                if ($props && (string) $props->getTitle() === $sheetTitle) {
                    return;
                }
            }
        }

        $batch = new BatchUpdateSpreadsheetRequest([
            'requests' => [
                new SheetsRequest([
                    'addSheet' => new AddSheetRequest([
                        'properties' => [
                            'title' => $sheetTitle,
                        ],
                    ]),
                ]),
            ],
        ]);

        $this->sheets->spreadsheets->batchUpdate($this->spreadsheetId, $batch);
    }

    private function resolveCredentialsPath(string $path): string
    {
        $path = trim($path);
        if ($path === '') {
            return '';
        }

        // Se vier absoluto, usa direto. Se vier relativo, resolve a partir do base_path do projeto.
        if (str_starts_with($path, '/')) {
            return $path;
        }

        return base_path($path);
    }
}

