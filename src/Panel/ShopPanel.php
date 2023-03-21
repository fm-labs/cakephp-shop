<?php
declare(strict_types=1);

namespace Shop\Panel;


use Cake\Core\Configure;
use Cake\I18n\I18n;
use DebugKit\DebugPanel;

/**
 * @codeCoverageIgnore
 */
class ShopPanel extends DebugPanel
{
    public $plugin = 'Shop';

    /**
     * @return string
     */
    public function title()
    {
        return "Shop";
    }

    public function data()
    {
        return [];
    }

    /**
     * @return string
     */
    public function elementName()
    {
        return $this->plugin . '.DebugKit/shop_panel';
    }
}
