<?php

namespace Tests\Feature;

use App\Http\Controllers\CommunionRequestController;
use App\Services\GoogleSheetsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CommunionRequestFormTest extends TestCase
{
    use RefreshDatabase;

    private function data(): array
    {
        return ['nome' => 'Participante de Teste', 'telefone' => '(61) 99999-0000',
            'endereco' => 'Endereço fictício de teste', 'adventista' => 'Não',
            'disponibilidade' => 'Teste: sábado à tarde'];
    }

    public function test_page_and_secretaria_card_are_available(): void
    {
        $response = $this->get(route('santa-ceia.formulario'))->assertOk()
            ->assertSee('A Ceia do Senhor em minha casa')
            ->assertSee('1 Coríntios 11:26')
            ->assertSee('Prepare-se com oração e exame de consciência ao longo da semana');
        foreach (array_keys($this->data()) as $field) {
            $this->assertMatchesRegularExpression('/<(?:input|textarea)\b[^>]*name="'.$field.'"[^>]*\brequired\b/', $response->getContent());
        }
        $this->get(route('secretaria'))->assertSee(route('santa-ceia.formulario'));
    }

    public function test_valid_request_writes_all_fields_in_order(): void
    {
        $data = $this->data();
        $this->mock(GoogleSheetsService::class, function (MockInterface $mock) use ($data) {
            $mock->shouldReceive('appendCommunionRequest')->once()->withArgs(fn ($headers, $row) =>
                $headers === CommunionRequestController::HEADERS && count($row) === 6 && array_slice($row, 1) === array_values($data));
            $mock->shouldNotReceive('appendAsaRegistration');
        });
        $this->post(route('santa-ceia.enviar'), $data)->assertRedirect(route('santa-ceia.formulario'))
            ->assertSessionHasNoErrors()->assertSessionHas('success');
    }

    public static function requiredFields(): array
    {
        return array_map(fn ($field) => [$field], ['nome', 'telefone', 'endereco', 'adventista', 'disponibilidade']);
    }

    #[DataProvider('requiredFields')]
    public function test_each_required_field_blocks_incomplete_requests(string $field): void
    {
        $this->mock(GoogleSheetsService::class, fn (MockInterface $mock) => $mock->shouldNotReceive('appendCommunionRequest'));
        $data = $this->data();
        $data[$field] = '   ';
        $this->from(route('santa-ceia.formulario'))->post(route('santa-ceia.enviar'), $data)
            ->assertSessionHasErrors($field)->assertSessionMissing('success');
    }

    public function test_invalid_phone_and_answer_are_rejected(): void
    {
        $this->mock(GoogleSheetsService::class, fn (MockInterface $mock) => $mock->shouldNotReceive('appendCommunionRequest'));
        $this->post(route('santa-ceia.enviar'), array_merge($this->data(), ['telefone' => '123', 'adventista' => 'Talvez']))
            ->assertSessionHasErrors(['telefone', 'adventista'])->assertSessionMissing('success');
    }

    public function test_failure_preserves_input_and_does_not_claim_success(): void
    {
        $this->mock(GoogleSheetsService::class, fn (MockInterface $mock) =>
            $mock->shouldReceive('appendCommunionRequest')->once()->andThrow(new \RuntimeException('indisponível')));
        $this->post(route('santa-ceia.enviar'), $this->data())->assertSessionHasErrors('envio')
            ->assertSessionHasInput('endereco', $this->data()['endereco'])->assertSessionMissing('success');
    }
}
