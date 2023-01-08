<?php
use Cupcake\Lib\ClassRegistry;
use Cake\Core\Configure;
use Cake\Log\Log;


/**
 * Log configuration
 */
if (!Log::getConfig('shop')) {
    Log::setConfig('shop', [
        'className' => 'Cake\Log\Engine\FileLog',
        'path' => LOGS,
        'file' => 'shop',
        //'levels' => ['notice', 'info', 'debug'],
        'scopes' => ['shop', 'order', 'payment', 'invoice', 'checkout'],
    ]);
}

/**
 * Load default config
 */
//Configure::load('Shop.content');
Configure::load('Shop.html_editor');
//Configure::load('Shop.shop');
