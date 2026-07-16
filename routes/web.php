<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Admin\UploadsController;
use App\Http\Controllers\Admin\CmsPreviewController;
use App\Http\Controllers\Admin\CmsCompareController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

// Login (gestão)
Route::middleware('guest')->get('/login', function () {
    return redirect('/admin/login');
})->name('login');

// Filament renders GET /admin/login. If JS/Livewire doesn't run, the form falls back to POST /admin/login.
// This handler makes the login work in that fallback scenario.
Route::middleware(['guest', 'throttle:10,1'])->post('/admin/login', function (Request $request) {
    $email = $request->input('email') ?? $request->input('login');

    $credentials = $request->validate([
        'password' => ['required', 'string'],
    ]);

    if (!is_string($email) || $email === '') {
        throw ValidationException::withMessages([
            'email' => 'O e-mail é obrigatório.',
        ]);
    }

    $remember = $request->boolean('remember');

    if (!Auth::attempt(['email' => $email, 'password' => $credentials['password']], $remember)) {
        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    $request->session()->regenerate();

    return redirect()->intended('/admin');
})
    ->withoutMiddleware([VerifyCsrfToken::class])
    ->name('admin.login.post');

Route::middleware(['auth', 'throttle:20,1'])->prefix('admin/uploads')->group(function () {
    Route::post('/image', [UploadsController::class, 'image'])->name('admin.uploads.image');
    Route::post('/file', [UploadsController::class, 'file'])->name('admin.uploads.file');
});

Route::middleware(['auth'])->get('/admin/cms/preview/{cmsRevision}', [CmsPreviewController::class, 'show'])
    ->name('admin.cms.preview');

Route::middleware(['auth'])->get('/admin/cms/compare/{cmsRevision}', [CmsCompareController::class, 'show'])
    ->name('admin.cms.compare');

// Sitemap
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Página inicial
Route::get('/', [PageController::class, 'home'])->name('home');

// Páginas do site
Route::get('/igreja', [PageController::class, 'igreja'])->name('igreja');
Route::get('/dizimos-ofertas', [PageController::class, 'dizimosOfertas'])->name('dizimos-ofertas');
Route::get('/escola-sabatina', [PageController::class, 'escolaSabatina'])->name('escola-sabatina');
Route::get('/estudo-biblico', [PageController::class, 'estudoBiblico'])->name('estudo-biblico');
Route::get('/estudo-biblico/formulario', [PageController::class, 'estudoBiblicoFormulario'])->name('estudo-biblico.formulario');
Route::get('/oracao-visita', [PageController::class, 'oracaoVisita'])->name('oracao-visita');
Route::get('/classe-novo-tempo', [PageController::class, 'classeNovoTempo'])->name('classe-novo-tempo');
Route::get('/classe-saude', [PageController::class, 'classeSaude'])->name('classe-saude');
Route::get('/clube-do-livro', [PageController::class, 'clubeDoLivro'])->name('clube-do-livro');
Route::get('/corais', [PageController::class, 'corais'])->name('corais');
Route::get('/cemab', [PageController::class, 'cemab'])->name('cemab');
Route::get('/doutores-da-esperanca', [PageController::class, 'doutoresDaEsperanca'])->name('doutores-da-esperanca');
Route::get('/programacoes', [PageController::class, 'programacoes'])->name('programacoes');
Route::view('/boletim-digital', 'pages.boletim-digital')->name('boletim-digital');
Route::get('/cpb', [PageController::class, 'cpb'])->name('cpb');
Route::get('/asa', [PageController::class, 'asa'])->name('asa');
Route::get('/secretaria', [PageController::class, 'secretaria'])->name('secretaria');
Route::get('/criacionismo', [PageController::class, 'criacionismo'])->name('criacionismo');
Route::get('/evidencias-biblicas', [PageController::class, 'evidenciasBiblicas'])->name('evidencias-biblicas');
Route::get('/filmes-series', [PageController::class, 'filmesSeries'])->name('filmes-series');
Route::get('/profecias', [PageController::class, 'profecias'])->name('profecias');
Route::get('/radio-tv-novo-tempo', [PageController::class, 'radioTvNovoTempo'])->name('radio-tv-novo-tempo');
Route::get('/ministerio-mulher', [PageController::class, 'ministerioMulher'])->name('ministerio-mulher');
Route::view('/desbravadores-cruzeiro-do-sul', 'pages.desbravadores-cruzeiro-do-sul')->name('desbravadores-cruzeiro-do-sul');
Route::view('/historia-desbravadores', 'pages.historia-desbravadores')->name('historia-desbravadores');
Route::view('/noticias/desbravadores-campori-2026', 'pages.noticia-desbravadores')->name('noticia-desbravadores');

// Time de desenvolvimento
Route::view('/time-de-desenvolvimento', 'pages.time-desenvolvimento')->name('time-desenvolvimento');
Route::view('/faq', 'pages.faq')->name('faq');

// Formulários
Route::post('/contato/enviar', [PageController::class, 'enviarContato'])->name('contato.enviar');
Route::post('/estudo-biblico/enviar', [PageController::class, 'enviarEstudoBiblico'])->name('estudo-biblico.enviar');
Route::post('/oracao-visita/enviar', [PageController::class, 'enviarOracaoVisita'])->name('oracao-visita.enviar');
Route::post('/secretaria/atualizar-dados', [PageController::class, 'atualizarDadosSecretaria'])->name('secretaria.atualizar-dados');

// API para vídeos do YouTube
Route::get('/api/videos-youtube', [PageController::class, 'getVideosYoutube'])->name('api.videos-youtube');
Route::get('/api/videos-novotempo', [PageController::class, 'getVideosNovoTempo'])->name('api.videos-novotempo');
Route::get('/api/videos-casapublicadora', [PageController::class, 'getVideosCasaPublicadora'])->name('api.videos-casapublicadora');
Route::get('/api/videos-provaievede', [PageController::class, 'getVideosProvaiEVede'])->name('api.videos-provaievede');
