<?php

namespace App\DTO\Integrations\VadetoBrands\Product;

use Illuminate\Support\Facades\Log;
use Throwable;

class ImageDTO
{
    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff', 'tif', 'webp', 'svg'];

    public function __construct(
        public readonly string $sku,
        public readonly string $language,
        public readonly array $urls
    ) {}

    public static function handle(array $images, string $language, string $sku): self {
        try {
            $sku = strval(trim($sku));
            $urls = [];
            foreach ($images as $image) {
                $url = $image['url'] ?? '';
                if (! $url || ! self::isValidImageUrl($url)) {
                    continue;
                }
                $urls[] = $url;
            }
            sort($urls);

            return new self(
                sku: $sku,
                language: $language,
                urls: $urls
            );
        } catch (Throwable $e) {
            Log::channel('vadeto_brands')->error('Error handling image DTO: '.$e->getMessage(), [
                'images' => $images,
                'sku' => $sku,
                'language' => $language,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            throw $e;
        }
    }
    public function toArray(): array {
        return [
            'sku' => $this->sku,
            'language' => $this->language,
            'urls' => $this->urls,
        ];
    }
    private static function isValidImageUrl(string $url): bool {
        $path = parse_url($url, PHP_URL_PATH) ?: '';
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        return in_array($extension, self::ALLOWED_EXTENSIONS);
    }
}
