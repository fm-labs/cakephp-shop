<?php

namespace Shop\Core\Order;

use Cake\Datasource\EntityInterface;

interface OrderInterface extends EntityInterface
{
    /**
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * @return string|null
     */
    public function getUuid(): ?string;

    /**
     * @return string|null
     */
    public function getOrderNrFormatted(): ?string;

    /**
     * @return string|null
     */
    public function getInvoiceNrFormatted(): ?string;

    /**
     * @return string|null
     */

    //public function isTemp(): ?bool;
    //public function isDeleted(): ?bool;
    //public function isStorno(): ?bool;
}
