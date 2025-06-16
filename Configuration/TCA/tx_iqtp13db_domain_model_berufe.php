<?php
return [
    'ctrl' => [
        'title' => 'Berufe',
        'label' => 'titel',
        'hideTable' => true,
        'searchFields' => 'berufid, titel',
        'iconfile' => 'EXT:iqtp13db/Resources/Public/Icons/tx_iqtp13db_domain_model_berufe.gif'
    ],
    'types' => [
        '1' => ['showitem' => 'berufid, titel, langisocode'],
    ],
    'columns' => [
        'berufid' => [
            'exclude' => true,
            'label' => 'Beruf ID',
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
