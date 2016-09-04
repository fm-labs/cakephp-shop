<?php
try {
    echo $this->requestAction(['plugin' => 'Shop', 'controller' => 'ShopCategories', 'action' => 'productslisting', 'category_id' => $categoryId]);
} catch (\Exception $ex) {
    debug($ex->getMessage());
}