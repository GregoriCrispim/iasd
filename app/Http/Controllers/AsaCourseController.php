<?php

namespace App\Http\Controllers;

use App\Services\GoogleSheetsService;
use App\Support\SiteAnalytics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class AsaCourseController extends Controller
{
    public const COURSES = [
        'Bordado à mão livre',
        'Costura Criativa para adultos',
        'Costura Criativa para crianças',
        'Culinária vegetariana',
        'Tenho interesse em outros cursos (especificar)',
        'Preciso de Assistência Social da ASA',
    ];

    public const HEADERS = [
        'Carimbo de data/hora',
        'NOME COMPLETO',
        'TELEFONE COM DDD',
        'QUAL CURSO DESEJA FAZER?',
        'Ficou com alguma dúvida ou gostaria de deixar uma sugestão? Use o espaço abaixo.',
    ];

    public function show()
    {
        return view('pages.asa-formulario', ['courses' => self::COURSES]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'telefone' => ['required', 'string', 'max:20', 'regex:/^(?:\([1-9][0-9]\)|[1-9][0-9])\s?[0-9]{4,5}-?[0-9]{4}$/'],
            'curso' => ['required', Rule::in(self::COURSES)],
            'mensagem' => ['required', 'string', 'max:3000'],
        ], [
            'nome.required' => 'Informe seu nome completo.',
            'telefone.required' => 'Informe seu telefone com DDD.',
            'telefone.regex' => 'Informe um telefone válido com DDD, como (61) 99999-9999.',
            'curso.required' => 'Selecione o curso ou atendimento desejado.',
            'curso.in' => 'Selecione uma das opções disponíveis.',
            'mensagem.required' => 'Preencha o campo de dúvidas ou sugestões. Se não houver, escreva "Sem dúvidas".',
        ]);

        try {
            app(GoogleSheetsService::class)->appendAsaRegistration(self::HEADERS, [
                now('America/Sao_Paulo')->format('d/m/Y H:i:s'),
                $validated['nome'],
                $validated['telefone'],
                $validated['curso'],
                $validated['mensagem'],
            ]);
        } catch (\Throwable $e) {
            Log::error('Falha ao registrar inscrição ASA', ['exception' => get_class($e), 'code' => $e->getCode()]);

            return redirect()->route('asa.formulario')
                ->withErrors(['envio' => 'Não foi possível registrar sua inscrição agora. Seus dados foram mantidos abaixo. Tente novamente em alguns minutos.'])
                ->withInput();
        }

        app(SiteAnalytics::class)->recordFormSubmission($request, 'asa-cursos');

        return redirect()->route('asa.formulario')->with('success', 'Inscrição enviada com sucesso! Agradecemos por participar.');
    }
}
