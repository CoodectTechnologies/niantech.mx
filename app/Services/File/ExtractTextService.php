<?php

namespace App\Services\File;

use Exception;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;
use Spatie\PdfToText\Pdf;

class ExtractTextService
{
    public function execute($filePath) {
        try {
            $fullPath = Storage::path($filePath);
            $extractedContent = '';
            $message = null;

            try {
                $extractedContent = Pdf::getText($fullPath);
            } catch (Exception $e) {
                $message = $e->getMessage();
            }

            try {
                if (! $extractedContent) {
                    $parser = new Parser;
                    $pdf = $parser->parseFile($fullPath);
                    $extractedContent = $pdf->getText();
                }
            } catch (Exception $e) {
                $message = $message ?: $e->getMessage();
            }

            if ($extractedContent) {
                $cleanedContent = mb_convert_encoding($extractedContent, 'UTF-8', 'UTF-8');
                $cleanedContent = preg_replace('/[\x00-\x1F\x7F]/u', '', $cleanedContent);

                return [
                    'status' => 'success',
                    'content' => $cleanedContent,
                    'message' => null,
                ];
            }

            return [
                'status' => 'failed',
                'content' => null,
                'message' => $message ?: 'Unable to extract text from file.',
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
