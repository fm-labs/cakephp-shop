<?php

namespace Shop\Lib;


use Cake\Core\Configure;

class Shop
{
    static public function config($key = null)
    {
        if ($key === null) {
            $key = 'Shop';
        } elseif (!preg_match('/Shop\./', $key)) {
            $key = 'Shop.' . $key;
        }

        return Configure::read($key);
    }
}