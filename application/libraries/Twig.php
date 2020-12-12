<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
* Part of CodeIgniter Simple and Secure Twig
*
* @author     Kenji Suzuki <https://github.com/kenjis>
* @license    MIT License
* @copyright  2015 Kenji Suzuki
* @link       https://github.com/kenjis/codeigniter-ss-twig
*/

use Twig\Environment;
use Twig\TwigFunction;
use Twig\Loader\FilesystemLoader;
use Twig\Extra\Intl\IntlExtension;

class Twig
{
    /**
     * @var array Paths to Twig templates
     */
    private $paths = [];

    /**
     * @var array Twig Environment Options
     * @see http://twig.sensiolabs.org/doc/api.html#environment-options
     */
    private $config = [];

    /**
     * @var array Functions to add to Twig
     */
    private $functions_asis = [
        'base_url', 'site_url',
    ];

    /**
     * @var array Functions with `is_safe` option
     * @see http://twig.sensiolabs.org/doc/advanced.html#automatic-escaping
     */
    private $functions_safe = [
        'form_open', 'form_close', 'form_error', 'form_hidden', 'set_value',
        'form_open_multipart', 'form_upload', 'form_submit', 'form_dropdown',
        'set_radio', 'set_select', 'set_checkbox',
    ];

    /**
     * @var bool Whether functions are added or not
     */
    private $functions_added = false;

    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @var Twig_Loader_Filesystem
     */
    private $loader;

    public function __construct($params = [])
    {
        if (empty($params)) {
            $CI = &get_instance();
            $params['twig'] = $CI->config->item('twig') ?? [];
        }
        if (isset($params['twig']['functions'])) {
            $this->functions_asis =
                array_unique(
                    array_merge($this->functions_asis, $params['twig']['functions'])
                );
            unset($params['twig']['functions']);
        }
        if (isset($params['twig']['functions_safe'])) {
            $this->functions_safe =
                array_unique(
                    array_merge($this->functions_safe, $params['twig']['functions_safe'])
                );
            unset($params['twig']['functions_safe']);
        }

        if (isset($params['twig']['paths'])) {
            $this->paths = $params['twig']['paths'];
            unset($params['twig']['paths']);
        } else {
            $this->paths = [VIEWPATH];
        }

        // default Twig config
        $this->config = [
            'cache' => APPPATH . 'cache/twig',
            'debug' => ENVIRONMENT !== 'production',
            'autoescape' => 'html',
            'auto_reload' => true,
        ];

        $this->config = array_merge($this->config, $params);
    }

    protected function resetTwig()
    {
        $this->twig = null;
        $this->createTwig();
    }

    protected function createTwig()
    {
        // $this->twig is singleton
        if ($this->twig !== null) {
            return;
        }

        if ($this->loader === null) {
            $this->loader = new FilesystemLoader($this->paths);
        }

        $twig = new Environment($this->loader, $this->config);
        
        if ($this->config['debug']) {
            $twig->addExtension(new Twig\Extension\DebugExtension());
        }
        $twig->addExtension(new IntlExtension());

        $this->twig = $twig;
    }

    protected function setLoader($loader)
    {
        $this->loader = $loader;
    }

    /**
     * Registers a Global
     *
     * @param string $name  The global name
     * @param mixed  $value The global value
     */
    public function addGlobal($name, $value)
    {
        $this->createTwig();
        $this->twig->addGlobal($name, $value);
    }

    /**
     * Renders Twig Template and Set Output
     *
     * @param string $view   Template filename without `.twig`
     * @param array  $params Array of parameters to pass to the template
     */
    public function display($view, $params = [])
    {
        $CI = &get_instance();
        $CI->output->set_output($this->render($view, $params));
    }

    /**
     * Renders Twig Template and Returns as String
     *
     * @param string $view   Template filename without `.twig`
     * @param array  $params Array of parameters to pass to the template
     * @return string
     */
    public function render($view, $params = [])
    {
        $this->createTwig();
        // We call addFunctions() here, because we must call addFunctions()
        // after loading CodeIgniter functions in a controller.
        $this->addFunctions();

        $view = $view . '.twig';

        return $this->twig->render($view, $params);
    }

    protected function addFunctions()
    {
        // Runs only once
        if ($this->functions_added) {
            return;
        }

        // as is functions
        foreach ($this->functions_asis as $function) {
            if (function_exists($function)) {
                $this->twig->addFunction(new TwigFunction($function, $function));
            }
        }

        // safe functions
        foreach ($this->functions_safe as $function) {
            if (function_exists($function)) {
                $this->twig->addFunction(new TwigFunction($function, $function, ['is_safe' => ['html']]));
            }
        }

        // customized functions
        if (function_exists('anchor')) {
            $this->twig->addFunction(new TwigFunction('anchor', [$this, 'safe_anchor'], ['is_safe' => ['html']]));
        }

        $this->functions_added = true;
    }

    /**
     * @param string $uri
     * @param string $title
     * @param array  $attributes [changed] only array is acceptable
     * @return string
     */
    public function safe_anchor($uri = '', $title = '', $attributes = [])
    {
        $uri = html_escape($uri);
        $title = html_escape($title);

        $new_attr = [];
        foreach ($attributes as $key => $val) {
            $new_attr[html_escape($key)] = html_escape($val);
        }

        return anchor($uri, $title, $new_attr);
    }

    /**
     * @return \Twig_Environment
     */
    public function getTwig()
    {
        $this->createTwig();

        return $this->twig;
    }
}