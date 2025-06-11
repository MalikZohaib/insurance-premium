<?php

declare(strict_types=1);

namespace Zohaibdev\InsurnacePremium\Domain\Model;


/**
 * This file is part of the "Insurance Premium" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2025 Zohaib <info@zohaib.dev>, zohaib.dev
 */

/**
 * InsurancePolicies
 */
class InsurancePolicies extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * title
     *
     * @var string
     */
    protected $title = null;

    /**
     * body
     *
     * @var string
     */
    protected $body = null;

    /**
     * __construct
     */
    public function __construct()
    {
    }

    /**
     * Returns the title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the title
     *
     * @param string $title
     * @return void
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * Returns the body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Sets the body
     *
     * @param string $body
     * @return void
     */
    public function setBody(string $body)
    {
        $this->body = $body;
    }
}
