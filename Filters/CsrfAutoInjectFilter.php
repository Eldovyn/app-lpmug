<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class CsrfAutoInjectFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // No before action needed
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Only inject in HTML responses
        $contentType = $response->getHeaderLine('Content-Type');
        if ($contentType && strpos($contentType, 'text/html') !== false) {
            $html = $response->getBody();

            // Inject CSRF meta tag in <head> for AJAX requests
            if (function_exists('csrf_meta')) {
                $csrfMeta = csrf_meta();
                $html = preg_replace('/(<head\b[^>]*>)/i', '$1' . "\n    " . $csrfMeta, $html);
            }

            // Inject CSRF hidden input field in all forms with method="POST"
            if (function_exists('csrf_field')) {
                $csrfField = csrf_field();
                // Match forms with method="post" or method='post' or method=post (case-insensitive)
                $html = preg_replace('/(<form\b[^>]*\bmethod=["\']?post\b[^>]*>)/i', '$1' . "\n" . $csrfField, $html);
            }

            $response->setBody($html);
        }
    }
}
