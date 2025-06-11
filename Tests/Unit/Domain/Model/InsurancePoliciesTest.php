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
class InsurancePoliciesTest extends UnitTestCase
{
    /**
     * @var \Zohaibdev\InsurnacePremium\Domain\Model\InsurancePolicies|MockObject|AccessibleObjectInterface
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = $this->getAccessibleMock(
            \Zohaibdev\InsurnacePremium\Domain\Model\InsurancePolicies::class,
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
    public function getTitleReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getTitle()
        );
    }

    /**
     * @test
     */
    public function setTitleForStringSetsTitle(): void
    {
        $this->subject->setTitle('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('title'));
    }

    /**
     * @test
     */
    public function getBodyReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getBody()
        );
    }

    /**
     * @test
     */
    public function setBodyForStringSetsBody(): void
    {
        $this->subject->setBody('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('body'));
    }

    /**
     * @test
     */
    public function getContributionsReturnsInitialValueForContribution(): void
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getContributions()
        );
    }

    /**
     * @test
     */
    public function setContributionsForObjectStorageContainingContributionSetsContributions(): void
    {
        $contribution = new \Zohaibdev\InsurnacePremium\Domain\Model\Contribution();
        $objectStorageHoldingExactlyOneContributions = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneContributions->attach($contribution);
        $this->subject->setContributions($objectStorageHoldingExactlyOneContributions);

        self::assertEquals($objectStorageHoldingExactlyOneContributions, $this->subject->_get('contributions'));
    }

    /**
     * @test
     */
    public function addContributionToObjectStorageHoldingContributions(): void
    {
        $contribution = new \Zohaibdev\InsurnacePremium\Domain\Model\Contribution();
        $contributionsObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->onlyMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $contributionsObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($contribution));
        $this->subject->_set('contributions', $contributionsObjectStorageMock);

        $this->subject->addContribution($contribution);
    }

    /**
     * @test
     */
    public function removeContributionFromObjectStorageHoldingContributions(): void
    {
        $contribution = new \Zohaibdev\InsurnacePremium\Domain\Model\Contribution();
        $contributionsObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->onlyMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $contributionsObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($contribution));
        $this->subject->_set('contributions', $contributionsObjectStorageMock);

        $this->subject->removeContribution($contribution);
    }
}
