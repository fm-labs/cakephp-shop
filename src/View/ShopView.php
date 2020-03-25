<?php
declare(strict_types=1);

namespace Shop\View;

use Content\View\ContentView;

class ShopView extends ContentView
{
    public function initialize()
    {
        parent::initialize();

        $this->loadHelper('Bootstrap.Ui');
        $this->loadHelper('Media.Media');
        $this->loadHelper('Paginator', [
            'templates' => 'Shop.paginator_templates',
        ]);
    }
}
