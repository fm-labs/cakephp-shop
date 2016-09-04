<?php

use Cake\Routing\Router;

$url = [
    'plugin' => 'Shop',
    'controller' => 'ShopTexts',
    'action' => 'edit_iframe',
    'model' => $model,
    'model_id' => $modelId,
    'model_scope' => $modelScope,
    'format' => $format,
    'locale' => $locale
];
?>
<!--
<iframe src="<?= Router::url($url); ?>" class="shop texts form iframe" width="100%"></iframe>
-->