<?php

namespace App\Http\Services\Post;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use DOMDocument;

class PostLinkPreviewService
{
    public function preview(string $url): array
    {
        $url = $this->normalizeUrl($url);

        $this->ensureSafeUrl($url);

        $response = Http::timeout(6)
            ->withHeaders([
                'User-Agent' => 'BlogexLinkPreview/1.0',
                'Accept' => 'text/html,application/xhtml+xml',
            ])
            ->get($url);

        if (!$response->successful()) {
            throw ValidationException::withMessages([
                'url' => 'Não foi possível acessar este link.',
            ]);
        }

        $contentType = $response->header('Content-Type', '');

        if (!Str::contains($contentType, 'text/html')) {
            throw ValidationException::withMessages([
                'url' => 'Este link não retornou uma página HTML.',
            ]);
        }

        $html = Str::limit($response->body(), 500000, '');

        return $this->extractPreview($html, $url);
    }

    private function extractPreview(string $html, string $url): array
    {
        $dom = new DOMDocument();

        libxml_use_internal_errors(true);

        $dom->loadHTML(
            '<?xml encoding="utf-8" ?>' . $html,
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        libxml_clear_errors();

        $title = $this->getMeta($dom, 'property', 'og:title') ?: $this->getTitle($dom) ?: $url;

        $description = $this->getMeta($dom, 'property', 'og:description') ?: $this->getMeta($dom, 'name', 'description');

        $image = $this->getMeta($dom, 'property', 'og:image') ?: $this->getMeta($dom, 'name', 'twitter:image');

        $siteName = $this->getMeta($dom, 'property', 'og:site_name');

        return [
            'url' => $url,
            'title' => Str::limit(trim($title), 120, ''),
            'description' => $description ? Str::limit(trim($description), 180, '') : null,
            'image' => $image ? $this->resolveUrl($image, $url) : null,
            'siteName' => $siteName ? Str::limit(trim($siteName), 80, '') : null,
            'domain' => parse_url($url, PHP_URL_HOST),
        ];
    }

    private function getTitle(DOMDocument $dom): ?string
    {
        $titles = $dom->getElementsByTagName('title');

        if ($titles->length === 0) {
            return null;
        }

        return $titles->item(0)?->textContent;
    }

    private function getMeta(DOMDocument $dom, string $attribute, string $value): ?string
    {
        foreach ($dom->getElementsByTagName('meta') as $meta) {
            if ($meta->getAttribute($attribute) !== $value) {
                continue;
            }

            $content = $meta->getAttribute('content');

            return $content ?: null;
        }

        return null;
    }

    private function normalizeUrl(string $url): string
    {
        $url = trim($url);

        if (!Str::startsWith($url, ['http://', 'https://'])) {
            $url = 'https://' . $url;
        }

        return $url;
    }

    private function resolveUrl(string $maybeRelativeUrl, string $baseUrl): string
    {
        if (Str::startsWith($maybeRelativeUrl, ['http://', 'https://'])) {
            return $maybeRelativeUrl;
        }

        $scheme = parse_url($baseUrl, PHP_URL_SCHEME);
        $host = parse_url($baseUrl, PHP_URL_HOST);

        if (Str::startsWith($maybeRelativeUrl, '//')) {
            return $scheme . ':' . $maybeRelativeUrl;
        }

        if (Str::startsWith($maybeRelativeUrl, '/')) {
            return $scheme . '://' . $host . $maybeRelativeUrl;
        }

        return $scheme . '://' . $host . '/' . ltrim($maybeRelativeUrl, '/');
    }

    private function ensureSafeUrl(string $url): void
    {
        $host = parse_url($url, PHP_URL_HOST);

        if (!$host) {
            throw ValidationException::withMessages([
                'url' => 'URL inválida.',
            ]);
        }

        if (in_array($host, ['localhost', '127.0.0.1', '::1'], true)) {
            throw ValidationException::withMessages([
                'url' => 'URL não permitida.',
            ]);
        }

        $records = dns_get_record($host, DNS_A + DNS_AAAA);

        foreach ($records as $record) {
            $ip = $record['ip'] ?? $record['ipv6'] ?? null;

            if (!$ip) {
                continue;
            }

            $isPublicIp = filter_var(
                $ip,
                FILTER_VALIDATE_IP,
                FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
            );

            if (!$isPublicIp) {
                throw ValidationException::withMessages([
                    'url' => 'URL não permitida.',
                ]);
            }
        }
    }
}
