<?php

namespace Shop\Controller\Admin;

use Content\Controller\Admin\AppController as ContentAdminController;

class AppController extends ContentAdminController
{
    public $paginate = [
        'limit' => 50
    ];
}
