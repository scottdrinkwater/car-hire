<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\AgePolicy;

class AgePolicyRepository
{
    public function findAgePolicy(int $age): ?AgePolicy
    {
        return AgePolicy::query()
            ->where('age_from', '<=', $age)
            ->where('age_to', '>=', $age)
            ->first();
    } 
}