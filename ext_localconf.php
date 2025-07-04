<?php

declare(strict_types=1);

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use Zohaibdev\InsurnacePremium\Controller\InsuranceController;
use Zohaibdev\InsurnacePremium\Hooks\PolicyDataHandlerHook;
defined('TYPO3') || die();

(static function () {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'InsurnacePremium',
        'Insurancecalculator',
        [
            InsuranceController::class => 'showCalculator, ajax'
        ],
        // non-cacheable actions
        [
            InsuranceController::class => 'ajax'
        ],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
    );

    // wizards
    // \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
    //     'mod {
    //         wizards.newContentElement.wizardItems.plugins {
    //             elements {
    //                 insurancecalculator {
    //                     iconIdentifier = insurnace_premium-plugin-insurancecalculator
    //                     title = LLL:EXT:insurnace_premium/Resources/Private/Language/locallang_db.xlf:tx_insurnace_premium_insurancecalculator.name
    //                     description = LLL:EXT:insurnace_premium/Resources/Private/Language/locallang_db.xlf:tx_insurnace_premium_insurancecalculator.description
    //                     tt_content_defValues {
    //                         CType = list
    //                         list_type = insurnacepremium_insurancecalculator
    //                     }
    //                 }
    //             }
    //             show = *
    //         }
    //    }'
    // );

    //Hook
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass']['insurnacepremium']
        = PolicyDataHandlerHook::class;

    // Register Cache Configuration
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['insurnacepremium_calculated']
        ??= [];

})();
