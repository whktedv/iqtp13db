<?php
return [
    'ctrl' => [
        'title' => 'Ort',
        'label' => 'titel',
        'hideTable' => true,
        'readOnly' => true,
        'searchFields' => 'plz, bundesland, landkreis',
        'iconfile' => 'EXT:iqtp13db/Resources/Public/Icons/tx_iqtp13db_domain_model_ort.gif'
    ],
    'types' => [
        '1' => ['showitem' => 'plz, bundesland, landkreis'],
    ],
    'columns' => [
        'plz' => [
            'exclude' => true,
            'label' => 'PLZ',
            'config' => [
                'type' => 'input',
                'size' => 5,
                'eval' => 'trim'
            ],
        ],
        'ort' => [
            'exclude' => true,
            'label' => 'Ort',
            'config' => [
                'type' => 'input',
                'size' => 80,
                'eval' => 'trim'
            ],
        ],
        'bundesland' => [
            'exclude' => true,
            'label' => 'Bundesland',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'eval' => 'trim'
            ],
        ],
        'landkreis' => [
            'exclude' => true,
            'label' => 'Landkreis',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'eval' => 'trim'
            ],
        ],
        'lat' => [
            'exclude' => true,
            'label' => 'Latitude',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim'
            ],
        ],
        'lon' => [
            'exclude' => true,
            'label' => 'Longitude',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'eval' => 'trim'
            ],
        ],
    ],
];
