<?php
defined('TYPO3_MODE') or die();

$fields = array(
        'niqbid' => array(
 			'exclude' => 1,
 			'label' => 'NIQ Beratungsstellen-ID',
 				'config' => array(
 				    'type' => 'input',
 				    'size' => 10,
 				    'eval' => 'trim'
 				),
 		),
        'generalmail' => array(
            'exclude' => 1,
            'label' => 'Allgemeine Mailadresse (u.a. f. Anmeldekopien)',
            'config' => array(
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ),
        ),
        'plzlist' => array(
            'exclude' => 1,
            'label' => 'Liste PLZ für Zuweisung Beratungsstelle bei Anmeldung (Komma-separiert!)',
            'config' => array(
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ),
        ),
        'keywordlist' => array(
            'exclude' => 1,
            'label' => 'Liste Keywords für Zuweisung Beratungsstelle bei Anmeldung (Komma-separiert!)',
            'config' => array(
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim'
            ),
        ),
);


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('fe_groups', $fields);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'fe_groups',
    'niqbid, generalmail, plzlist, keywordlist',
    '',
    ''
    );

