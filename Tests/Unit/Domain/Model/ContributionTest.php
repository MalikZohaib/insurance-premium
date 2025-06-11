<?php

declare(strict_types=1);

namespace Zohaibdev\InsurnacePremium\Tests\Unit\Domain\Model;

use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case
 *
 * @author Zohaib <info@zohaib.dev>
 */
class ContributionTest extends UnitTestCase
{
    /**
     * @var \Zohaibdev\InsurnacePremium\Domain\Model\Contribution|MockObject|AccessibleObjectInterface
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = $this->getAccessibleMock(
            \Zohaibdev\InsurnacePremium\Domain\Model\Contribution::class,
            ['dummy']
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getAmountReturnsInitialValueForFloat(): void
    {
        self::assertSame(
            0.0,
            $this->subject->getAmount()
        );
    }

    /**
     * @test
     */
    public function setAmountForFloatSetsAmount(): void
    {
        $this->subject->setAmount(3.14159265);

        self::assertEquals(3.14159265, $this->subject->_get('amount'));
    }

    /**
     * @test
     */
    public function getMinAgeReturnsInitialValueForInt(): void
    {
        self::assertSame(
            0,
            $this->subject->getMinAge()
        );
    }

    /**
     * @test
     */
    public function setMinAgeForIntSetsMinAge(): void
    {
        $this->subject->setMinAge(12);

        self::assertEquals(12, $this->subject->_get('minAge'));
    }

    /**
     * @test
     */
    public function getMaxAgeReturnsInitialValueForInt(): void
    {
        self::assertSame(
            0,
            $this->subject->getMaxAge()
        );
    }

    /**
     * @test
     */
    public function setMaxAgeForIntSetsMaxAge(): void
    {
        $this->subject->setMaxAge(12);

        self::assertEquals(12, $this->subject->_get('maxAge'));
    }
}
