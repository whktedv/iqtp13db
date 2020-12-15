<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_berater',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'versioningWS' => true,
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'name,organisation',
        'iconfile' => 'EXT:iqtp13db/Resources/Public/Icons/tx_iqtp13db_domain_model_berater.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden, name, organisation, kuerzel',
    ],
    'types' => [
        '1' => ['showitem' => 'hidden, name, organisation, kuerzel, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
    ],
    'columns' => [
        't3ver_label' => [
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ],
        ],
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'items' => [
                    '1' => [
                        '0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
                    ]
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'eval' => 'datetime',
                'default' => 0,
            	'renderType' => 'inputDateTime',
            	'behaviour' => [
            			'allowLanguageSynchronization' => true,
            	]
            ]
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'size' => 13,
                'eval' => 'datetime',
                'default' => 0,
            	'renderType' => 'inputDateTime',
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038)
                ],
            	'behaviour' => [
            			'allowLanguageSynchronization' => true,
            	]
            ],
        ],
        'name' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_berater.name',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'organisation' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_berater.organisation',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'kuerzel' => [
	    'exclude' => true,
	    'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_berater.kuerzel',
	    'config' => [
	    'type' => 'input',
	    'size' => 10,
	    'eval' => 'trim'
	    		],
	    		],
    ],
];
