<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

$pluginSignature = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'InsurnacePremium',
    'Insurancecalculator',
    'Insurance Calculator'
);

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';

ExtensionManagementUtility::addPiFlexFormValue(
    $pluginSignature,
    'FILE:EXT:insurnace_premium/Configuration/FlexForms/InsurancecalculatorPlugin.xml'
);