<?php

declare(strict_types=1);

namespace Zohaibdev\InsurnacePremium\Service;

use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\VariableFrontend;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Zohaibdev\InsurnacePremium\Domain\Model\Dto\PolicySearch;

class AgeContributionCacheService
{
    
    public function __construct(
        protected VariableFrontend $cache
    ) {}

    public function getCache(PolicySearch $policySearch): ?float
    {
        $cacheKey = $this->generateCacheKey($policySearch);

        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        // If cache does not exist, return null
        return null;
    }

    public function setCache(PolicySearch $policySearch, float $contribution): void
    {
        $cacheKey = $this->generateCacheKey($policySearch);
        $this->cache->set(
            $cacheKey,
            $contribution,
            ['policy_' . $policySearch->getPolicyUid()],
            86400
        ); // 1 day
    }

    public function clearCacheByTag(int $policyUid): void
    {
        $this->cache->flushByTag('policy_' . $policyUid);
    }

    protected function generateCacheKey(PolicySearch $policySearch): string
    {
        return 'policy_' . $policySearch->getPolicyUid() . '_age_' . $policySearch->getAge();
    }
}