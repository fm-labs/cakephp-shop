<?php
return [
    'HtmlEditor' => [
        'shop' => [
            'convert_urls' => false,
            '@image_list' => ['plugin' => 'Content', 'controller' => 'HtmlEditor', 'action' => 'imageList', 'shop'],
            '@link_list' => ['plugin' => 'Content', 'controller' => 'HtmlEditor', 'action' => 'linkList'],
        ],
    ],
];
