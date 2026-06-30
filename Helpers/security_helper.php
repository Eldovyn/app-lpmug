<?php

if (!function_exists('sanitize_input')) {
    /**
     * Sanitizes plain text input by escaping HTML characters to prevent XSS.
     * Uses CodeIgniter's esc() function.
     *
     * @param string|array $data Data to be sanitized
     * @return string|array Sanitized data
     */
    function sanitize_input($data)
    {
        if (is_array($data)) {
            $sanitized = [];
            foreach ($data as $key => $value) {
                $sanitized[$key] = sanitize_input($value);
            }
            return $sanitized;
        }

        return esc($data, 'html');
    }
}

if (!function_exists('sanitize_html')) {
    /**
     * Sanitizes WYSIWYG HTML input using HTMLPurifier.
     * Removes malicious scripts while keeping allowed formatting tags.
     *
     * @param string $html HTML content to be sanitized
     * @return string Sanitized HTML
     */
    function sanitize_html($html)
    {
        if (empty($html)) {
            return $html;
        }

        // Initialize HTMLPurifier only when needed to save memory
        static $purifier = null;
        if ($purifier === null) {
            $config = \HTMLPurifier_Config::createDefault();
            
            // Konfigurasi tag yang diperbolehkan (sesuai kebutuhan WYSIWYG Summernote)
            $config->set('HTML.Allowed', 'p,b,strong,i,em,u,a[href|title|target],ul,ol,li,br,span[style],div[style],table,tbody,tr,td,th,h1,h2,h3,h4,h5,h6,img[src|alt|width|height|style]');
            
            // Konfigurasi cache
            $cachePath = WRITEPATH . 'cache/htmlpurifier';
            if (!is_dir($cachePath)) {
                mkdir($cachePath, 0777, true);
            }
            $config->set('Cache.SerializerPath', $cachePath);

            // Izinkan atribut target pada tag a (INI HARUS PALING BAWAH KARENA FINALISASI CONFIG)
            $def = $config->getHTMLDefinition(true);
            $def->addAttribute('a', 'target', 'Enum#_blank,_self,_target,_top');

            $purifier = new \HTMLPurifier($config);
        }

        return $purifier->purify($html);
    }
}
