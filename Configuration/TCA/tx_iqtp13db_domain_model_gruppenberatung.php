<?php

return [
    'ctrl' => [
        'title' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_gruppenberatung',
        'label' => 'titel',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'sortby' => 'sorting',
        'searchFields' => 'titel,beschreibung,ort',
        'iconfile' => 'EXT:iqtp13db/Resources/Public/Icons/tx_iqtp13db_domain_model_gruppenberatung.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden, titel, beschreibung, datum, ort, max_teilnehmer, teilnehmer',
    ],
    'types' => [
        '1' => ['showitem' => 'hidden, titel, beschreibung, datum, ort, max_teilnehmer, teilnehmer, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
    ],
    'columns' => [
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'invertStateDisplay' => true
                    ]
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
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
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038)
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'titel' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_gruppenberatung.titel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],
        ],
        'beschreibung' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_gruppenberatung.beschreibung',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ]
        ],
        'datum' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_gruppenberatung.datum',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime',
                'default' => 0,
            ],
        ],
        'ort' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_gruppenberatung.ort',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'max_teilnehmer' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_gruppenberatung.max_teilnehmer',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
                'default' => 0,
            ]
        ],
        'teilnehmer' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_gruppenberatung.teilnehmer',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_iqtp13db_domain_model_teilnehmer',
                'MM' => 'tx_iqtp13db_teilnehmer_gruppenberatung_mm',
                'size' => 10,
                'autoSizeMax' => 30,
                'maxitems' => 9999,
                'multiple' => 0,
                'fieldControl' => [
                    'editPopup' => [
                        'disabled' => false,
                    ],
                    'addRecord' => [
                        'disabled' => false,
                    ],
                    'listModule' => [
                        'disabled' => true,
                    ],
                ],
            ],
        ],
    ],
];