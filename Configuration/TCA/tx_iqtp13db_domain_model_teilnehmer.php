<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer',
        'label' => 'nachname',
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
        'searchFields' => 'niqchiffre,nachname,vorname,plz,ort,email,telefon,lebensalter,geburtsland,erste_staatsangehoerigkeit,zweite_staatsangehoerigkeit,einreisejahr,wohnsitz_nein_in, zertifikat_sprachniveau,erwerbsland1,dauer_berufsausbildung1,abschlussjahr1,ausbildungsinstitution1,ausbildungsort1,abschluss1,berufserfahrung1,ausbildungsfremdeberufserfahrung1,deutscher_referenzberuf1,wunschberuf1,erwerbsland2,dauer_berufsausbildung2,abschlussjahr2,ausbildungsinstitution2,ausbildungsort2,abschluss2,berufserfahrung2,ausbildungsfremdeberufserfahrung2,deutscher_referenzberuf2,wunschberuf2,leistungsbezug,name_berater_a_a,kontakt_berater_a_a,kundennummer_a_a,einw_anerkstelle, einw_anerkstelledatum, einw_anerkstellemedium, einw_anerkstellename, einw_anerkstellekontakt, einw_person, einw_persondatum, einw_personmedium, einw_personname, einw_personkontakt, frueherer_antrag_referenzberuf,frueherer_antrag_institution,name_beratungsstelle,verification_code,verification_ip',
        'iconfile' => 'EXT:iqtp13db/Resources/Public/Icons/tx_iqtp13db_domain_model_teilnehmer.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, beratungsstatus, niqchiffre, schonberaten, schonberatenvon, nachname, vorname, plz, ort, email, confirmemail, telefon, lebensalter, geburtsland, geschlecht, erste_staatsangehoerigkeit, zweite_staatsangehoerigkeit, einreisejahr, wohnsitz_deutschland, wohnsitz_nein_in, ortskraftafghanistan, deutschkenntnisse, zertifikatdeutsch, zertifikat_sprachniveau, abschlussart1, abschlussart2, erwerbsland1, dauer_berufsausbildung1, abschlussjahr1, ausbildungsinstitution1, ausbildungsort1, abschluss1, berufserfahrung1, ausbildungsfremdeberufserfahrung1, deutscher_referenzberuf1, wunschberuf1, erwerbsland2, dauer_berufsausbildung2, abschlussjahr2, ausbildungsinstitution2, ausbildungsort2, abschluss2, berufserfahrung2, ausbildungsfremdeberufserfahrung2, deutscher_referenzberuf2, wunschberuf2, erwerbsstatus, leistungsbezugjanein, leistungsbezug, einwilligungdatenan_a_a, einwilligungdatenan_a_adatum, einwilligungdatenan_a_amedium, name_berater_a_a, kontakt_berater_a_a, kundennummer_a_a, einw_anerkstelle, einw_anerkstelledatum, einw_anerkstellemedium, einw_anerkstellename, einw_anerkstellekontakt, einw_person, einw_persondatum, einw_personmedium, einw_personname, einw_personkontakt, aufenthaltsstatus, aufenthaltsstatusfreitext, frueherer_antrag, frueherer_antrag_referenzberuf, frueherer_antrag_institution, bescheidfrueherer_anerkennungsantrag, name_beratungsstelle, notizen, einwilligung, verification_code, verification_date, verification_ip',
    ],
    'types' => [
        '1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, beratungsstatus, niqchiffre, schonberaten, schonberatenvon, nachname, vorname, plz, ort, email, confirmemail, telefon, lebensalter, geburtsland, geschlecht, erste_staatsangehoerigkeit, zweite_staatsangehoerigkeit, einreisejahr, wohnsitz_deutschland, wohnsitz_nein_in, ortskraftafghanistan, deutschkenntnisse, zertifikatdeutsch, zertifikat_sprachniveau, abschlussart1, abschlussart2, erwerbsland1, dauer_berufsausbildung1, abschlussjahr1, ausbildungsinstitution1, ausbildungsort1, abschluss1, berufserfahrung1, ausbildungsfremdeberufserfahrung1, deutscher_referenzberuf1, wunschberuf1, erwerbsland2, dauer_berufsausbildung2, abschlussjahr2, ausbildungsinstitution2, ausbildungsort2, abschluss2, berufserfahrung2, ausbildungsfremdeberufserfahrung2, deutscher_referenzberuf2, wunschberuf2, erwerbsstatus, leistungsbezugjanein, leistungsbezug, einwilligungdatenan_a_a, einwilligungdatenan_a_adatum, einwilligungdatenan_a_amedium, name_berater_a_a, kontakt_berater_a_a, kundennummer_a_a, einw_anerkstelle, einw_anerkstelledatum, einw_anerkstellemedium, einw_anerkstellename, einw_anerkstellekontakt, einw_person, einw_persondatum, einw_personmedium, einw_personname, einw_personkontakt, aufenthaltsstatus, aufenthaltsstatusfreitext, frueherer_antrag, frueherer_antrag_referenzberuf, frueherer_antrag_institution, bescheidfrueherer_anerkennungsantrag, name_beratungsstelle, notizen, einwilligung, verification_code, verification_date, verification_ip, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
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
                'foreign_table' => 'tx_iqtp13db_domain_model_teilnehmer',
                'foreign_table_where' => 'AND {#tx_iqtp13db_domain_model_teilnehmer}.{#pid}=###CURRENT_PID### AND {#tx_iqtp13db_domain_model_teilnehmer}.{#sys_language_uid} IN (-1,0)',
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

        'beratungsstatus' => [
            'exclude' => false,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.beratungsstatus',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'niqchiffre' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.niqchiffre',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'schonberaten' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.schonberaten',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'schonberatenvon' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.schonberatenvon',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ]
        ],
        'nachname' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.nachname',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'vorname' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.vorname',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'plz' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.plz',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'ort' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.ort',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'email' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.email',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'nospace,email'
            ]
        ],
        'confirmemail' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.confirmemail',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'nospace,email'
            ]
        ],
        'telefon' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.telefon',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'lebensalter' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.lebensalter',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'geburtsland' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.geburtsland',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'geschlecht' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.geschlecht',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'erste_staatsangehoerigkeit' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.erste_staatsangehoerigkeit',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'zweite_staatsangehoerigkeit' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.zweite_staatsangehoerigkeit',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'einreisejahr' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.einreisejahr',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'wohnsitz_deutschland' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.wohnsitz_deutschland',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'wohnsitz_nein_in' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.wohnsitz_nein_in',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'ortskraftafghanistan' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.ortskraftafghanistan',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'deutschkenntnisse' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.deutschkenntnisse',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'zertifikatdeutsch' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.zertifikatdeutsch',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'zertifikat_sprachniveau' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.zertifikat_sprachniveau',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'abschlussart1' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.abschlussart1',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'abschlussart2' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.abschlussart2',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'erwerbsland1' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.erwerbsland1',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'dauer_berufsausbildung1' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.dauer_berufsausbildung1',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'abschlussjahr1' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.abschlussjahr1',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'ausbildungsinstitution1' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.ausbildungsinstitution1',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'ausbildungsort1' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.ausbildungsort1',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'abschluss1' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.abschluss1',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'berufserfahrung1' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.berufserfahrung1',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ]
        ],
        'ausbildungsfremdeberufserfahrung1' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.ausbildungsfremdeberufserfahrung1',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ]
        ],
        'deutscher_referenzberuf1' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.deutscher_referenzberuf1',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'wunschberuf1' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.wunschberuf1',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'erwerbsland2' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.erwerbsland2',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'dauer_berufsausbildung2' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.dauer_berufsausbildung2',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'abschlussjahr2' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.abschlussjahr2',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'ausbildungsinstitution2' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.ausbildungsinstitution2',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'ausbildungsort2' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.ausbildungsort2',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'abschluss2' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.abschluss2',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'berufserfahrung2' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.berufserfahrung2',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ]
        ],
        'ausbildungsfremdeberufserfahrung2' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.ausbildungsfremdeberufserfahrung2',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ]
        ],
        'deutscher_referenzberuf2' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.deutscher_referenzberuf2',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'wunschberuf2' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.wunschberuf2',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'erwerbsstatus' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.erwerbsstatus',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'leistungsbezugjanein' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.leistungsbezugjanein',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'leistungsbezug' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.leistungsbezug',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'einwilligungdatenan_a_a' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.einwilligungdatenan_a_a',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'einwilligungdatenan_a_adatum' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.einwilligungdatenan_a_adatum',
            'config' => [
                 'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'einwilligungdatenan_a_amedium' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.einwilligungdatenan_a_amedium',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'name_berater_a_a' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.name_berater_a_a',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'kontakt_berater_a_a' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.kontakt_berater_a_a',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'kundennummer_a_a' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.kundennummer_a_a',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'einw_anerkstelle' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.einw_anerkstelle',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'einw_anerkstelledatum' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.einw_anerkstelledatum',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'einw_anerkstellemedium' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.einw_anerkstellemedium',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'einw_anerkstellename' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.einw_anerkstellename',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'einw_anerkstellekontakt' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.einw_anerkstellekontakt',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'einw_person' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.einw_person',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'einw_persondatum' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.einw_persondatum',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'einw_personmedium' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.einw_personmedium',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'einw_personname' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.einw_personname',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'einw_personkontakt' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.einw_personkontakt',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'aufenthaltsstatus' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.aufenthaltsstatus',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ] 
				],
		'aufenthaltsstatusfreitext' => [ 
				'exclude' => true,
				'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.aufenthaltsstatusfreitext',
				'config' => [ 
						'type' => 'text',
						'cols' => 40,
						'rows' => 15,
						'eval' => 'trim' 
				] 
		],
        'frueherer_antrag' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.frueherer_antrag',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'frueherer_antrag_referenzberuf' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.frueherer_antrag_referenzberuf',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'frueherer_antrag_institution' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.frueherer_antrag_institution',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'bescheidfrueherer_anerkennungsantrag' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.bescheidfrueherer_anerkennungsantrag',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'name_beratungsstelle' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.name_beratungsstelle',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'notizen' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.notizen',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ],
        ],
        'einwilligung' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.einwilligung',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'verification_code' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.verification_code',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'verification_date' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.verification_date',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
        ],
        'verification_ip' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.verification_ip',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
    
    ],
];
