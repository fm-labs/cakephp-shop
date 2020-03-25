<?php
declare(strict_types=1);

namespace Shop\Lib;

use Cake\Core\Configure;

/**
 * Class Shop
 *
 * @package Shop\Lib
 */
class Shop
{
    /**
     * Convenience wrapper to access Shop configuration
     *
     * @param null $key
     * @return mixed
     */
    public static function config($key = null)
    {
        if ($key === null) {
            $key = 'Shop';
        } elseif (!preg_match('/Shop\./', $key)) {
            $key = 'Shop.' . $key;
        }

        return Configure::read($key);
    }
}
