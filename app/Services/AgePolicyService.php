<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\AgePolicyRepository;

final class AgePolicyService
{
    private AgePolicyRepository $agePolicyRepository;

    public function __construct(
        AgePolicyRepository $agePolicyRepository
    ) {
        $this->agePolicyRepository = $agePolicyRepository;
    }

    /**
     * Get the additional cost to add onto the base cost based on the users age.
     * 
     * @param int $age
     * @return float
     */
    public function getAdditionCost(int $age): float
    {
        $additionalCost = $this->agePolicyRepository->findAgePolicy($age);

        return $additionalCost->additional_cost ?? 0.00;
    }
}