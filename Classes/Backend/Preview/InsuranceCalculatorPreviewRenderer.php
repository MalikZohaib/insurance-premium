<?php

declare(strict_types=1);

namespace Zohaibdev\InsurnacePremium\Backend\Preview;

use TYPO3\CMS\Backend\Preview\PreviewRendererInterface;
use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem;
use TYPO3\CMS\Backend\View\PageLayoutView;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Backend\Utility\BackendUtility;

final class InsuranceCalculatorPreviewRenderer implements PreviewRendererInterface
{
    public function renderPageModulePreviewContent(GridColumnItem $item): string
    {
        $row = $item->getRecord();
        // Extract FlexForm XML and convert to array
        $flexFormData = $row['pi_flexform'] ?? '';
        $settings = $this->getFlexFormSettings($flexFormData);
        $policyUid = (int) ($settings['settings.policy'] ?? 0);
        $policyRecord = [];

        if ($policyUid > 0) {
            $policyRecord = BackendUtility::getRecord(
                'tx_insurnacepremium_domain_model_insurancepolicies',
                $policyUid
            );
        }

        // Render preview HTML
        $html = '<div class="insurance-plugin-preview">';

        if (!empty($policyRecord)) {
            $html .= 'Selected Policy: <em>' . htmlspecialchars($policyRecord['title'] ?? '[No Title]') . '</em>';
        } else {
            $html .= 'No policy selected.';
        }

        $html .= '</div>';

        return $html;
    }

    /**
     * Extracts FlexForm settings into a flat array.
     */
    private function getFlexFormSettings(string $flexFormXml): array
    {
        if (empty($flexFormXml)) {
            return [];
        }

        $settings = [];

        try {
            $flexFormArray = GeneralUtility::xml2array($flexFormXml);
            if (!is_array($flexFormArray['data']['sDEF']['lDEF'] ?? null)) {
                return [];
            }

            foreach ($flexFormArray['data']['sDEF']['lDEF'] as $key => $value) {
                if (isset($value['vDEF'])) {
                    $settings[$key] = $value['vDEF'];
                }
            }
        } catch (\Exception $e) {
            // Log or silently ignore malformed FlexForm XML
        }

        return $settings;
    }

    private function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }

    /**
     * @inheritDoc
     */
    public function renderPageModulePreviewFooter(GridColumnItem $item): string
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function renderPageModulePreviewHeader(GridColumnItem $item): string
    {
        return '<div class="insurance-plugin-preview-header"><strong>Insurance Plugin Preview</strong></div>';
    }

    /**
     * @inheritDoc
     */
    public function wrapPageModulePreview(string $previewHeader, string $previewContent, GridColumnItem $item): string
    {
        return '<div class="insurance-plugin-preview-wrapper">' . $previewHeader . $previewContent . '</div>';
    }
}
