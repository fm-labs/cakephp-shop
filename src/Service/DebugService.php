<?php
declare(strict_types=1);

namespace Shop\Service;

use Cake\Event\Event;

class DebugService extends BaseService
{
    public function implementedEvents(): array
    {
        return [
            'Controller.initialize' => 'beforeFilter',
            'Controller.startup' => 'startup',
            'Controller.beforeRender' => 'beforeRender',
            'Controller.beforeRedirect' => 'beforeRedirect',
            'Controller.shutdown' => 'shutdown',
        ];
    }

    public function beforeFilter(Event $event)
    {
        $this->_logEvent(__FUNCTION__, $event);
    }

    public function startup(Event $event)
    {
        $this->_logEvent(__FUNCTION__, $event);
    }

    public function beforeRender(Event $event)
    {
        $this->_logEvent(__FUNCTION__, $event);
    }

    public function beforeRedirect(Event $event)
    {
        $this->_logEvent(__FUNCTION__, $event);
    }

    public function shutdown(Event $event)
    {
        $this->_logEvent(__FUNCTION__, $event);
    }
}
