<?php
declare(strict_types=1);

namespace Shop\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ShopCouponsFixture
 */
class ShopCouponsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'code' => 'Lorem ipsum dolor sit amet',
                'type' => 'Lorem ipsum dolor sit amet',
                'value' => 1.5,
                'valuetype' => 'Lorem ipsum dolor sit amet',
                'max_use' => 1,
                'max_use_per_customer' => 1,
                'is_published' => 1,
                'valid_from' => '2023-03-20 00:52:36',
                'valid_until' => '2023-03-20 00:52:36',
                'created' => '2023-03-20 00:52:36',
                'modified' => '2023-03-20 00:52:36',
            ],
        ];
        parent::init();
    }
}
