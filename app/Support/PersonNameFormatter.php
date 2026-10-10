<?php

namespace App\Support;

class PersonNameFormatter
{
    /** Partículas que ficam em minúsculo (exceto se forem a primeira palavra). */
    private const PARTICLES = [
        'a', 'as', 'à', 'às',
        'o', 'os',
        'de', 'da', 'das', 'do', 'dos',
        'e', 'y',
        'em', 'na', 'nas', 'no', 'nos',
        'para', 'por',
        'di', 'du', 'del', 'della', 'van', 'von',
    ];

    public static function format(string $name): string
    {
        $name = trim(preg_replace('/\s+/u', ' ', $name) ?? '');
        if ($name === '') {
            return '';
        }

        $parts = preg_split('/(\s+|-+)/u', mb_strtolower($name, 'UTF-8'), -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        if ($parts === false) {
            return $name;
        }

        $formatted = '';
        $isFirstWord = true;

        foreach ($parts as $part) {
            if (preg_match('/^\s+$/u', $part) || $part === '-') {
                $formatted .= $part;
                continue;
            }

            if (! $isFirstWord && in_array($part, self::PARTICLES, true)) {
                $formatted .= $part;
            } else {
                $formatted .= self::capitalizeWord($part);
            }

            $isFirstWord = false;
        }

        return $formatted;
    }

    private static function capitalizeWord(string $word): string
    {
        // Apostrophe / aspas tipográficas: d'ávila → D'Ávila
        if (preg_match("/^([\p{L}]+)(['’])([\p{L}]+)$/u", $word, $matches)) {
            return self::mbUcfirst($matches[1]).$matches[2].self::mbUcfirst($matches[3]);
        }

        return self::mbUcfirst($word);
    }

    private static function mbUcfirst(string $value): string
    {
        $first = mb_strtoupper(mb_substr($value, 0, 1, 'UTF-8'), 'UTF-8');
        $rest = mb_substr($value, 1, null, 'UTF-8');

        return $first.$rest;
    }
}
