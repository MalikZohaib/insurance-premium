<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:insurnace_premium/Resources/Private/Language/locallang_db.xlf:tx_insurnacepremium_domain_model_insurancepolicies',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'security' => [
            'ignorePageTypeRestriction' => true,
        ],
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'title,body',
        'iconfile' => 'EXT:insurnace_premium/Resources/Public/Icons/tx_insurnacepremium_domain_model_insurancepolicies.gif'
    ],
    'types' => [
        '1' => ['showitem' => 'title, body, contributions, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language, sys_language_uid, l10n_parent, l10n_diffsource, --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access, hidden, starttime, endtime'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'language',
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_insurnacepremium_domain_model_insurancepolicies',
                'foreign_table_where' => 'AND {#tx_insurnacepremium_domain_model_insurancepolicies}.{#pid}=###CURRENT_PID### AND {#tx_insurnacepremium_domain_model_insurancepolicies}.{#sys_language_uid} IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
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
                'type' => 'datetime',
                'format' => 'date',
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
                'type' => 'datetime',
                'format' => 'date',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038)
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],

        'title' => [
            'exclude' => true,
            'label' => 'LLL:EXT:insurnace_premium/Resources/Private/Language/locallang_db.xlf:tx_insurnacepremium_domain_model_insurancepolicies.title',
            'description' => 'LLL:EXT:insurnace_premium/Resources/Private/Language/locallang_db.xlf:tx_insurnacepremium_domain_model_insurancepolicies.title.description',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
                'default' => ''
            ],
        ],
        'body' => [
            'exclude' => true,
            'label' => 'LLL:EXT:insurnace_premium/Resources/Private/Language/locallang_db.xlf:tx_insurnacepremium_domain_model_insurancepolicies.body',
            'description' => 'LLL:EXT:insurnace_premium/Resources/Private/Language/locallang_db.xlf:tx_insurnacepremium_domain_model_insurancepolicies.body.description',
            'config' => [
                'type' => 'json',
                'required' => true,
                'default' => '',
                'eval' => 'trim,Zohaibdev\\InsurnacePremium\\Evaluation\\AgeRangeJsonEval',
            ]
        ],
    ],
];
