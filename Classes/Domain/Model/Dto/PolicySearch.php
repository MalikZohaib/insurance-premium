<?php

declare(strict_types=1);

namespace Zohaibdev\InsurnacePremium\Domain\Model\Dto;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Annotation\Validate;

class PolicySearch extends AbstractEntity
{

    /**
     * @var int
     */
    #[Validate(['validator' => 'NotEmptyValidator'])]
     #[Validate(['validator' => 'Integer'])]
     protected ?int $policyUid = null;

    /**
     * @var int|null
     */
    #[Validate(['validator' => 'NotEmptyValidator'])]
    #[Validate(['validator' => 'NumberRangeValidator', 'options' => ['minimum' => 0]])]
    protected ?int $age = null;

    public function getPolicyUid(): ?int
    {
        return $this->policyUid;
    }
    
    public function setPolicyUid(?int $policyUid): void
    {
        $this->policyUid = $policyUid;
    }
    
    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): void
    {
        $this->age = $age;
    }
}