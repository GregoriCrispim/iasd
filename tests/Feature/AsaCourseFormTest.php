<?php

namespace Tests\Feature;

use App\Http\Controllers\AsaCourseController;
use App\Services\GoogleSheetsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class AsaCourseFormTest extends TestCase
{
    use RefreshDatabase;

    private function registration(): array
    {
        return [
            'nome' => 'Participante de Teste',
            'telefone' => '(61) 99999-0000',
            'curso' => 'Bordado à mão livre',
            'mensagem' => 'Inscrição fictícia para teste automatizado.',
        ];
    }

    public function test_form_shows_original_fields_and_all_six_options(): void
    {
        $response = $this->get(route('asa.formulario'))->assertOk();
        foreach (AsaCourseController::COURSES as $course) {
            $response->assertSee($course);
        }
        foreach (['nome', 'telefone', 'curso', 'mensagem'] as $field) {
            $response->assertSee('name="'.$field.'"', false);
        }
        $this->get(route('asa'))->assertSee(route('asa.formulario'));
    }

    public function test_valid_submission_writes_fields_in_header_order_and_confirms(): void
    {
        $data = $this->registration();
        $this->mock(GoogleSheetsService::class, function (MockInterface $mock) use ($data) {
            $mock->shouldReceive('appendAsaRegistration')->once()->withArgs(function ($headers, $row) use ($data) {
                return $headers === AsaCourseController::HEADERS
                    && preg_match('/^\d{2}\/\d{2}\/\d{4} \d{2}:\d{2}:\d{2}$/', $row[0])
                    && array_slice($row, 1) === array_values($data);
            });
        });

        $this->post(route('asa.enviar'), $data)
            ->assertRedirect(route('asa.formulario'))
            ->assertSessionHasNoErrors()->assertSessionHas('success');
    }

    public function test_required_fields_and_invalid_course_never_reach_sheets(): void
    {
        $this->mock(GoogleSheetsService::class, fn (MockInterface $mock) => $mock->shouldNotReceive('appendAsaRegistration'));
        $this->from(route('asa.formulario'))->post(route('asa.enviar'), [])
            ->assertSessionHasErrors(['nome', 'telefone', 'curso', 'mensagem'])->assertSessionMissing('success');
        $this->post(route('asa.enviar'), array_merge($this->registration(), ['curso' => 'Curso inexistente', 'telefone' => '123']))
            ->assertSessionHasErrors(['curso', 'telefone'])->assertSessionMissing('success');
    }

    public function test_sheets_failure_preserves_input_without_false_success(): void
    {
        $this->mock(GoogleSheetsService::class, function (MockInterface $mock) {
            $mock->shouldReceive('appendAsaRegistration')->once()->andThrow(new \RuntimeException('Sheets indisponível'));
        });

        $this->post(route('asa.enviar'), $this->registration())
            ->assertRedirect(route('asa.formulario'))->assertSessionHasErrors('envio')
            ->assertSessionHasInput('nome', 'Participante de Teste')->assertSessionMissing('success');
    }
}
