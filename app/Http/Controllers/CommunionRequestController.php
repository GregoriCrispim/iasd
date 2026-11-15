<?php

namespace App\Http\Controllers;

use App\Services\GoogleSheetsService;
use App\Support\SiteAnalytics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class CommunionRequestController extends Controller
{
    public const HEADERS = [
        'Carimbo de data/hora',
        'Nome completo',
        'Telefone',
        'Endereço',
        'Você é Adventista do Sétimo Dia?',
        'Melhor dia e horário para receber a Ceia?',
    ];

    public function show()
    {
        return view('pages.santa-ceia-formulario');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'telefone' => ['required', 'string', 'max:20', 'regex:/^(?:\([1-9][0-9]\)|[1-9][0-9])\s?[0-9]{4,5}-?[0-9]{4}$/'],
            'endereco' => ['required', 'string', 'max:1000'],
            'adventista' => ['required', Rule::in(['Sim', 'Não'])],
            'disponibilidade' => ['required', 'string', 'max:500'],
        ], [
            'nome.required' => 'Informe seu nome completo.',
            'telefone.required' => 'Informe seu telefone com DDD.',
            'telefone.regex' => 'Informe um telefone válido com DDD, como (61) 99999-9999.',
            'endereco.required' => 'Informe o endereço para receber a Ceia.',
            'adventista.required' => 'Informe se você é Adventista do Sétimo Dia.',
            'adventista.in' => 'Selecione Sim ou Não.',
            'disponibilidade.required' => 'Informe o melhor dia e horário para receber a Ceia.',
        ]);

        try {
            app(GoogleSheetsService::class)->appendCommunionRequest(self::HEADERS, [
                now('America/Sao_Paulo')->format('d/m/Y H:i:s'),
                $validated['nome'],
                $validated['telefone'],
                $validated['endereco'],
                $validated['adventista'],
                $validated['disponibilidade'],
            ]);
        } catch (\Throwable $e) {
            Log::error('Falha ao registrar solicitação de Santa Ceia', ['exception' => get_class($e), 'code' => $e->getCode()]);

            return redirect()->route('santa-ceia.formulario')
                ->withErrors(['envio' => 'Não foi possível registrar sua solicitação agora. Seus dados foram mantidos abaixo. Tente novamente em alguns minutos.'])
                ->withInput();
        }

        app(SiteAnalytics::class)->recordFormSubmission($request, 'santa-ceia');

        return redirect()->route('santa-ceia.formulario')
            ->with('success', 'Solicitação enviada com sucesso! Nossa equipe entrará em contato para combinar a visita.');
    }
}
