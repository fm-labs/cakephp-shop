<?php
return [
    'Cron.tasks' => [
        'shop_cron_clear_cc_data' => [
            'className' => \Shop\Cron\Task\ClearCreditcardDataCronTask::class,
            'interval' => 'weekly',
            'enabled' => false,
        ],
    ]
];
