<?php namespace Intraxia\Jaxion\Core;

use Intraxia\Jaxion\Contract\Core\Application as ApplicationContract;
use Intraxia\Jaxion\Http\Router;
use Intraxia\Jaxion\Register\I18n;
use Closure;
use WP_CLI;

/**
 * Class Application
 * @package Intraxia\Jaxion
 */
class Application extends Container implements ApplicationContract
{
    /**
     * Singleton instance of the Application object
     *
     * @var Application
     */
    protected static $instance = null;

    /**
     * {@inheritdoc}
     *
     * @param string $file
     * @throws ApplicationAlreadyBootedException
     */
    public function __construct($file)
    {
        if (static::$instance !== null) {
            throw new ApplicationAlreadyBootedException;
        }

        static::$instance = $this;

        $this->registerDefaultConstants($file);
        $this->registerDefaultServices();

        register_activation_hook($file, array($this, 'activate'));
        register_deactivation_hook($file, array($this, 'deactivate'));
    }

    /**
     * {@inheritDoc}
     */
    public function boot()
    {
        $this->routes($this['Router']);
        $this['Loader']->register();
    }

    /**
     * {@inheritDoc}
     *
     * @codeCoverageIgnore
     * @param object $router
     */
    public function routes($router)
    {
        // no-op
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public function activate()
    {
        // no-op
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public function deactivate()
    {
        // no-op
    }
    /**
     * {@inheritdoc}
     *
     * @param string  $name
     * @param Closure $class
     */
    public function command($name, Closure $class)
    {
        if (defined('WP_CLI') && WP_CLI) {
            WP_CLI::add_command($name, $class($this));
        }
    }

    /**
     * {@inheritDoc}
     *
     * @return Application
     * @throws ApplicationNotBootedException
     */
    public static function get()
    {
        if (static::$instance === null) {
            throw new ApplicationNotBootedException;
        }

        return static::$instance;
    }

    /**
     * {@inheritDoc}
     */
    public static function shutdown()
    {
        if (static::$instance !== null) {
            static::$instance = null;
        }
    }

    /**
     * Sets the plugin's url, path, and basename.
     *
     * @param string $file
     */
    private function registerDefaultConstants($file)
    {
        $this['url']      = plugin_dir_url($file);
        $this['path']     = plugin_dir_path($file);
        $this['basename'] = plugin_basename($file);
    }

    /**
     * Registers the built-in services with the Application container.
     */
    private function registerDefaultServices()
    {
        $this['I18n'] = function ($app) {
            return new I18n($app['path']);
        };

        $this['Loader'] = function ($app) {
            return new Loader($app);
        };

        $this['Router'] = function () {
            return new Router();
        };
    }
}
