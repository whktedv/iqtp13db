<?php
return [
    'ctrl' => [
        'title' => 'Branche',
        'label' => 'titel',
        'hideTable' => true,
        'readOnly' => true,
        'iconfile' => 'EXT:iqtp13db/Resources/Public/Icons/tx_iqtp13db_domain_model_branche.gif'
    ],
    'types' => [
        '1' => ['showitem' => 'brancheid, brancheok, titel, langisocode'],
    ],
    'columns' => [
        'brancheid' => [
            'exclude' => true,
            'label' => 'Branche ID',
            'config' => [
                'type' => 'input',
                'size' => 5,
                'eval' => 'trim'
            ],
        ],
        'brancheok' => [
            'exclude' => true,
            'label' => 'Branche Oberkategorie',
            'config' => [
                'type' => 'input',
                'size' => 5,
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
