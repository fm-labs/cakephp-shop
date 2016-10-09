<?php

namespace Shop\Controller\Admin;

use Cake\Core\Configure;
use Cake\Event\Event;
use Media\Lib\Media\MediaManager;
use App\Controller\Admin\AppController as BaseAdminAppController;

class AppController extends BaseAdminAppController
{
    public $locale;

    public $paginate = [
        'limit' => 100,
    ];

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $locale = $this->request->query('locale');
        $this->locale = ($locale) ? $locale : Configure::read('Shop.defaultLocale');
    }

    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);
        $this->set('locale', $this->locale);
    }

    protected function _getGalleryList()
    {
        $list = [];
        $mm = MediaManager::get('shop');
        $list = $mm->getSelectListRecursive();
        return $list;
    }

}
