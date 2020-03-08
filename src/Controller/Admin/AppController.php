<?php

namespace Shop\Controller\Admin;

use Content\Controller\Admin\AppController as ContentAdminController;

/**
 * Class AppController
 *
 * @package Shop\Controller\Admin
 */
class AppController extends ContentAdminController
{
    /**
     * @var array
     */
    public $paginate = [
        'limit' => 50,
    ];
}
