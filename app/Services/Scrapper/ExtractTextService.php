<?php

namespace App\Services\Scrapper;

use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class ExtractTextService
{
    public function execute($url) {
        try {
            $html = Http::get($url)->body();

            $crawler = new Crawler($html);

            $arrayText = $crawler->filter('p')->each(function (Crawler $node) {
                return trim($node->text());
            });

            $cleanedContent = trim(implode(' ', $arrayText));

            if ($cleanedContent === '') {
                return [
                    'status' => 'failed',
                    'content' => null,
                    'message' => 'No se pudo extraer contenido de la URL proporcionada.',
                ];
            }

            return [
                'status' => 'success',
                'content' => $cleanedContent,
                'message' => null,
            ];
        } catch (\Throwable $e) {
            return [
                'status' => 'failed',
                'content' => null,
                'message' => $e->getMessage(),
            ];
        }
    }
}
