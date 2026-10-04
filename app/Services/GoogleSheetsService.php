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
use Illuminate\Support\Facades\Log;
use RuntimeException;
use GuzzleHttp\Client;

class GoogleSheetsService
{
    private Sheets $sheets;
    private string $spreadsheetId;
    private string $extraSpreadsheetId;
    private string $sheetName;

    public function __construct()
    {
        $credentialsPath = (string) config('services.google_sheets.credentials_path', '');
        $this->spreadsheetId = (string) config('services.google_sheets.spreadsheet_id', '');
        $this->extraSpreadsheetId = (string) config('services.google_sheets.spreadsheet_id_extra', '');
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

        // Configurar cliente HTTP para resolver problemas de SSL
        $client->setHttpClient(new \GuzzleHttp\Client([
            'verify' => false, // Desabilitar verificação SSL em ambiente de desenvolvimento
            'timeout' => 30,
        ]));

        $this->sheets = new Sheets($client);
    }

    /**
     * @param  array<int, mixed>  $row
     */
    public function appendRow(array $row): void
    {
        $this->appendPrimary($this->sheetName, $row);

        $this->appendExtra($this->sheetName, $row, 'appendRow');
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

        $this->ensureSheetExists($this->spreadsheetId, $sheetName);
        $this->appendValueRange($this->spreadsheetId, $sheetName, $row);

        $this->appendExtra($sheetName, $row, 'appendRowToSheet');
    }

    /**
     * Grava na planilha principal. Se o nome da aba estiver diferente do
     * configurado (ex.: "Página1"), tenta automaticamente a primeira aba.
     *
     * @param  array<int, mixed>  $row
     */
    private function appendPrimary(string $sheetName, array $row): void
    {
        try {
            $this->appendValueRange($this->spreadsheetId, $sheetName, $row);
        } catch (GoogleServiceException $e) {
            $firstSheet = $this->getFirstSheetTitle($this->spreadsheetId);
            if ($firstSheet === '' || $firstSheet === $sheetName) {
                throw $e;
            }

            $this->appendValueRange($this->spreadsheetId, $firstSheet, $row);
        }
    }

    /**
     * Grava também na planilha secundária (GOOGLE_SHEETS_SPREADSHEET_ID_EXTRA).
     * A aba é criada automaticamente se não existir. Falhas são registradas
     * no log sem interromper o envio para a planilha principal.
     *
     * @param  array<int, mixed>  $row
     */
    private function appendExtra(string $sheetName, array $row, string $context): void
    {
        if ($this->extraSpreadsheetId === '') {
            return;
        }

        try {
            $this->ensureSheetExists($this->extraSpreadsheetId, $sheetName);
            $this->appendValueRange($this->extraSpreadsheetId, $sheetName, $row);
        } catch (\Throwable $e) {
            Log::warning('Falha ao gravar na planilha secundária do Google Sheets', [
                'context' => $context,
                'spreadsheet_id' => $this->extraSpreadsheetId,
                'sheet' => $sheetName,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * @param  array<int, mixed>  $row
     */
    private function appendValueRange(string $spreadsheetId, string $sheetName, array $row): void
    {
        $body = new ValueRange([
            'values' => [Arr::wrap($row)],
        ]);

        $params = [
            'valueInputOption' => 'USER_ENTERED',
            'insertDataOption' => 'INSERT_ROWS',
        ];

        $this->sheets->spreadsheets_values->append(
            $spreadsheetId,
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

    private function getFirstSheetTitle(string $spreadsheetId): string
    {
        $spreadsheet = $this->sheets->spreadsheets->get($spreadsheetId);
        $sheets = $spreadsheet->getSheets();
        if (!is_array($sheets) || empty($sheets)) {
            return '';
        }

        $props = $sheets[0]->getProperties();
        return $props ? (string) $props->getTitle() : '';
    }

    private function ensureSheetExists(string $spreadsheetId, string $sheetTitle): void
    {
        $spreadsheet = $this->sheets->spreadsheets->get($spreadsheetId);
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

        $this->sheets->spreadsheets->batchUpdate($spreadsheetId, $batch);
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
