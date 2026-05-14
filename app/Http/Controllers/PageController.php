<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleSheetsService;
use Illuminate\Support\Facades\Log;

class PageController extends Controller
{
    /**
     * Exibe a página inicial
     */
    public function home()
    {
        return view('pages.home');
    }

    /**
     * Exibe a página da igreja
     */
    public function igreja()
    {
        return view('pages.igreja');
    }

    /**
     * Exibe a página de dízimos e ofertas
     */
    public function dizimosOfertas()
    {
        return view('pages.dizimos-ofertas');
    }

    /**
     * Exibe a página da escola sabatina
     */
    public function escolaSabatina()
    {
        return view('pages.escola-sabatina');
    }

    /**
     * Exibe a página de estudo bíblico
     */
    public function estudoBiblico()
    {
        return view('pages.estudo-biblico');
    }

    /**
     * Exibe a página de oração e visita
     */
    public function oracaoVisita()
    {
        return view('pages.oracao-visita');
    }

    /**
     * Exibe a página da Classe Novo Tempo
     */
    public function classeNovoTempo()
    {
        return view('pages.classe-novo-tempo');
    }

    /**
     * Exibe a página da Classe de Saúde
     */
    public function classeSaude()
    {
        return view('pages.classe-saude');
    }

    /**
     * Exibe a página do Clube do Livro
     */
    public function clubeDoLivro()
    {
        return view('pages.clube-do-livro');
    }

    /**
     * Exibe a página de Corais e Orquestras
     */
    public function corais()
    {
        return view('pages.corais');
    }

    /**
     * Exibe a página do CEMAB
     */
    public function cemab()
    {
        return view('pages.cemab');
    }

    /**
     * Exibe a página dos Doutores da Esperança
     */
    public function doutoresDaEsperanca()
    {
        return view('pages.doutores-da-esperanca');
    }

    /**
     * Exibe a página de Programações
     */
    public function programacoes()
    {
        return view('pages.programacoes');
    }

    /**
     * Exibe a página da CPB (Casa Publicadora Brasileira)
     */
    public function cpb()
    {
        return view('pages.cpb');
    }

    /**
     * Exibe a página da ASA (Ação Solidária Adventista)
     */
    public function asa()
    {
        return view('pages.asa');
    }

    /**
     * Exibe a página da Secretaria
     */
    public function secretaria()
    {
        return view('pages.secretaria');
    }

    /**
     * Exibe a página de Criacionismo
     */
    public function criacionismo()
    {
        return view('pages.criacionismo');
    }

    /**
     * Exibe a página de Evidências Bíblicas
     */
    public function evidenciasBiblicas()
    {
        return view('pages.evidencias-biblicas');
    }

    /**
     * Exibe a página de Filmes e Séries
     */
    public function filmesSeries()
    {
        return view('pages.filmes-series');
    }

    /**
     * Exibe a página de Profecias Bíblicas
     */
    public function profecias()
    {
        return view('pages.profecias');
    }

    /**
     * Exibe a página de Rádio e TV Novo Tempo
     */
    public function radioTvNovoTempo()
    {
        return view('pages.radio-tv-novo-tempo');
    }

