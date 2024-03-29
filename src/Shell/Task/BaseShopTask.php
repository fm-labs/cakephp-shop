<?php
declare(strict_types=1);

namespace Shop\Shell\Task;

use Cake\Console\Shell;

/**
 * Class BaseShopTask
 *
 * @package Shop\Shell\Task
 */
class BaseShopTask extends Shell
{
    /**
     * {@inheritDoc}
     */
    public function initialize(): void
    {
        parent::initialize();
    }

    /**
     * {@inheritDoc}
     */
    public function startup(): void
    {
        parent::startup();
    }
}
