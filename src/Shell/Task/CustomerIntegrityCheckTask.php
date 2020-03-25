<?php
declare(strict_types=1);

namespace Shop\Shell\Task;

use Cake\Console\ConsoleOptionParser;

/**
 * Class CustomerIntegrityCheckTask
 *
 * @package Shop\Shell\Task
 * @property \Shop\Model\Table\ShopCustomersTable $ShopCustomers
 */
class CustomerIntegrityCheckTask extends BaseShopTask
{
    /**
     * @var array
     */
    protected $_checks = [];

    /**
     * @var array
     */
    protected $_counter = [];

    /**
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser(): ConsoleOptionParser
    {
        $parser = parent::getOptionParser();
        $parser
            ->setDescription(__d('shop', "Check shop customer data integrity"));

        return $parser;
    }

    /**
     * Main method
     *
     * @return void
     */
    public function main()
    {
        $this->_counter = ['warn' => 0, 'crit' => 0];
        $this->_checks = [];

        $this->loadModel('Shop.ShopCustomers');

        $customers = $this->ShopCustomers->find()->contain(['Users'])->all();
        foreach ($customers as $customer) {
            // check customer data
            foreach (['first_name', 'last_name', 'email', 'user_id', 'user'] as $prop) {
                $val = $customer->get($prop);
                if (!$val) {
                    $this->_mark($customer->id, sprintf('Customer property %s has no value', $prop), 'crit');
                }
            }

            foreach (['greeting', 'display_name'] as $prop) {
                $val = $customer->get($prop);
                if (!$val) {
                    $this->_mark($customer->id, sprintf('Customer property %s has no value', $prop), 'warn');
                }
            }

            // check user data
            foreach (['username', 'email', 'display_name'] as $prop) {
                if (!$customer->user) {
                    continue;
                }
                $val = $customer->user->get($prop);
                if (!$val) {
                    $this->_mark($customer->id, sprintf('User property %s has no value', $prop), 'crit');
                }
            }
        }

        $this->out(sprintf('<warning>%d warning messsages</warning>', $this->_counter['warn']));
        $this->out(sprintf('<error>%d critical messages</error>', $this->_counter['crit']));
    }

    /**
     * @param $id
     * @param $msg
     * @param string $level
     * @return void
     */
    protected function _mark($id, $msg, $level = 'warn')
    {
        if (!isset($this->_checks[$id])) {
            $this->_checks[$id] = ['warn' => [], 'crit' => []];
        }

        $this->_checks[$id][$level][] = $msg;
        $this->_counter[$level]++;

        switch ($level) {
            case 'warn':
                $tag = 'warning';
                break;
            case 'crit':
                $tag = 'error';
                break;
            default:
                $tag = 'info';
                break;
        }

        $this->out(sprintf("<%s>[%d][%s] %s</%s>", $tag, $id, $level, $msg, $tag));
    }
}
