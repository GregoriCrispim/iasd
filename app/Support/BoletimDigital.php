<?php

namespace App\Support;

class BoletimDigital
{
    /**
     * Raw boletim items (same source used by the boletim page and home cover grid).
     *
     * @return list<array{type: string, src: string, alt: string, title?: string|null, text?: string|null}>
     */
    public static function items(): array
    {
        static $items = null;

        if ($items !== null) {
            return $items;
        }

        $path = resource_path('data/boletim-current.php');
        $items = is_file($path) ? require $path : [];

        if (!is_array($items)) {
            $items = [];
        }

        return $items;
    }

    /**
     * Image paths relative to public/, for the home section background grid.
     * Uses a small unique set (repeated for density) so the wall stays rich
     * without downloading every boletim poster.
     *
     * @return list<string>
     */
    public static function coverImages(int $uniqueLimit = 12, int $tileCount = 20): array
    {
        $paths = [];

        foreach (self::items() as $item) {
            if (($item['type'] ?? '') !== 'image') {
                continue;
            }

            $src = $item['src'] ?? null;
            if (!is_string($src) || $src === '') {
                continue;
            }

            $paths[] = $src;
        }

        $paths = array_values(array_unique($paths));

        if ($paths === []) {
            return [];
        }

        $uniqueLimit = max(1, $uniqueLimit);
        $tileCount = max($uniqueLimit, $tileCount);

        // Keep variety while capping network requests: sample evenly across the feed.
        if (count($paths) > $uniqueLimit) {
            $sampled = [];
            $lastIndex = count($paths) - 1;
            for ($i = 0; $i < $uniqueLimit; $i++) {
                $sampled[] = $paths[(int) round(($i / max(1, $uniqueLimit - 1)) * $lastIndex)];
            }
            $paths = array_values(array_unique($sampled));
        }

        $tiles = [];
        $count = count($paths);
        for ($i = 0; $i < $tileCount; $i++) {
            $tiles[] = $paths[$i % $count];
        }

        return $tiles;
    }

    /**
     * Items with URLs linkified for the boletim page feed.
     *
     * @return list<array<string, mixed>>
     */
    public static function preparedItems(): array
    {
        $items = self::items();

        foreach ($items as &$item) {
            if (!empty($item['text']) && is_string($item['text'])) {
                $item['text'] = self::linkify($item['text']);
            }
        }
        unset($item);

        return $items;
    }

    /**
     * Two-column layout used by the boletim feed.
     *
     * @return array{0: list<array<string, mixed>>, 1: list<array<string, mixed>>}
     */
    public static function columns(): array
    {
        $columns = [[], []];

        foreach (self::preparedItems() as $index => $item) {
            $columns[$index % 2][] = $item;
        }

        return $columns;
    }

    public static function linkify(?string $text): ?string
    {
        if ($text === null || $text === '') {
            return $text;
        }

        return preg_replace(
            '~(https?://[^\s<]+)~',
            '<a href="$1" target="_blank" rel="noopener noreferrer">$1</a>',
            $text
        );
    }
}
