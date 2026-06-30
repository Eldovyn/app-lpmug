<?php

namespace App\Libraries;

class GoogleTranslate
{
    private $apiKey;
    private $cache = [];
    private $cacheFile;

    public function __construct()
    {
        $this->apiKey = getenv('apiKeyGoogleTranslateApi');

        if (empty($this->apiKey)) {
            log_message('error', 'Google Translate API key is not set. Please configure apiKeyGoogleTranslateApi in .env');
        }

        $this->cacheFile = WRITEPATH . 'cache/translate_cache.json';

        // Load cache from file
        if (file_exists($this->cacheFile)) {
            $this->cache = json_decode(file_get_contents($this->cacheFile), true) ?? [];
        }
    }

    /**
     * Translate text from source language to target language
     */
    public function translate(string $text, string $targetLang, string $sourceLang = 'id'): string
    {
        // If source and target are the same, return original text
        if ($sourceLang === $targetLang) {
            return $text;
        }

        // Check cache first
        $cacheKey = md5($text . $sourceLang . $targetLang);
        if (isset($this->cache[$cacheKey])) {
            return $this->cache[$cacheKey];
        }

        // If text is empty, return as is
        if (empty(trim($text))) {
            return $text;
        }

        try {
            $url = 'https://translation.googleapis.com/language/translate/v2';
            $data = [
                'q' => $text,
                'source' => $sourceLang,
                'target' => $targetLang,
                'format' => 'text'
            ];

            $ch = curl_init($url . '?key=' . $this->apiKey);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200) {
                $result = json_decode($response, true);
                if (isset($result['data']['translations'][0]['translatedText'])) {
                    $translatedText = $result['data']['translations'][0]['translatedText'];

                    // Save to cache
                    $this->cache[$cacheKey] = $translatedText;
                    $this->saveCache();

                    return $translatedText;
                }
            }

            // Log error if translation fails
            log_message('error', 'Google Translate API error: HTTP ' . $httpCode . ' - ' . $response);
        } catch (\Exception $e) {
            log_message('error', 'Google Translate error: ' . $e->getMessage());
        }

        // Return original text if translation fails
        return $text;
    }

    /**
     * Translate text based on current language (cookie or query parameter)
     */
    public function translateByCookie(string $text, string $sourceLang = 'id'): string
    {
        // First check query parameter, then cookie
        $targetLang = null;

        // Get current request to check query parameter
        $request = service('request');
        $queryLang = $request->getGet('lang');

        if (!empty($queryLang) && in_array(strtolower($queryLang), ['id', 'en'], true)) {
            $targetLang = strtolower($queryLang);
        } else {
            $targetLang = strtolower(get_cookie('lang')) ?: 'id';
        }

        return $this->translate($text, $targetLang, $sourceLang);
    }

    /**
     * Save cache to file
     */
    private function saveCache(): void
    {
        $cacheDir = WRITEPATH . 'cache';
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }
        file_put_contents($this->cacheFile, json_encode($this->cache));
    }

    /**
     * Clear translation cache
     */
    public function clearCache(): void
    {
        $this->cache = [];
        if (file_exists($this->cacheFile)) {
            unlink($this->cacheFile);
        }
    }
}
