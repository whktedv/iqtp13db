<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_beratung',
        'label' => 'chiffre',
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
        'searchFields' => 'chiffre,prozess,datum,folgekontakt,ort,beratungsart,anfrage_durch,anmerkung,ergebnis_weiterleitung,anmerkung_verfahren,angaben_vereinbarungen,umfang,beratung_abgeschlossen,uebertrag_n_i_q,dokumente_ratsuchender,dokumente_anhaengen,folgekontakte,bescheid_gleichwertigkeitspruefung,ergebnis_gleichwertigkeitsfeststellung,zab_bewertung,verweis_an_bildungsdienstleister,empfohlene_qualimassnahme,welcher_bildungsdienstleister,modul_zuordnung_qualimassnahme,bundesland_qualimassnahme,anz_dokumente,weg_beratungsstelle,name_beratungsstelle,teilnehmer,berater',
        'iconfile' => 'EXT:iqtp13db/Resources/Public/Icons/tx_iqtp13db_domain_model_beratung.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden, chiffre, prozess, datum, folgekontakt, ort, beratungsart, anfrage_durch, anmerkung, ergebnis_weiterleitung, anmerkung_verfahren, angaben_vereinbarungen, umfang, beratung_abgeschlossen, uebertrag_n_i_q, dokumente_ratsuchender, dokumente_anhaengen, folgekontakte, bescheid_gleichwertigkeitspruefung, ergebnis_gleichwertigkeitsfeststellung, zab_bewertung, verweis_an_bildungsdienstleister, empfohlene_qualimassnahme, welcher_bildungsdienstleister, modul_zuordnung_qualimassnahme, bundesland_qualimassnahme, anz_dokumente, weg_beratungsstelle, name_beratungsstelle, teilnehmer, berater',
    ],
    'types' => [
        '1' => ['showitem' => 'hidden, chiffre, prozess, datum, folgekontakt, ort, beratungsart, anfrage_durch, anmerkung, ergebnis_weiterleitung, anmerkung_verfahren, angaben_vereinbarungen, umfang, beratung_abgeschlossen, uebertrag_n_i_q, dokumente_ratsuchender, dokumente_anhaengen, folgekontakte, bescheid_gleichwertigkeitspruefung, ergebnis_gleichwertigkeitsfeststellung, zab_bewertung, verweis_an_bildungsdienstleister, empfohlene_qualimassnahme, welcher_bildungsdienstleister, modul_zuordnung_qualimassnahme, bundesland_qualimassnahme, anz_dokumente, weg_beratungsstelle, name_beratungsstelle, teilnehmer, berater, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
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
        'chiffre' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_beratung.chiffre',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'prozess' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_beratung.prozess',
	        'config' => [
	            'type' => 'input',
	            'size' => 30,
	            'eval' => 'trim'
			],
	    ],
	    'datum' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_beratung.datum',
	        'config' => [
			    'dbType' => 'datetime',
			    'type' => 'input',
			    'size' => 12,
			    'eval' => 'datetime',
			    'default' => '0000-00-00 00:00:00',
	        	'renderType' => 'inputDateTime'
			],
	    ],
	    'folgekontakt' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_beratung.folgekontakt',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'ort' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_beratung.ort',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'beratungsart' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_beratung.beratungsart',
	        'config' => [
	            'type' => 'input',
	            'size' => 30,
	            'eval' => 'trim'
			],
	    ],
	    'anfrage_durch' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_beratung.anfrage_durch',
	        'config' => [
	            'type' => 'input',
	            'size' => 30,
	            'eval' => 'trim'
			],
	    ],
	    'anmerkung' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_beratung.anmerkung',
	        'config' => [
			    'type' => 'text',
			    'cols' => 40,
			    'rows' => 15,
			    'eval' => 'trim'
			]
	    ],
	    'ergebnis_weiterleitung' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_beratung.ergebnis_weiterleitung',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'anmerkung_verfahren' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_beratung.anmerkung_verfahren',
	        'config' => [
			    'type' => 'text',
			    'cols' => 40,
			    'rows' => 15,
			    'eval' => 'trim'
			]
	    ],
	    'angaben_vereinbarungen' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_beratung.angaben_vereinbarungen',
	        'config' => [
			    'type' => 'text',
			    'cols' => 40,
			    'rows' => 15,
			    'eval' => 'trim'
			]
	    ],
	    'umfang' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_beratung.umfang',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'beratung_abgeschlossen' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_beratung.beratung_abgeschlossen',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'uebertrag_n_i_q' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_beratung.uebertrag_n_i_q',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'dokumente_ratsuchender' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_beratung.dokumente_ratsuchender',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'dokumente_anhaengen' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_beratung.dokumente_anhaengen',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'folgekontakte' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_beratung.folgekontakte',
	        'config' => [
	            'type' => 'input',
	            'size' => 30,
	            'eval' => 'trim'
			],
	    ],
	    'bescheid_gleichwertigkeitspruefung' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_beratung.bescheid_gleichwertigkeitspruefung',
	        'config' => [
	            'type' => 'input',
	            'size' => 30,
	            'eval' => 'trim'
			],
	    ],
	    'ergebnis_gleichwertigkeitsfeststellung' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_beratung.ergebnis_gleichwertigkeitsfeststellung',
	        'config' => [
	            'type' => 'input',
	            'size' => 30,
	            'eval' => 'trim'
			],
	    ],
	    'zab_bewertung' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_beratung.zab_bewertung',
	        'config' => [
	            'type' => 'input',
	            'size' => 30,
	            'eval' => 'trim'
			],
	    ],
	    'verweis_an_bildungsdienstleister' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_beratung.verweis_an_bildungsdienstleister',
	        'config' => [
	            'type' => 'input',
	            'size' => 30,
	            'eval' => 'trim'
			],
	    ],
	    'empfohlene_qualimassnahme' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_beratung.empfohlene_qualimassnahme',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'welcher_bildungsdienstleister' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_beratung.welcher_bildungsdienstleister',
	        'config' => [
	            'type' => 'input',
	            'size' => 30,
	            'eval' => 'trim'
			],
	    ],
	    'modul_zuordnung_qualimassnahme' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_beratung.modul_zuordnung_qualimassnahme',
	        'config' => [
	            'type' => 'input',
	            'size' => 30,
	            'eval' => 'trim'
			],
	    ],
	    'bundesland_qualimassnahme' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_beratung.bundesland_qualimassnahme',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'anz_dokumente' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_beratung.anz_dokumente',
	        'config' => [
			    'type' => 'input',
			    'size' => 4,
			    'eval' => 'int'
			]
	    ],
	    'weg_beratungsstelle' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_beratung.weg_beratungsstelle',
	        'config' => [
	            'type' => 'input',
	            'size' => 30,
	            'eval' => 'trim'
			],
	    ],
	    'name_beratungsstelle' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_beratung.name_beratungsstelle',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'teilnehmer' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_beratung.teilnehmer',
	        'config' => [
			    'type' => 'select',
			    'renderType' => 'selectSingle',
			    'foreign_table' => 'tx_iqtp13db_domain_model_teilnehmer',
			    'minitems' => 0,
			    'maxitems' => 1,
			],
	    ],
	    'berater' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_beratung.berater',
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
