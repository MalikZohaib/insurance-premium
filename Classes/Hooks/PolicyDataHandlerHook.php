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
        DataHandler $dataHandler
    ): void {

        if ($table !== 'tx_insurnacepremium_domain_model_insurancepolicies' || !isset($fieldArray['body'])) {
            return;
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

        $hasError = false;
        $message = '';

        // Validate the 'body' JSON field
        if (!is_array($incomingFieldArray['body'])) {
            $message .= 'Invalid JSON format in "body".';
            $hasError = true;
        } else {
            foreach ($incomingFieldArray['body'] as $range => $amount) {
                if (!preg_match('/^\d+-\d+$/', $range)) {
                    $message .= "Invalid age range: {$range}";
                    $hasError = true;
                }
                if (!is_numeric($amount)) {
                    $message .= "Invalid amount for range {$range}: must be numeric.";
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
