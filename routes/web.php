<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\GaleriaController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CmsPageController;
use App\Http\Controllers\Admin\CmsBlockController;
use App\Http\Controllers\Admin\CmsRevisionController;
use App\Http\Controllers\Admin\ApprovalsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UploadsController;
use App\Http\Controllers\Admin\CmsPreviewController;
use App\Http\Controllers\Admin\CmsCompareController;

/*
|--------------------------------------------------------------------------
| Painel administrativo (Blade)
|--------------------------------------------------------------------------
*/

// Autenticação
Route::middleware('guest')->group(function () {
    Route::get('/login', fn () => redirect()->route('admin.login'))->name('login');
    Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'login'])
        ->middleware('throttle:10,1')
        ->name('admin.login.post');
});

Route::post('/admin/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('admin.logout');

Route::middleware(['auth', 'role:super_admin,manager,collaborator'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Uploads (editor)
        Route::middleware('throttle:20,1')->group(function () {
            Route::post('/uploads/image', [UploadsController::class, 'image'])->name('uploads.image');
            Route::post('/uploads/file', [UploadsController::class, 'file'])->name('uploads.file');
        });

        // Preview / comparação de revisões
        Route::get('/cms/preview/{cmsRevision}', [CmsPreviewController::class, 'show'])->name('cms.preview');
        Route::get('/cms/compare/{cmsRevision}', [CmsCompareController::class, 'show'])->name('cms.compare');

        // Revisões (todos os papéis; escopo aplicado no controller)
        Route::get('/cms/revisions', [CmsRevisionController::class, 'index'])->name('revisions.index');
        Route::get('/cms/revisions/create', [CmsRevisionController::class, 'create'])->name('revisions.create');
        Route::post('/cms/revisions', [CmsRevisionController::class, 'store'])->name('revisions.store');
        Route::get('/cms/revisions/{revision}/edit', [CmsRevisionController::class, 'edit'])->name('revisions.edit');
        Route::put('/cms/revisions/{revision}', [CmsRevisionController::class, 'update'])->name('revisions.update');
        Route::post('/cms/revisions/{revision}/submit', [CmsRevisionController::class, 'submit'])->name('revisions.submit');
        Route::post('/cms/revisions/{revision}/approve-manager', [CmsRevisionController::class, 'approveManager'])->name('revisions.approveManager');
        Route::post('/cms/revisions/{revision}/approve-super', [CmsRevisionController::class, 'approveSuper'])->name('revisions.approveSuper');
        Route::post('/cms/revisions/{revision}/reject', [CmsRevisionController::class, 'reject'])->name('revisions.reject');
        Route::delete('/cms/revisions/{revision}', [CmsRevisionController::class, 'destroy'])->name('revisions.destroy');

        // Aprovações e usuários (super-admin e gestor)
        Route::middleware('role:super_admin,manager')->group(function () {
            Route::get('/cms/approvals', [ApprovalsController::class, 'index'])->name('approvals.index');
            Route::post('/cms/approvals/{revision}/approve', [ApprovalsController::class, 'approve'])->name('approvals.approve');
            Route::post('/cms/approvals/{revision}/reject', [ApprovalsController::class, 'reject'])->name('approvals.reject');

            Route::get('/users', [UserController::class, 'index'])->name('users.index');
            Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
            Route::post('/users', [UserController::class, 'store'])->name('users.store');
            Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
            Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

            Route::get('/users/{user}/pages', [UserController::class, 'pages'])->name('users.pages');
            Route::post('/users/{user}/pages', [UserController::class, 'attachPage'])->name('users.pages.attach');
            Route::put('/users/{user}/pages/{page}', [UserController::class, 'updatePage'])->name('users.pages.update');
            Route::delete('/users/{user}/pages/{page}', [UserController::class, 'detachPage'])->name('users.pages.detach');
        });

        // Somente super-admin
        Route::middleware('role:super_admin')->group(function () {
            Route::get('/cms/pages', [CmsPageController::class, 'index'])->name('pages.index');
            Route::post('/cms/pages/sync', [CmsPageController::class, 'sync'])->name('pages.sync');
            Route::get('/cms/pages/{page}/edit', [CmsPageController::class, 'edit'])->name('pages.edit');
            Route::put('/cms/pages/{page}', [CmsPageController::class, 'update'])->name('pages.update');

            Route::get('/cms/blocks', [CmsBlockController::class, 'index'])->name('blocks.index');
            Route::get('/cms/blocks/create', [CmsBlockController::class, 'create'])->name('blocks.create');
            Route::post('/cms/blocks', [CmsBlockController::class, 'store'])->name('blocks.store');
            Route::get('/cms/blocks/{block}/edit', [CmsBlockController::class, 'edit'])->name('blocks.edit');
            Route::put('/cms/blocks/{block}', [CmsBlockController::class, 'update'])->name('blocks.update');
            Route::delete('/cms/blocks/{block}', [CmsBlockController::class, 'destroy'])->name('blocks.destroy');
        });
    });

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

// Galeria de fotos (em testes - sem links no site ainda)
Route::get('/galeria', [GaleriaController::class, 'index'])->name('galeria');
// Miniatura via query string (evento/foto) para funcionar também com `php -S -t public`,
// que serve URLs terminadas em extensão (.webp) como arquivo estático antes de rotear.
Route::get('/galeria/thumb', [GaleriaController::class, 'thumb'])->name('galeria.thumb');
Route::get('/galeria/{evento}/download', [GaleriaController::class, 'download'])->name('galeria.download');
Route::get('/galeria/{evento}', [GaleriaController::class, 'show'])->name('galeria.show');

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
