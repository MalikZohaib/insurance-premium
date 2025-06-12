<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

$pluginSignature = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'InsurnacePremium',
    'Insurancecalculator',
    'Insurance Calculator',
    'insurnace_premium-plugin-insurancecalculator'
);

ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform,',
    $pluginSignature,
    'after:subheader',
);

ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:insurnace_premium/Configuration/FlexForms/InsurancecalculatorPlugin.xml',
    $pluginSignature,
);