    /**
     * Processa o formulário de contato
     */
    public function enviarContato(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefone' => 'required|string|max:20',
            'assunto' => 'required|string|max:255',
            'mensagem' => 'required|string|max:1000',
        ]);

        if (config('services.google_sheets.credentials_path') && config('services.google_sheets.spreadsheet_id')) {
            try {
                $sheets = app(GoogleSheetsService::class);
                $sheets->appendRow([
                    now()->format('Y-m-d H:i:s'),
                    'contato',
                    $validated['nome'],
                    $validated['email'],
                    $validated['telefone'],
                    $validated['assunto'],
                    $validated['mensagem'],
                ]);
            } catch (\Throwable $e) {
                Log::error('Falha ao gravar no Google Sheets (contato)', [
                    'message' => $e->getMessage(),
                ]);

                return back()
                    ->withErrors(['google_sheets' => 'Não foi possível registrar sua mensagem no momento. Tente novamente em alguns minutos.'])
                    ->withInput();
            }
        }

        return back()->with('success', 'Mensagem enviada com sucesso! Entraremos em contato em breve.');
    }

    /**
     * Processa o formulário de estudo bíblico
     */
    public function enviarEstudoBiblico(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefone' => 'required|string|max:20',
            'mensagem' => 'required|string|max:1000',
        ]);

        if (config('services.google_sheets.credentials_path') && config('services.google_sheets.spreadsheet_id')) {
            try {
                $sheets = app(GoogleSheetsService::class);
                $now = now();
                $sheets->appendRowToSheet('Estudo Bíblico', [
                    $now->format('d/m/Y'),
                    $now->format('H:i'),
                    $validated['nome'],
                    $validated['email'],
                    $validated['telefone'],
                    $validated['mensagem'],
                ]);
            } catch (\Throwable $e) {
                Log::error('Falha ao gravar no Google Sheets (estudo-biblico)', [
                    'message' => $e->getMessage(),
                ]);

                return back()
                    ->withErrors(['google_sheets' => 'Não foi possível registrar sua solicitação no momento. Tente novamente em alguns minutos.'])
                    ->withInput();
            }
        }

        return back()->with('success', 'Solicitação de estudo bíblico enviada com sucesso! Entraremos em contato em breve.');
    }

    /**
     * Processa o formulário de oração e visita
     */
    public function enviarOracaoVisita(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'telefone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'contato_pastor' => 'nullable|boolean',
            'pedido' => 'required|string|max:1000',
        ]);

        if (config('services.google_sheets.credentials_path') && config('services.google_sheets.spreadsheet_id')) {
            try {
                $sheets = app(GoogleSheetsService::class);
                $now = now();
                $sheets->appendRowToSheet('Oração e Visita', [
                    $now->format('d/m/Y'),
                    $now->format('H:i'),
                    $validated['nome'],
                    $validated['email'],
                    $validated['telefone'],
                    !empty($validated['contato_pastor']) ? 'sim' : 'nao',
                    $validated['pedido'],
                ]);
            } catch (\Throwable $e) {
                Log::error('Falha ao gravar no Google Sheets (oracao-visita)', [
                    'message' => $e->getMessage(),
                ]);

                return back()
                    ->withErrors(['google_sheets' => 'Não foi possível registrar seu pedido no momento. Tente novamente em alguns minutos.'])
                    ->withInput();
            }
        }

        return back()->with('success', 'Pedido de oração enviado com sucesso! Estaremos orando por você.');
    }

    /**
     * Processa o formulário de atualização de dados da secretaria
     */
    public function atualizarDadosSecretaria(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'telefone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'endereco' => 'nullable|string|max:500',
            'observacoes' => 'nullable|string|max:1000',
        ]);

        if (config('services.google_sheets.credentials_path') && config('services.google_sheets.spreadsheet_id')) {
            try {
                $sheets = app(GoogleSheetsService::class);
                $sheets->appendRow([
                    now()->format('Y-m-d H:i:s'),
                    'secretaria',
                    $validated['nome'],
                    $validated['email'],
                    $validated['telefone'],
                    $validated['endereco'] ?? '',
                    $validated['observacoes'] ?? '',
                ]);
            } catch (\Throwable $e) {
                Log::error('Falha ao gravar no Google Sheets (secretaria)', [
                    'message' => $e->getMessage(),
                ]);

                return back()
                    ->withErrors(['google_sheets' => 'Não foi possível registrar sua atualização no momento. Tente novamente em alguns minutos.'])
                    ->withInput();
            }
        }

        return back()->with('success', 'Dados atualizados com sucesso! Entraremos em contato em breve.');
    }

    /**
     * API para buscar vídeos do YouTube (ao vivo / próximo agendado + últimas transmissões encerradas)
     */
    public function getVideosYoutube()
    {
        $apiKey = 'AIzaSyB63pkSZDhSxyh7Fu7LbRSuRvS92NwT4dY';
        $channelId = 'UCk4NT38Kna-Fj5jyFtPwjQQ';

        $uploadsPlaylistId = 'UU' . substr($channelId, 2);
        $maxResults = 10;

        try {
            $context = stream_context_create([
                'http' => [
                    'ignore_errors' => true,
                    'timeout' => 10
                ]
            ]);

            $url = "https://www.googleapis.com/youtube/v3/playlistItems?key={$apiKey}&playlistId={$uploadsPlaylistId}&part=snippet&maxResults={$maxResults}";
            $response = @file_get_contents($url, false, $context);

            if ($response === false) {
                return response()->json(['error' => 'Não foi possível conectar à API do YouTube'], 500);
            }

            $data = json_decode($response, true);

            if (isset($data['error'])) {
                return response()->json(['error' => $data['error']['message'] ?? 'Erro na API do YouTube'], 500);
            }

            $videoIds = [];
            $basicInfo = [];

            if (isset($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $item) {
                    $videoId = $item['snippet']['resourceId']['videoId'] ?? null;
                    if ($videoId) {
                        $videoIds[] = $videoId;
                        $basicInfo[$videoId] = [
                            'id' => $videoId,
                            'title' => $item['snippet']['title'] ?? 'Sem título',
                            'thumbnail' => $item['snippet']['thumbnails']['medium']['url']
                                ?? $item['snippet']['thumbnails']['default']['url']
                                ?? ''
                        ];
                    }
                }
            }

            if (empty($videoIds)) {
                return response()->json([]);
            }

            $idsParam = implode(',', $videoIds);
            $detailsUrl = "https://www.googleapis.com/youtube/v3/videos?key={$apiKey}&id={$idsParam}&part=snippet,liveStreamingDetails";
            $detailsResponse = @file_get_contents($detailsUrl, false, $context);

            if ($detailsResponse === false) {
                return response()->json(array_values(array_slice($basicInfo, 0, 4)));
            }

            $detailsData = json_decode($detailsResponse, true);

            $live = [];
            $upcoming = [];
            $completed = [];

            foreach (($detailsData['items'] ?? []) as $item) {
                $vid = $item['id'];
                $info = $basicInfo[$vid] ?? null;
                if (!$info) continue;

                $broadcast = $item['snippet']['liveBroadcastContent'] ?? 'none';
                $liveDetails = $item['liveStreamingDetails'] ?? null;

                if ($broadcast === 'live') {
                    $info['scheduledStart'] = $liveDetails['scheduledStartTime'] ?? null;
                    $live[] = $info;
                } elseif ($broadcast === 'upcoming') {
                    $info['scheduledStart'] = $liveDetails['scheduledStartTime'] ?? null;
                    $upcoming[] = $info;
                } else {
                    $info['actualStart'] = $liveDetails['actualStartTime']
                        ?? $item['snippet']['publishedAt']
                        ?? null;
                    $completed[] = $info;
                }
            }

            usort($upcoming, fn($a, $b) =>
                strtotime($a['scheduledStart'] ?? '9999') - strtotime($b['scheduledStart'] ?? '9999')
            );

            usort($completed, fn($a, $b) =>
                strtotime($b['actualStart'] ?? '0') - strtotime($a['actualStart'] ?? '0')
            );

            $main = $live[0] ?? $upcoming[0] ?? $completed[0] ?? null;
            if (!$main) {
                return response()->json([]);
            }

            $thumbs = array_slice($completed, 0, 3);
            if ($main === ($completed[0] ?? null)) {
                $thumbs = array_slice($completed, 1, 3);
            }

            $result = array_merge([$main], $thumbs);

            $result = array_map(function ($v) {
                unset($v['scheduledStart'], $v['actualStart']);
                return $v;
            }, $result);

            return response()->json(array_values($result));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao carregar vídeos: ' . $e->getMessage()], 500);
        }
    }

    /**
     * API para buscar vídeos do canal Novo Tempo (@novotempo)
     */
    public function getVideosNovoTempo()
    {
        $apiKey = 'AIzaSyB63pkSZDhSxyh7Fu7LbRSuRvS92NwT4dY';
        $channelHandle = 'novotempo'; // Handle do canal sem o @
        $maxResults = 10;

        try {
            $context = stream_context_create([
                'http' => [
                    'ignore_errors' => true,
                    'timeout' => 10
                ]
            ]);

            // Primeiro, busca o channelId usando o handle
            $channelUrl = "https://www.googleapis.com/youtube/v3/channels?key={$apiKey}&forHandle={$channelHandle}&part=id,contentDetails";
            $channelResponse = @file_get_contents($channelUrl, false, $context);
            
            if ($channelResponse === false) {
                return response()->json(['error' => 'Não foi possível conectar à API do YouTube'], 500);
            }
            
            $channelData = json_decode($channelResponse, true);

            if (isset($channelData['error'])) {
                return response()->json(['error' => $channelData['error']['message'] ?? 'Erro na API do YouTube'], 500);
            }

            if (!isset($channelData['items']) || empty($channelData['items'])) {
                return response()->json(['error' => 'Canal não encontrado'], 404);
            }

            $channelId = $channelData['items'][0]['id'];
            
            // A playlist de uploads é "UU" + resto do ID do canal (substitui "UC" por "UU")
            $uploadsPlaylistId = 'UU' . substr($channelId, 2);

            // Usa PlaylistItems para pegar os vídeos da playlist de uploads
            $url = "https://www.googleapis.com/youtube/v3/playlistItems?key={$apiKey}&playlistId={$uploadsPlaylistId}&part=snippet&maxResults={$maxResults}";
            
            $response = @file_get_contents($url, false, $context);
            
            if ($response === false) {
                return response()->json(['error' => 'Não foi possível conectar à API do YouTube'], 500);
            }
            
            $data = json_decode($response, true);

            if (isset($data['error'])) {
                return response()->json(['error' => $data['error']['message'] ?? 'Erro na API do YouTube'], 500);
            }

            $videos = [];

            if (isset($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $item) {
                    $videoId = $item['snippet']['resourceId']['videoId'] ?? null;
                    if ($videoId) {
                        $videos[] = [
                            'id' => $videoId,
                            'title' => $item['snippet']['title'] ?? 'Sem título',
                            'thumbnail' => $item['snippet']['thumbnails']['medium']['url'] ?? $item['snippet']['thumbnails']['default']['url'] ?? ''
                        ];
                    }
                }
            }

            return response()->json($videos);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao carregar vídeos: ' . $e->getMessage()], 500);
        }
    }

    /**
     * API para buscar vídeos do canal Casa Publicadora (@casapublicadora)
     */
    public function getVideosCasaPublicadora()
    {
        $apiKey = 'AIzaSyB63pkSZDhSxyh7Fu7LbRSuRvS92NwT4dY';
        $channelHandle = 'casapublicadora'; // Handle do canal sem o @
        $maxResults = 10;

        try {
            $context = stream_context_create([
                'http' => [
                    'ignore_errors' => true,
                    'timeout' => 10
                ]
            ]);

            // Primeiro, busca o channelId usando o handle
            $channelUrl = "https://www.googleapis.com/youtube/v3/channels?key={$apiKey}&forHandle={$channelHandle}&part=id,contentDetails";
            $channelResponse = @file_get_contents($channelUrl, false, $context);
            
            if ($channelResponse === false) {
                return response()->json(['error' => 'Não foi possível conectar à API do YouTube'], 500);
            }
            
            $channelData = json_decode($channelResponse, true);

            if (isset($channelData['error'])) {
                return response()->json(['error' => $channelData['error']['message'] ?? 'Erro na API do YouTube'], 500);
            }

            if (!isset($channelData['items']) || empty($channelData['items'])) {
                return response()->json(['error' => 'Canal não encontrado'], 404);
            }

            $channelId = $channelData['items'][0]['id'];
            
            // A playlist de uploads é "UU" + resto do ID do canal (substitui "UC" por "UU")
            $uploadsPlaylistId = 'UU' . substr($channelId, 2);

            // Usa PlaylistItems para pegar os vídeos da playlist de uploads
            $url = "https://www.googleapis.com/youtube/v3/playlistItems?key={$apiKey}&playlistId={$uploadsPlaylistId}&part=snippet&maxResults={$maxResults}";
            
            $response = @file_get_contents($url, false, $context);
            
            if ($response === false) {
                return response()->json(['error' => 'Não foi possível conectar à API do YouTube'], 500);
            }
            
            $data = json_decode($response, true);

            if (isset($data['error'])) {
                return response()->json(['error' => $data['error']['message'] ?? 'Erro na API do YouTube'], 500);
            }

            $videos = [];

            if (isset($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $item) {
                    $videoId = $item['snippet']['resourceId']['videoId'] ?? null;
                    if ($videoId) {
                        $videos[] = [
                            'id' => $videoId,
                            'title' => $item['snippet']['title'] ?? 'Sem título',
                            'thumbnail' => $item['snippet']['thumbnails']['medium']['url'] ?? $item['snippet']['thumbnails']['default']['url'] ?? ''
                        ];
                    }
                }
            }

            return response()->json($videos);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao carregar vídeos: ' . $e->getMessage()], 500);
        }
    }

    /**
     * API para buscar vídeos do canal Provai e Vede (@provaievedeoficial)
     */
    public function getVideosProvaiEVede()
    {
        $apiKey = 'AIzaSyB63pkSZDhSxyh7Fu7LbRSuRvS92NwT4dY';
        $channelHandle = 'provaievedeoficial'; // Handle do canal sem o @
        $maxResults = 10;

        try {
            $context = stream_context_create([
                'http' => [
                    'ignore_errors' => true,
                    'timeout' => 10
                ]
            ]);

            // Primeiro, busca o channelId usando o handle
            $channelUrl = "https://www.googleapis.com/youtube/v3/channels?key={$apiKey}&forHandle={$channelHandle}&part=id,contentDetails";
            $channelResponse = @file_get_contents($channelUrl, false, $context);
            
            if ($channelResponse === false) {
                return response()->json(['error' => 'Não foi possível conectar à API do YouTube'], 500);
            }
            
            $channelData = json_decode($channelResponse, true);

            if (isset($channelData['error'])) {
                return response()->json(['error' => $channelData['error']['message'] ?? 'Erro na API do YouTube'], 500);
            }

            if (!isset($channelData['items']) || empty($channelData['items'])) {
                return response()->json(['error' => 'Canal não encontrado'], 404);
            }

            $channelId = $channelData['items'][0]['id'];
            
            // A playlist de uploads é "UU" + resto do ID do canal (substitui "UC" por "UU")
            $uploadsPlaylistId = 'UU' . substr($channelId, 2);

            // Usa PlaylistItems para pegar os vídeos da playlist de uploads
            $url = "https://www.googleapis.com/youtube/v3/playlistItems?key={$apiKey}&playlistId={$uploadsPlaylistId}&part=snippet&maxResults={$maxResults}";
            
            $response = @file_get_contents($url, false, $context);
            
            if ($response === false) {
                return response()->json(['error' => 'Não foi possível conectar à API do YouTube'], 500);
            }
            
            $data = json_decode($response, true);

            if (isset($data['error'])) {
                return response()->json(['error' => $data['error']['message'] ?? 'Erro na API do YouTube'], 500);
            }

            $videos = [];

            if (isset($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $item) {
                    $videoId = $item['snippet']['resourceId']['videoId'] ?? null;
                    if ($videoId) {
                        $videos[] = [
                            'id' => $videoId,
                            'title' => $item['snippet']['title'] ?? 'Sem título',
                            'thumbnail' => $item['snippet']['thumbnails']['medium']['url'] ?? $item['snippet']['thumbnails']['default']['url'] ?? ''
                        ];
                    }
                }
            }

            return response()->json($videos);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao carregar vídeos: ' . $e->getMessage()], 500);
        }
    }
}

