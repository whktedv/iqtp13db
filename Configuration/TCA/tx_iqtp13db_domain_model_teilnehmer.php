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
        'searchFields' => 'niqchiffre,nachname,vorname,plz,ort,email,telefon,lebensalter,geburtsland,erste_staatsangehoerigkeit,zweite_staatsangehoerigkeit,einreisejahr,wohnsitz_nein_in, zertifikat_sprachniveau,erwerbsland1,dauer_berufsausbildung1,abschlussjahr1,ausbildungsinstitution1,ausbildungsort1,abschluss1,berufserfahrung1,berufserfahrunginland1,ausbildungsfremdeberufserfahrung1,deutscher_referenzberuf1,wunschberuf1,erwerbsland2,dauer_berufsausbildung2,abschlussjahr2,ausbildungsinstitution2,ausbildungsort2,abschluss2,berufserfahrung2,berufserfahrunginland2,ausbildungsfremdeberufserfahrung2,deutscher_referenzberuf2,wunschberuf2,leistungsbezug,name_berater_a_a,kontakt_berater_a_a,kundennummer_a_a,einw_anerkstelle, einw_anerkstelledatum, einw_anerkstellemedium, einw_anerkstellename, einw_anerkstellekontakt, einw_person, einw_persondatum, einw_personmedium, einw_personname, einw_personkontakt, name_beratungsstelle,verification_code,verification_ip,anerkennungszuschussbeantragt,wieberaten,kooperationgruppe',
        'iconfile' => 'EXT:iqtp13db/Resources/Public/Icons/tx_iqtp13db_domain_model_teilnehmer.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, niqidberatungsstelle, beratungsstatus, niqchiffre, niqtstamp, schonberaten, schonberatenvon, nachname, vorname, plz, ort, email, confirmemail, telefon, lebensalter, geburtsland, geschlecht, erste_staatsangehoerigkeit, zweite_staatsangehoerigkeit, einreisejahr, wohnsitz_deutschland, wohnsitz_nein_in, sonstigerstatus, deutschkenntnisse, zertifikatdeutsch, zertifikat_sprachniveau, erwerbsstatus, leistungsbezugjanein, leistungsbezug, einwilligungdatenan_a_a, einwilligungdatenan_a_adatum, einwilligungdatenan_a_amedium, name_berater_a_a, kontakt_berater_a_a, kundennummer_a_a, einw_anerkstelle, einw_anerkstelledatum, einw_anerkstellemedium, einw_anerkstellename, einw_anerkstellekontakt, einw_person, einw_persondatum, einw_personmedium, einw_personname, einw_personkontakt, aufenthaltsstatus, aufenthaltsstatusfreitext, name_beratungsstelle, notizen, einwilligung, verification_code, verification_date, verification_ip,anerkennungszuschussbeantragt,wieberaten,kooperationgruppe, beratungdatum, beratungsart, beratungsartfreitext, beratungsort, beratungzu, anerkennendestellen, anerkennungsberatung, anerkennungsberatungfreitext, qualifizierungsberatung, qualifizierungsberatung, beratungnotizen, erstberatungabgeschlossen, berater',
    ],
    'types' => [
        '1' => ['showitem' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, niqidberatungsstelle, beratungsstatus, niqchiffre, niqtstamp, schonberaten, schonberatenvon, nachname, vorname, plz, ort, email, confirmemail, telefon, lebensalter, geburtsland, geschlecht, erste_staatsangehoerigkeit, zweite_staatsangehoerigkeit, einreisejahr, wohnsitz_deutschland, wohnsitz_nein_in, sonstigerstatus, deutschkenntnisse, zertifikatdeutsch, zertifikat_sprachniveau, erwerbsstatus, leistungsbezugjanein, leistungsbezug, einwilligungdatenan_a_a, einwilligungdatenan_a_adatum, einwilligungdatenan_a_amedium, name_berater_a_a, kontakt_berater_a_a, kundennummer_a_a, einw_anerkstelle, einw_anerkstelledatum, einw_anerkstellemedium, einw_anerkstellename, einw_anerkstellekontakt, einw_person, einw_persondatum, einw_personmedium, einw_personname, einw_personkontakt, aufenthaltsstatus, aufenthaltsstatusfreitext, name_beratungsstelle, notizen, einwilligung, verification_code, verification_date, verification_ip,anerkennungszuschussbeantragt,wieberaten,kooperationgruppe, beratungdatum, beratungsart, beratungsartfreitext, beratungsort, beratungzu, anerkennendestellen, anerkennungsberatung, anerkennungsberatungfreitext, qualifizierungsberatung, qualifizierungsberatung, beratungnotizen, erstberatungabgeschlossen, berater, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
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
        
        'niqidberatungsstelle' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.niqidberatungsstelle',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int',
                'default' => 0
            ],
        ],
        
        'tstamp' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.tstamp',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0               
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
        'niqtstamp' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.niqtstamp',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'int'
            ]
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
        'sonstigerstatus' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.sonstigerstatus',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
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
        'anerkennungszuschussbeantragt' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.anerkennungszuschussbeantragt',
            'config' => [
                'type' => 'input',
                'size' => 4,
                'eval' => 'trim'
            ]
        ],
        'wieberaten' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.wieberaten',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'kooperationgruppe' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.kooperationgruppe',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'beratungdatum' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.beratungdatum',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'beratungsart' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.beratungsart',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ]
        ],
        'beratungsartfreitext' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.beratungsartfreitext',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ]
        ],
        'beratungsort' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.beratungsort',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ]
        ],
        'beratungzu' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.beratungzu',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],     
        'anerkennendestellen' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.anerkennendestellen',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ]
        ],
        'anerkennungsberatung' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.anerkennungsberatung',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'anerkennungsberatungfreitext' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.anerkennungsberatungfreitext',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ]
        ],
        'qualifizierungsberatung' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.qualifizierungsberatung',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'qualifizierungsberatungfreitext' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.qualifizierungsberatungfreitext',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ]
        ],
        'beratungnotizen' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.beratungnotizen',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ]
        ],
        'erstberatungabgeschlossen' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.erstberatungabgeschlossen',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ]
        ],       
        'berater' => [
            'exclude' => true,
            'label' => 'LLL:EXT:iqtp13db/Resources/Private/Language/locallang.xlf:tx_iqtp13db_domain_model_teilnehmer.berater',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'fe_users',
                'minitems' => 0,
                'maxitems' => 1,
            ],
            
        ],
    
    ],
];
