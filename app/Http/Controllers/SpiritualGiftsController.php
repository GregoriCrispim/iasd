<?php

namespace App\Http\Controllers;

use App\Services\SpiritualGiftsScorer;
use Illuminate\Http\Request;
use InvalidArgumentException;

class SpiritualGiftsController extends Controller
{
    private const DATA_PATH = 'data/spiritual-gifts';

    public function show()
    {
        return view('pages.teste-de-dons', [
            'questions' => $this->loadJson('questions.json'),
            'gifts' => $this->loadJson('gifts.json'),
            'texts' => $this->loadJson('result_texts.json'),
        ]);
    }

    public function result(Request $request, SpiritualGiftsScorer $scorer)
    {
        $questions = $this->loadJson('questions.json');
        $gifts = $this->loadJson('gifts.json');
        $texts = $this->loadJson('result_texts.json');

        // O browser envia um único campo JSON; nada de totais calculados no
        // cliente. Aqui validamos estrutura/valores e o scorer recalcula tudo
        // no servidor, sem confiar em qualquer pontuação recebida.
        $answers = json_decode((string) $request->input('answers_json', ''), true);

        if (! is_array($answers) || count($answers) !== 76) {
            return $this->redirectWithCalculationError($texts);
        }

        foreach ($answers as $value) {
            if (! is_numeric($value) || (string) (int) $value !== (string) $value
                || (int) $value < 0 || (int) $value > 3) {
                return $this->redirectWithCalculationError($texts);
            }
            // normaliza para inteiro antes de pontuar
        }
        $answers = array_map('intval', array_values($answers));

        try {
            $scored = $scorer->score($answers, $gifts['gifts']);
        } catch (InvalidArgumentException) {
            return $this->redirectWithCalculationError($texts);
        }

        $descriptions = collect($gifts['gifts'])->mapWithKeys(
            fn (array $gift) => [$gift['name'] => $gift['description']]
        );

        $results = collect($scored['results'])
            ->map(fn (array $row) => $row + ['description' => $descriptions->get($row['name'], '')]);

        return view('pages.teste-de-dons-resultado', [
            'questions' => $questions,
            'texts' => $texts,
            'scored' => $scored,
            'results' => $results,
        ]);
    }

    private function redirectWithCalculationError(array $texts)
    {
        return redirect()
            ->route('teste-de-dons')
            ->with('error', $texts['messages']['calculation_error']);
    }

    private function loadJson(string $file): array
    {
        $path = resource_path(self::DATA_PATH.'/'.$file);

        $decoded = json_decode((string) file_get_contents($path), true);

        if (! is_array($decoded)) {
            abort(500, "Não foi possível carregar os dados do teste de dons ({$file}).");
        }

        return $decoded;
    }
}
