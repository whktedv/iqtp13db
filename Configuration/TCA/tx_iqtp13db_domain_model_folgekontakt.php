<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_folgekontakt',
        'label' => 'teilnehmer',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'notizen',
        'iconfile' => 'EXT:iqtp13db/Resources/Public/Icons/tx_iqtp13db_domain_model_folgekontakt.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, datum, antraggestellt, zab_gleichwertigkeit, notizen, teilnehmer, berater',
    ],
    'types' => [
        '1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, datum, antraggestellt, zab_gleichwertigkeit, notizen, teilnehmer, berater, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages',
                'items' => [
                    [
                        'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.allLanguages',
                        -1,
                        'flags-multiple'
                    ]
                ],
                'default' => 0,
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_iqtp13db_domain_model_folgekontakt',
                'foreign_table_where' => 'AND {#tx_iqtp13db_domain_model_folgekontakt}.{#pid}=###CURRENT_PID### AND {#tx_iqtp13db_domain_model_folgekontakt}.{#sys_language_uid} IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        't3ver_label' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ],
        ],
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

        'datum' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_folgekontakt.datum',
            'config' => [
               'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'antraggestellt' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_folgekontakt.antraggestellt',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'zab_gleichwertigkeit' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_folgekontakt.zab_gleichwertigkeit',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'notizen' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_folgekontakt.notizen',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ]
        ],
        'teilnehmer' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_folgekontakt.teilnehmer',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_iqtp13db_domain_model_teilnehmer',
                'default' => 0,
                'size' => 10,
                'autoSizeMax' => 30,
                'maxitems' => 1,
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
        'berater' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_folgekontakt.berater',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'tx_iqtp13db_domain_model_berater',
                'default' => 0,
                'minitems' => 0,
                'maxitems' => 1,
            ],

        ],
    
    ],
];
