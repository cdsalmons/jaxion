<?php
namespace Intraxia\Jaxion\Register;

use Intraxia\Jaxion\Contract\Register\I18n as I18nContract;

class I18n implements I18nContract
{

    /**
     * Plugin path
     *
     * @var string
     */
    private $path;

    /**
     * Action hooks for the I18n service.
     *
     * @var array
     */
    public $actions = array(
        array(
            'hook' => 'after_setup_theme',
            'method' => 'loadTranslation',
        ),
    );

    /**
     * @inheritdoc
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * @inheritdoc
     */
    public function loadTranslation()
    {
        load_plugin_textdomain(
            basename($this->path),
            false,
            basename($this->path) . '/languages/'
        );
    }
}
