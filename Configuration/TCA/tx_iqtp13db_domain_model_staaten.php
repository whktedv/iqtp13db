<?php
return [
    'ctrl' => [
        'title' => 'Staaten',
        'label' => 'titel',
        'hideTable' => true,
        'searchFields' => 'staatid, titel',
        'iconfile' => ''
    ],
    'types' => [
        '1' => ['showitem' => 'staatid, titel, langisocode'],
    ],
    'columns' => [
        'staatid' => [
            'exclude' => true,
            'label' => 'Staat ID',
            'config' => [
                'type' => 'input',
                'size' => 10,
                'eval' => 'trim'
            ],
        ],
        'titel' => [
            'exclude' => true,
            'label' => 'Titel',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'eval' => 'trim'
            ],
        ],
        'langisocode' => [
            'exclude' => true,
            'label' => 'ISO Code',
            'config' => [
                'type' => 'input',
                'size' => 2,
                'eval' => 'trim'
            ],
        ],
    ],
];
