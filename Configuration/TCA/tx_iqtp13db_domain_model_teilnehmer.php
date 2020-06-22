<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer',
        'label' => 'nachname',
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
        'searchFields' => 'nachname,vorname,strasse,plz,ort,email,telefon,geburtsjahr,geburtsland,geschlecht,erste_staatsangehoerigkeit,zweite_staatsangehoerigkeit,einreisejahr,wohnsitz_deutschland,wohnsitz_ja_bundesland,wohnsitz_nein_in,deutschkenntnisse,zertifikatdeutsch,zertifikat_sprachniveau,beratungsgespraech_deutsch,beratungsgespraech_sprache,abschlussart_a,abschlussart_h,erwerbsland1,dauer_berufsausbildung1,abschlussjahr1,ausbildungsinstitution1,ausbildungsort1,abschluss1,deutsch_abschlusstitel1,berufserfahrung1,deutscher_referenzberuf1,wunschberuf1,erwerbsstatus,leistungsbezug,aufenthaltsstatus,frueherer_antrag,frueherer_antrag_referenzberuf,frueherer_antrag_institution,anz_beratungen,weitere_sprachkenntnisse,sprachen,einwilligung,erwerbsland2,dauer_berufsausbildung2,abschlussjahr2,ausbildungsinstitution2,ausbildungsort2,abschluss2,deutsch_abschlusstitel2,berufserfahrung2,deutscher_referenzberuf2,wunschberuf2,original_dokumente_abschluss1,original_dokumente_abschluss2,bescheidfrueherer_anerkennungsantrag',
        'iconfile' => 'EXT:iqtp13db/Resources/Public/Icons/tx_iqtp13db_domain_model_teilnehmer.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden, nachname, vorname, strasse, plz, ort, email, telefon, geburtsjahr, geburtsland, geschlecht, erste_staatsangehoerigkeit, zweite_staatsangehoerigkeit, einreisejahr, wohnsitz_deutschland, wohnsitz_ja_bundesland, wohnsitz_nein_in, deutschkenntnisse, zertifikatdeutsch, zertifikat_sprachniveau, beratungsgespraech_deutsch, beratungsgespraech_sprache, abschlussart_a, abschlussart_h, erwerbsland1, dauer_berufsausbildung1, abschlussjahr1, ausbildungsinstitution1, ausbildungsort1, abschluss1, deutsch_abschlusstitel1, berufserfahrung1, deutscher_referenzberuf1, wunschberuf1, erwerbsstatus, leistungsbezug, aufenthaltsstatus, frueherer_antrag, frueherer_antrag_referenzberuf, frueherer_antrag_institution, anz_beratungen, weitere_sprachkenntnisse, sprachen, einwilligung, erwerbsland2, dauer_berufsausbildung2, abschlussjahr2, ausbildungsinstitution2, ausbildungsort2, abschluss2, deutsch_abschlusstitel2, berufserfahrung2, deutscher_referenzberuf2, wunschberuf2, original_dokumente_abschluss1, original_dokumente_abschluss2, bescheidfrueherer_anerkennungsantrag, verification_code, verification_date, verification_ip',
    ],
    'types' => [
        '1' => ['showitem' => 'hidden, nachname, vorname, strasse, plz, ort, email, telefon, geburtsjahr, geburtsland, geschlecht, erste_staatsangehoerigkeit, zweite_staatsangehoerigkeit, einreisejahr, wohnsitz_deutschland, wohnsitz_ja_bundesland, wohnsitz_nein_in, deutschkenntnisse, zertifikatdeutsch, zertifikat_sprachniveau, beratungsgespraech_deutsch, beratungsgespraech_sprache, abschlussart_a, abschlussart_h, erwerbsland1, dauer_berufsausbildung1, abschlussjahr1, ausbildungsinstitution1, ausbildungsort1, abschluss1, deutsch_abschlusstitel1, berufserfahrung1, deutscher_referenzberuf1, wunschberuf1, erwerbsstatus, leistungsbezug, aufenthaltsstatus, frueherer_antrag, frueherer_antrag_referenzberuf, frueherer_antrag_institution, anz_beratungen, weitere_sprachkenntnisse, sprachen, einwilligung, erwerbsland2, dauer_berufsausbildung2, abschlussjahr2, ausbildungsinstitution2, ausbildungsort2, abschluss2, deutsch_abschlusstitel2, berufserfahrung2, deutscher_referenzberuf2, wunschberuf2, original_dokumente_abschluss1, original_dokumente_abschluss2, bescheidfrueherer_anerkennungsantrag, verification_code, verification_date, verification_ip, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
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
        'nachname' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.nachname',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim,required'
			],
	    ],
	    'vorname' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.vorname',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim,required'
			],
	    ],
	    'strasse' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.strasse',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'plz' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.plz',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'ort' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.ort',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'email' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.email',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim,required'
			],
	    ],
	    'telefon' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.telefon',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim,required'
			],
	    ],
	    'geburtsjahr' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.geburtsjahr',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'geburtsland' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.geburtsland',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim,required'
			],
	    ],
	    'geschlecht' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.geschlecht',
	        'config' => [
	            'type' => 'input',
	            'size' => 30,
	            'eval' => 'trim,required'
			],
	    ],
	    'erste_staatsangehoerigkeit' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.erste_staatsangehoerigkeit',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'zweite_staatsangehoerigkeit' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.zweite_staatsangehoerigkeit',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'einreisejahr' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.einreisejahr',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'wohnsitz_deutschland' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.wohnsitz_deutschland',
	        'config' => [
			    'type' => 'input',
			    'size' => 4,
			    'eval' => 'int,required'
			]
	    ],
	    'wohnsitz_ja_bundesland' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.wohnsitz_ja_bundesland',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim,required'
			],
	    ],
	    'wohnsitz_nein_in' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.wohnsitz_nein_in',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
        'geplante_einreise' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.geplante_einreise',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'kontakt_visastelle' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.kontakt_visastelle',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'visumsantrag' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.visumsantrag',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
	    'deutschkenntnisse' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.deutschkenntnisse',
	        'config' => [
	            'type' => 'input',
	            'size' => 30,
	            'eval' => 'trim'
			],
	    ],
	    'zertifikatdeutsch' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.zertifikatdeutsch',
	        'config' => [
			    'type' => 'input',
			    'size' => 4,
			    'eval' => 'int'
			]
	    ],
	    'zertifikat_sprachniveau' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.zertifikat_sprachniveau',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'beratungsgespraech_deutsch' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.beratungsgespraech_deutsch',
	        'config' => [
			    'type' => 'input',
			    'size' => 4,
			    'eval' => 'int'
			]
	    ],
	    'beratungsgespraech_sprache' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.beratungsgespraech_sprache',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'abschlussart_a' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.abschlussart_a',
	        'config' => [
			    'type' => 'check',
			    'items' => [
			        '1' => [
			            '0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
			        ]
			    ],
			    'default' => 0
			]
	    ],
	    'abschlussart_h' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.abschlussart_h',
	        'config' => [
			    'type' => 'check',
			    'items' => [
			        '1' => [
			            '0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
			        ]
			    ],
			    'default' => 0
			]
	    ],
	    'erwerbsland1' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.erwerbsland1',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim,required'
			],
	    ],
	    'dauer_berufsausbildung1' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.dauer_berufsausbildung1',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'abschlussjahr1' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.abschlussjahr1',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim,required'
			],
	    ],
	    'ausbildungsinstitution1' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.ausbildungsinstitution1',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'ausbildungsort1' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.ausbildungsort1',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'abschluss1' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.abschluss1',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim,required'
			],
	    ],
	    'deutsch_abschlusstitel1' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.deutsch_abschlusstitel1',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim,required'
			],
	    ],
	    'berufserfahrung1' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.berufserfahrung1',
	        'config' => [
			    'type' => 'text',
			    'cols' => 40,
			    'rows' => 15,
			    'eval' => 'trim'
			]
	    ],
	    'deutscher_referenzberuf1' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.deutscher_referenzberuf1',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'wunschberuf1' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.wunschberuf1',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'erwerbsstatus' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.erwerbsstatus',
	        'config' => [
	            'type' => 'input',
	            'size' => 30,
	            'eval' => 'trim'
			],
	    ],
	    'leistungsbezug' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.leistungsbezug',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'aufenthaltsstatus' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.aufenthaltsstatus',
	        'config' => [
	            'type' => 'input',
	            'size' => 30,
	            'eval' => 'trim'
			],
	    ],
	    'frueherer_antrag' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.frueherer_antrag',
	        'config' => [
			    'type' => 'input',
			    'size' => 4,
			    'eval' => 'int'
			]
	    ],
	    'frueherer_antrag_referenzberuf' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.frueherer_antrag_referenzberuf',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'frueherer_antrag_institution' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.frueherer_antrag_institution',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'anz_beratungen' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.anz_beratungen',
	        'config' => [
			    'type' => 'input',
			    'size' => 4,
			    'eval' => 'int'
			]
	    ],
	    'weitere_sprachkenntnisse' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.weitere_sprachkenntnisse',
	        'config' => [
			    'type' => 'input',
			    'size' => 4,
			    'eval' => 'int'
			]
	    ],
	    'sprachen' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.sprachen',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'einwilligung' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.einwilligung',
	        'config' => [
			    'type' => 'check',
			    'items' => [
			        '1' => [
			            '0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
			        ]
			    ],
			    'default' => 0
			]
	    ],
	    'erwerbsland2' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.erwerbsland2',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'dauer_berufsausbildung2' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.dauer_berufsausbildung2',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'abschlussjahr2' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.abschlussjahr2',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'ausbildungsinstitution2' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.ausbildungsinstitution2',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'ausbildungsort2' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.ausbildungsort2',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'abschluss2' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.abschluss2',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'deutsch_abschlusstitel2' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.deutsch_abschlusstitel2',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'berufserfahrung2' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.berufserfahrung2',
	        'config' => [
			    'type' => 'text',
			    'cols' => 40,
			    'rows' => 15,
			    'eval' => 'trim'
			]
	    ],
	    'deutscher_referenzberuf2' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.deutscher_referenzberuf2',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'wunschberuf2' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.wunschberuf2',
	        'config' => [
			    'type' => 'input',
			    'size' => 30,
			    'eval' => 'trim'
			],
	    ],
	    'original_dokumente_abschluss1' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.original_dokumente_abschluss1',
	        'config' => [
	            'type' => 'input',
	            'size' => 30,
	            'eval' => 'trim'
			],
	    ],
	    'original_dokumente_abschluss2' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.original_dokumente_abschluss2',
	        'config' => [
	            'type' => 'input',
	            'size' => 30,
	            'eval' => 'trim'
			],
	    ],
	    'bescheidfrueherer_anerkennungsantrag' => [
	        'exclude' => true,
	        'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.bescheidfrueherer_anerkennungsantrag',
	        'config' => [
			    'type' => 'check',
			    'items' => [
			        '1' => [
			            '0' => 'LLL:EXT:lang/locallang_core.xlf:labels.enabled'
			        ]
			    ],
			    'default' => 0
			]
	    ],
        'verification_code' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.verification_code',
            'config' => [
                'type' => 'input',
                'size' => '64',
                'eval' => 'trim'
            ]
        ],
        'verification_date' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.verification_date',
            'config' => [
                'type' => 'input',
                'size' => 12,
                'eval' => 'datetime',
                'checkbox' => 0,
                'eval' => 'trim'
            ]
        ],
        'verification_ip' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang_db.xlf:tx_iqtp13db_domain_model_teilnehmer.verification_ip',
            'config' => [
                'type' => 'input',
                'size' => '30',
                'eval' => 'trim'
            ]
        ],
    ],
];
