<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * @var \CodeIgniter\Database\BaseConnection
     */
    protected $db;

    /**
     * @var \CodeIgniter\Encryption\EncrypterInterface
     */
    protected $encrypter;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = ['custom', 'cookie'];

    /**
     * Language dictionary - can be overridden in child controllers
     */
    protected $dict = [
        'id' => [],
        'en' => []
    ];

    /**
     * Current language
     */
    protected $lang = 'id';

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
        session();
        $this->db = \Config\Database::connect();
        $this->encrypter = \Config\Services::encrypter();

        // Initialize language
        $this->initLanguage();
    }

    /**
     * Initialize language from cookie or query param
     */
    protected function initLanguage()
    {
        $allowed = ['id', 'en'];
        $this->lang = get_cookie('lang') ?: 'id';

        if (! in_array($this->lang, $allowed, true)) {
            $this->lang = 'id';
        }

        $reqLang = $this->request->getGet('lang');
        if ($reqLang && in_array($reqLang, $allowed, true)) {
            set_cookie('lang', $reqLang, 60 * 60 * 24 * 30);
            $this->lang = $reqLang;
        }
    }

    /**
     * Get translation for a key
     */
    protected function t($key, $dict = null)
    {
        $dict = $dict ?? $this->dict;
        return $dict[$this->lang][$key] ?? $dict['id'][$key] ?? $key;
    }

    /**
     * Get current language
     */
    protected function getLang()
    {
        return $this->lang;
    }

    /**
     * Translate text using Google Translate API (uncached).
     */
    protected function translateText(string $text, string $source, string $target, string $format = 'text'): string
    {
        return service('translation')->translate($text, $source, $target, $format);
    }

    /**
     * Translate text using Google Translate API (cached).
     */
    protected function translateTextCached(string $text, string $source, string $target, string $format = 'text'): string
    {
        return service('translation')->translateCached($text, $source, $target, $format);
    }

    /**
     * Safe cache remember helper.
     */
    protected function cacheRemember(string $key, int $ttlSeconds, callable $callback)
    {
        $cache = cache();
        $hit = $cache->get($key);

        if ($hit !== null) {
            return $hit;
        }

        $value = $callback();
        $cache->save($key, $value, $ttlSeconds);

        return $value;
    }
}
