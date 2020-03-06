<?php

namespace Shop\Controller;

use Cake\Event\Event;
use Content\Controller\Traits\PagesDisplayActionTrait;

class PagesController extends AppController
{
    use PagesDisplayActionTrait;

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow();

        if ($this->request->getParam('action') !== 'display') {
            $action = $this->request->getParam('action');
            $this->request->getParam('action') = 'display';
            $this->request->getParam('pass')[0] = $action;
        }
    }
}
