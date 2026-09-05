<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SitemapController extends Controller
{
    public function index()
    {
        $pages = [
            url('/'),
            url('/igreja'),
            url('/estudo-biblico'),
            url('/estudo-biblico/formulario'),
            url('/escola-sabatina'),
            url('/programacoes'),
            url('/dizimos-ofertas'),
            url('/oracao-visita'),
            url('/asa'),
            url('/secretaria'),
            url('/profecias'),
            url('/criacionismo'),
            url('/evidencias-biblicas'),
            url('/filmes-series'),
            url('/radio-tv-novo-tempo'),
            url('/cpb'),
            url('/cemab'),
            url('/classe-novo-tempo'),
            url('/classe-saude'),
            url('/clube-do-livro'),
            url('/corais'),
            url('/doutores-da-esperanca'),
            url('/map'),
            url('/time-de-desenvolvimento'),
            url('/faq'),
        ];

        return response()->view('sitemap', compact('pages'))
            ->header('Content-Type', 'text/xml');
    }
}
