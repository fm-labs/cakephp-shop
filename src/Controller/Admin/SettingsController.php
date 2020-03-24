<?php

namespace Shop\Controller\Admin;

use Cake\Event\Event;
use Settings\Form\SettingsForm;
use Settings\SettingsManager;

/**
 * Class SettingsController
 *
 * @package Banana\Controller\Admin
 *
 */
class SettingsController extends AppController
{
    /**
     * @var SettingsManager
     */
    public $_settingsManager;

    /**
     * @return SettingsManager
     */
    public function settingsManager()
    {
        if (!$this->_settingsManager) {
            $manager = new SettingsManager();
            //$this->getEventManager()->dispatch(new Event('Settings.build', null, ['manager' => $manager]));
            $this->_settingsManager = $manager;
        }

        return $this->_settingsManager;
    }

    /**
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $manager = new SettingsManager();
        $manager->load('Shop.settings');

        $form = new SettingsForm();
        $form->setSettingsManager($manager);
        $this->set('settingsForm', $form);
    }
}
