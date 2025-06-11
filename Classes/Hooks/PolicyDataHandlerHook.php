<?php

declare(strict_types=1);

namespace Zohaibdev\InsurnacePremium\Hooks;

use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use Psr\Log\LoggerInterface;
use TYPO3\CMS\Core\Utility\MathUtility;

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

        // Validate the 'body' JSON field
        if (!is_array($fieldArray['body'])) {
            throw new \RuntimeException('Invalid JSON format in "body".');
        }
        debug($fieldArray['body']);
        foreach ($fieldArray['body'] as $range => $amount) {
            if (!preg_match('/^\d+-\d+$/', $range)) {
                throw new \RuntimeException("Invalid age range: {$range}");
            }
            if (!is_numeric($amount)) {
                throw new \RuntimeException("Invalid amount for range {$range}: must be numeric.");
            }
        }
    }
    // private function log(string $message, string $level = 'info'): void
    // {
    //     $logger = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Log\LogManager::class)
    //     ->getLogger(__CLASS__);
    //     $logger->$level('[InsurancePolicyHook] ' . $message);
    // }
}
