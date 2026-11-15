<?php

namespace Tests\Unit;

use App\Http\Controllers\AsaCourseController;
use App\Http\Controllers\CommunionRequestController;
use App\Services\GoogleSheetsService;
use Google\Client;
use Google\Service\Sheets;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class GoogleSheetsFormDestinationTest extends TestCase
{
    private array $history = [];

    private function service(array $responses): GoogleSheetsService
    {
        $handler = HandlerStack::create(new MockHandler(array_map(fn ($body) => new Response(200, ['Content-Type' => 'application/json'], json_encode($body)), $responses)));
        $handler->push(Middleware::history($this->history));
        $client = new Client();
        $client->setHttpClient(new \GuzzleHttp\Client(['handler' => $handler]));
        $service = (new \ReflectionClass(GoogleSheetsService::class))->newInstanceWithoutConstructor();
        (new \ReflectionProperty($service, 'sheets'))->setValue($service, new Sheets($client));

        return $service;
    }

    public static function destinations(): array
    {
        return [
            ['asa', 'appendAsaRegistration', AsaCourseController::HEADERS, 1449603265, 'Asa', 'E'],
            ['communion', 'appendCommunionRequest', CommunionRequestController::HEADERS, 443878736, 'Santa ceia', 'F'],
        ];
    }

    #[DataProvider('destinations')]
    public function test_only_the_configured_tab_is_written_as_literal_text(string $key, string $method, array $headers, int $gid, string $title, string $column): void
    {
        config(["services.google_sheets.{$key}_spreadsheet_id" => 'target-book', "services.google_sheets.{$key}_sheet_id" => $gid]);
        $service = $this->service([
            ['sheets' => [['properties' => ['sheetId' => 0, 'title' => 'Outra aba']], ['properties' => ['sheetId' => $gid, 'title' => $title]]]],
            [], [], ['updates' => ['updatedRows' => 1]],
        ]);
        $row = array_fill(0, count($headers), '=texto literal');
        $service->$method($headers, $row);
        $this->assertCount(4, $this->history);
        foreach (array_slice($this->history, 2) as $transaction) {
            $uri = $transaction['request']->getUri();
            $this->assertStringContainsString('target-book', (string) $uri);
            $this->assertStringContainsString("'{$title}'!", urldecode($uri->getPath()));
            $this->assertStringContainsString('valueInputOption=RAW', $uri->getQuery());
        }
        $this->assertStringContainsString("!A:{$column}:append", urldecode($this->history[3]['request']->getUri()->getPath()));
        $this->assertSame([$headers], json_decode((string) $this->history[2]['request']->getBody(), true)['values']);
        $this->assertSame([$row], json_decode((string) $this->history[3]['request']->getBody(), true)['values']);
    }

    public function test_existing_headers_are_not_overwritten(): void
    {
        $service = $this->service([
            ['sheets' => [['properties' => ['sheetId' => 443878736, 'title' => 'Santa ceia']]]],
            ['values' => [['Cabeçalho de dados existentes']]],
        ]);
        $this->expectException(\RuntimeException::class);
        $service->prepareWorksheet('target-book', 443878736, CommunionRequestController::HEADERS);
    }

    public function test_missing_tab_never_falls_back_to_another_tab(): void
    {
        $service = $this->service([['sheets' => [['properties' => ['sheetId' => 0, 'title' => 'Outra aba']]]]]);
        $this->expectException(\RuntimeException::class);
        $service->prepareWorksheet('target-book', 443878736, CommunionRequestController::HEADERS);
    }
}
