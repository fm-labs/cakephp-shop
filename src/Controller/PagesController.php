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

        if ($this->request->params['action'] !== 'display') {
            $action = $this->request->params['action'];
            $this->request->params['action'] = 'display';
            $this->request->params['pass'][0] = $action;
        }
    }
}