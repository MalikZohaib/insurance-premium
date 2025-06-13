<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die();

$pluginSignature = \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'InsurnacePremium',
    'Insurancecalculator',
    'LLL:EXT:insurnace_premium/Resources/Private/Language/locallang_db.xlf:tt_content.ctype.insurnacepremium_insurancecalculator.title',
    'insurnace_premium-plugin-insurancecalculator',
    'plugins',
    'LLL:EXT:insurnace_premium/Resources/Private/Language/locallang_db.xlf:tt_content.ctype.insurnacepremium_insurancecalculator.description',


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

// Register the preview renderer for the tt_content type
$GLOBALS['TCA']['tt_content']['types'][$pluginSignature]['previewRenderer']
    = Zohaibdev\InsurnacePremium\Backend\Preview\InsuranceCalculatorPreviewRenderer::class;