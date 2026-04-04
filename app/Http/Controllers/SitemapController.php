<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SitemapController extends Controller
{
    /**
     * Gera o sitemap.xml do site
     */
    public function index()
    {
        $pages = [
            [
                'url' => url('/'),
                'priority' => '1.0',
                'changefreq' => 'daily'
            ],
            [
                'url' => url('/igreja'),
                'priority' => '0.9',
                'changefreq' => 'weekly'
            ],
            [
                'url' => url('/estudo-biblico'),
                'priority' => '0.9',
                'changefreq' => 'weekly'
            ],
            [
                'url' => url('/escola-sabatina'),
                'priority' => '0.8',
                'changefreq' => 'weekly'
            ],
            [
                'url' => url('/programacoes'),
                'priority' => '0.9',
                'changefreq' => 'daily'
            ],
            [
                'url' => url('/dizimos-ofertas'),
                'priority' => '0.8',
                'changefreq' => 'monthly'
            ],
            [
                'url' => url('/oracao-visita'),
                'priority' => '0.9',
                'changefreq' => 'weekly'
            ],
            [
                'url' => url('/asa'),
                'priority' => '0.8',
                'changefreq' => 'weekly'
            ],
            [
                'url' => url('/secretaria'),
                'priority' => '0.8',
                'changefreq' => 'monthly'
            ],
            [
                'url' => url('/profecias'),
                'priority' => '0.7',
                'changefreq' => 'monthly'
            ],
            [
                'url' => url('/criacionismo'),
                'priority' => '0.7',
                'changefreq' => 'monthly'
            ],
            [
                'url' => url('/evidencias-biblicas'),
                'priority' => '0.7',
                'changefreq' => 'monthly'
            ],
            [
                'url' => url('/filmes-series'),
                'priority' => '0.6',
                'changefreq' => 'monthly'
            ],
            [
                'url' => url('/radio-tv-novo-tempo'),
                'priority' => '0.7',
                'changefreq' => 'weekly'
            ],
            [
                'url' => url('/cpb'),
                'priority' => '0.7',
                'changefreq' => 'monthly'
            ],
            [
                'url' => url('/cemab'),
                'priority' => '0.7',
                'changefreq' => 'weekly'
            ],
            [
                'url' => url('/classe-novo-tempo'),
                'priority' => '0.7',
                'changefreq' => 'weekly'
            ],
            [
                'url' => url('/classe-saude'),
                'priority' => '0.7',
                'changefreq' => 'weekly'
            ],
            [
                'url' => url('/clube-do-livro'),
                'priority' => '0.6',
                'changefreq' => 'monthly'
            ],
            [
                'url' => url('/corais'),
                'priority' => '0.6',
                'changefreq' => 'monthly'
            ],
            [
                'url' => url('/doutores-da-esperanca'),
                'priority' => '0.7',
                'changefreq' => 'weekly'
            ],
            [
                'url' => url('/time-desenvolvimento'),
                'priority' => '0.5',
                'changefreq' => 'yearly'
            ],
            [
                'url' => url('/faq'),
                'priority' => '0.8',
                'changefreq' => 'monthly'
            ],
        ];

        return response()->view('sitemap', compact('pages'))
            ->header('Content-Type', 'text/xml');
    }
}
