<?php

namespace App\Services;

use Google\Cloud\Translate\V2\TranslateClient;

class TranslationService
{
    /**
     * @var TranslateClient|null
     */
    protected $client;

    /**
     * Constructor. Initializes the Google Translate client if API key exists.
     */
    public function __construct()
    {
        $apiKey = $_ENV['apiKeyGoogleTranslateApi'] ?? null;
        if ($apiKey) {
            try {
                $this->client = new TranslateClient([
                    'key' => $apiKey,
                ]);
            } catch (\Exception $e) {
                log_message('error', 'TranslationService initialization failed: ' . $e->getMessage());
                $this->client = null;
            }
        }
    }

    /**
     * Translate text using Google Translate API (Uncached).
     */
    public function translate(string $text, string $source, string $target, string $format = 'text'): string
    {
        $text = trim($text);
        if ($text === '' || $source === $target) {
            return $text;
        }

        if (!$this->client) {
            return $text;
        }

        try {
            $result = $this->client->translate($text, [
                'source' => $source,
                'target' => $target,
                'format' => $format,
            ]);
            return $result['text'] ?? $text;
        } catch (\Exception $e) {
            log_message('error', 'Translation failed: ' . $e->getMessage() . ' | Text: ' . substr($text, 0, 50));
            return $text;
        }
    }

    /**
     * Translate text with Cache support.
     */
    public function translateCached(string $text, string $source, string $target, string $format = 'text', int $ttl = 60 * 60 * 24 * 30): string
    {
        $text = trim($text);
        if ($text === '' || $source === $target) {
            return $text;
        }

        $cacheKey = 'gtr_' . md5($source . '|' . $target . '|' . $format . '|' . $text);
        $cache = cache();

        try {
            $cached = $cache->get($cacheKey);
            if (is_string($cached) && $cached !== '') {
                return $cached;
            }
        } catch (\Exception $e) {
            log_message('error', 'TranslationService cache read failed: ' . $e->getMessage());
        }

        $translated = $this->translate($text, $source, $target, $format);

        // Only cache if translation succeeded and returned non-empty
        if ($translated !== $text && $translated !== '') {
            try {
                $cache->save($cacheKey, $translated, $ttl);
            } catch (\Exception $e) {
                log_message('error', 'TranslationService cache write failed: ' . $e->getMessage());
            }
        }

        return $translated;
    }
}
