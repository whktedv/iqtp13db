<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_historie',
        'label' => 'property',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'versioningWS' => true,
        'delete' => 'deleted',
        'searchFields' => 'property',
        'iconfile' => 'EXT:iqtp13db/Resources/Public/Icons/tx_iqtp13db_domain_model_historie.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'teilnehmer, property, oldvalue, newvalue, berater, tstamp',
    ],
    'types' => [
        '1' => ['showitem' => 'teilnehmer, property, oldvalue, newvalue, berater, tstamp, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access'],
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
	    'teilnehmer' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_historie.teilnehmer',
	        'config' => [
			    'type' => 'select',
			    'renderType' => 'selectSingle',
			    'foreign_table' => 'tx_iqtp13db_domain_model_teilnehmer',
			    'minitems' => 0,
			    'maxitems' => 1,
			],
	    ],
		'property' => [ 
				'exclude' => true,
				'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_historie.property',
				'config' => [ 
						'type' => 'input',
						'size' => 30,
						'eval' => 'trim' 
				] 
		],
		'oldvalue' => [ 
				'exclude' => true,
				'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_historie.oldvalue',
				'config' => [ 
						'type' => 'input',
						'size' => 30,
						'eval' => 'trim' 
				] 
		],
		'newvalue' => [ 
				'exclude' => true,
				'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_historie.newvalue',
				'config' => [ 
						'type' => 'input',
						'size' => 30,
						'eval' => 'trim' 
				] 
		],
		'berater' => [ 
				'exclude' => true,
				'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_historie.berater',
				'config' => [ 
						'type' => 'select',
						'renderType' => 'selectSingle',
						'foreign_table' => 'tx_iqtp13db_domain_model_berater',
						'default' => 0,
						'minitems' => 0,
						'maxitems' => 1 
				] 
		],
		'tstamp' => [
			'exclude' => true,
			'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.tstamp',
			'config' => [
				'type' => 'input',
				'renderType' => 'inputDateTime',
				'eval' => 'datetime,int',
				'default' => 0,
				'behaviour' => [
						'allowLanguageSynchronization' => true
				]
			],
		],
    ],
];
