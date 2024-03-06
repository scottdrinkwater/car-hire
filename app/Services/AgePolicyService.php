<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\AgePolicy;

class AgePolicyService
{
    /**
     * Get the additional cost to add onto the base cost based on the users age.
     * 
     * @param int $age
     * @return float
     */
    public function getAdditionCost(int $age): float
    {
        $additionalCost = AgePolicy::query()
            ->where('age_from', '<=', $age)
            ->where('age_to', '>=', $age)
            ->pluck('additional_cost')
            ->first();

        return (float) $additionalCost ?? 0;
    }
}