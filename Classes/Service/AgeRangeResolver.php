<?php

declare(strict_types=1);
namespace Zohaibdev\InsurnacePremium\Service;

/**
 * AgeRangeResolver
 *
 * This service is responsible for parsing age-based contribution ranges
 * from a JSON string and resolving the appropriate value based on a given age.
 *
 * Example JSON format:
 * {
 *   "0-10": "12.5",
 *   "11-20": "17.0"
 * }
 *
 * Usage:
 * $resolver = new AgeRangeResolver($json);
 * $amount = $resolver->findContributionByAge(15); // returns 17.0
 *
 * This class ensures proper range validation and can be extended to support
 * more complex range formats or dynamic value calculations.
 *
 */
class AgeRangeResolver
{
    protected array $ranges = [];

    public function __construct(string $json)
    {
        $this->ranges = json_decode($json, true) ?? [];
    }

    /**
     * Returns the matched contribution for a given age.
     */
    public function findContributionByAge(int $age): ?float
    {
        foreach ($this->ranges as $range => $value) {
            if ($this->ageInRange($age, $range)) {
                return (float)$value;
            }
        }
        return null;
    }

    /**
     * Check if age is inside a "min-max" range string.
     */
    protected function ageInRange(int $age, string $range): bool
    {
        if (!preg_match('/^(\d+)-(\d+)$/', $range, $matches)) {
            return false;
        }

        [$_, $min, $max] = $matches;
        return $age >= (int)$min && $age <= (int)$max;
    }
}
