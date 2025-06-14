<?php

declare(strict_types=1);

namespace Zohaibdev\InsurnacePremium\Hooks;

use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Utility\MathUtility;
use Zohaibdev\InsurnacePremium\Service\AgeContributionCacheService;

class PolicyDataHandlerHook
{
    public function processDatamap_afterDatabaseOperations(
        $status,
        $table,
        $id,
        array $fieldArray,
        DataHandler $pObj
    ): void {

        if ($table !== 'tx_insurnacepremium_domain_model_insurancepolicies' || !isset($fieldArray['body'])) {
            return;
        }

        // Fetch the original UID if this is a localization
        if (
            isset($pObj->datamap[$table][$id]['l10n_parent']) &&
            (int) $pObj->datamap[$table][$id]['l10n_parent'] > 0
        ) {
            $id = (int) $pObj->datamap[$table][$id]['l10n_parent'];
        }

        // Remove the cache for the policy
        $cache = GeneralUtility::makeInstance(CacheManager::class)->getCache('insurnacepremium_calculated');
        $ageContributionCacheService = GeneralUtility::makeInstance(
            AgeContributionCacheService::class,
            $cache
        );
        $ageContributionCacheService->clearCacheByTag((int) $id);
    }

    public function processDatamap_preProcessFieldArray(
        array &$incomingFieldArray,
        string $table,
        string $id,
        DataHandler $pObj
    ): void {
        if ($table !== 'tx_insurnacepremium_domain_model_insurancepolicies') {
            return;
        }

        // Validate the "body" field for JSON structure and content
        $rawBody = $incomingFieldArray['body'] ?? '';
        if (!is_string($rawBody) || trim($rawBody) === '') {
            // When called for translated record, the body might not be set
            return;
        }
        $decoded = json_decode($rawBody, true);
        $jsonError = json_last_error();

        $hasError = false;
        $message = '';

        if (!is_array($decoded) || $jsonError !== JSON_ERROR_NONE) {
            $message .= 'The "body" field contains invalid JSON: ' . json_last_error_msg();
            $hasError = true;
        } else {
            foreach ($decoded as $range => $amount) {
                if (!preg_match('/^\d+-\d+$/', $range)) {
                    $message .= "Invalid age range format: {$range} (should be 'min-max'). ";
                    $hasError = true;
                }
                if (!is_numeric($amount)) {
                    $message .= "Invalid value for range {$range}: must be numeric.";
                    $hasError = true;
                }
            }
        }

        if ($hasError) {
            // Unset to prevent invalid save
            unset($incomingFieldArray['body']);

            /** @var FlashMessageService $flashMessageService */
            $flashMessageService = GeneralUtility::makeInstance(FlashMessageService::class);
            $defaultFlashMessageQueue = $flashMessageService->getMessageQueueByIdentifier();

            $flashMessage = GeneralUtility::makeInstance(
                FlashMessage::class,
                $message,
                'Validation error',
                ContextualFeedbackSeverity::ERROR,
                true
            );
            $defaultFlashMessageQueue->addMessage($flashMessage);

            // Stop further processing and reload the edit form
            return;
        }
    }
}
