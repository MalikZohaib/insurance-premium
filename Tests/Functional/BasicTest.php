<?php

declare(strict_types=1);

namespace Zohaibdev\InsurnacePremium\Tests\Functional;

use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

/**
 * Test case
 *
 * @author Zohaib <info@zohaib.dev>
 */
class BasicTest extends FunctionalTestCase
{
    /**
     * @var array
     */
    protected $testExtensionsToLoad = [
        'typo3conf/ext/insurnace_premium',
    ];

    /**
     * Just a dummy to show that at least one test is actually executed
     *
     * @test
     */
    public function dummy(): void
    {
        $this->assertTrue(true);
    }
}
