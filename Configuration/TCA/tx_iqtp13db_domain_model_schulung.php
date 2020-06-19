<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_schulung',
        'label' => 'datum',
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
        'searchFields' => 'datum,institution,massnahmebereich,art,andere_art,modularer_aufbau,kooperation,kooperation_sonstige,zeit_umfang,organisation,teilnahmeart,teilnehmerkreis,themen,themen_anderes,institution_auswahl,institution_andere,betriebsgroesse,weitere_planung,weitere_planung_andere,anz_dokumente,berater',
        'iconfile' => 'EXT:iqtp13db/Resources/Public/Icons/tx_iqtp13db_domain_model_schulung.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden, datum, institution, massnahmebereich, art, andere_art, modularer_aufbau, kooperation, kooperation_sonstige, zeit_umfang, organisation, teilnahmeart, teilnehmerkreis, themen, themen_anderes, institution_auswahl, institution_andere, betriebsgroesse, weitere_planung, weitere_planung_andere, anz_dokumente, berater',
    ],
    'types' => [
        '1' => ['showitem' => 'hidden, datum, institution, massnahmebereich, art, andere_art, modularer_aufbau, kooperation, kooperation_sonstige, zeit_umfang, organisation, teilnahmeart, teilnehmerkreis, themen, themen_anderes, institution_auswahl, institution_andere, betriebsgroesse, weitere_planung, weitere_planung_andere, anz_dokumente, berater, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
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
        'datum' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_schulung.datum',
	        'config' => [
			    'dbType' => 'datetime',
			    'type' => 'input',
			    'size' => 12,
			    'eval' => 'datetime',
			    'default' => '0000-00-00 00:00:00',
	        	'renderType' => 'inputDateTime'
			],
	    ],
	    'institution' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_schulung.institution',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'massnahmebereich' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_schulung.massnahmebereich',
	        'config' => [
			    'type' => 'select',
			    'renderType' => 'selectSingle',
			    'items' => [
			        ['-- Label --', 0],
			    ],
			    'size' => 1,
			    'maxitems' => 1,
			    'eval' => ''
			],
	    ],
	    'art' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_schulung.art',
	        'config' => [
			    'type' => 'select',
			    'renderType' => 'selectSingle',
			    'items' => [
			        ['-- Label --', 0],
			    ],
			    'size' => 1,
			    'maxitems' => 1,
			    'eval' => ''
			],
	    ],
	    'andere_art' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_schulung.andere_art',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'modularer_aufbau' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_schulung.modularer_aufbau',
	        'config' => [
			    'type' => 'select',
			    'renderType' => 'selectSingle',
			    'items' => [
			        ['-- Label --', 0],
			    ],
			    'size' => 1,
			    'maxitems' => 1,
			    'eval' => ''
			],
	    ],
	    'kooperation' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_schulung.kooperation',
	        'config' => [
			    'type' => 'select',
			    'renderType' => 'selectSingle',
			    'items' => [
			        ['-- Label --', 0],
			    ],
			    'size' => 1,
			    'maxitems' => 1,
			    'eval' => ''
			],
	    ],
	    'kooperation_sonstige' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_schulung.kooperation_sonstige',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'zeit_umfang' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_schulung.zeit_umfang',
	        'config' => [
			    'type' => 'select',
			    'renderType' => 'selectSingle',
			    'items' => [
			        ['-- Label --', 0],
			    ],
			    'size' => 1,
			    'maxitems' => 1,
			    'eval' => ''
			],
	    ],
	    'organisation' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_schulung.organisation',
	        'config' => [
			    'type' => 'select',
			    'renderType' => 'selectSingle',
			    'items' => [
			        ['-- Label --', 0],
			    ],
			    'size' => 1,
			    'maxitems' => 1,
			    'eval' => ''
			],
	    ],
	    'teilnahmeart' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_schulung.teilnahmeart',
	        'config' => [
			    'type' => 'select',
			    'renderType' => 'selectSingle',
			    'items' => [
			        ['-- Label --', 0],
			    ],
			    'size' => 1,
			    'maxitems' => 1,
			    'eval' => ''
			],
	    ],
	    'teilnehmerkreis' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_schulung.teilnehmerkreis',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'themen' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_schulung.themen',
	        'config' => [
			    'type' => 'select',
			    'renderType' => 'selectSingle',
			    'items' => [
			        ['-- Label --', 0],
			    ],
			    'size' => 1,
			    'maxitems' => 1,
			    'eval' => ''
			],
	    ],
	    'themen_anderes' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_schulung.themen_anderes',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'institution_auswahl' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_schulung.institution_auswahl',
	        'config' => [
			    'type' => 'select',
			    'renderType' => 'selectSingle',
			    'items' => [
			        ['-- Label --', 0],
			    ],
			    'size' => 1,
			    'maxitems' => 1,
			    'eval' => ''
			],
	    ],
	    'institution_andere' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_schulung.institution_andere',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'betriebsgroesse' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_schulung.betriebsgroesse',
	        'config' => [
			    'type' => 'select',
			    'renderType' => 'selectSingle',
			    'items' => [
			        ['-- Label --', 0],
			    ],
			    'size' => 1,
			    'maxitems' => 1,
			    'eval' => ''
			],
	    ],
	    'weitere_planung' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_schulung.weitere_planung',
	        'config' => [
			    'type' => 'select',
			    'renderType' => 'selectSingle',
			    'items' => [
			        ['-- Label --', 0],
			    ],
			    'size' => 1,
			    'maxitems' => 1,
			    'eval' => ''
			],
	    ],
	    'weitere_planung_andere' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_schulung.weitere_planung_andere',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'anz_dokumente' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_schulung.anz_dokumente',
	        'config' => [
			    'type' => 'input',
			    'size' => 4,
			    'eval' => 'int'
			]
	    ],
	    'berater' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_schulung.berater',
	        'config' => [
			    'type' => 'select',
			    'renderType' => 'selectSingle',
			    'foreign_table' => 'tx_iqtp13db_domain_model_berater',
			    'minitems' => 0,
			    'maxitems' => 1,
			],
	    ],
    ],
];
