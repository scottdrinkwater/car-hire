<?php

namespace Tests\Unit\Services;

use App\Models\AgePolicy;
use App\Repositories\AgePolicyRepository;
use App\Services\AgePolicyService;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class AgePolicyServiceTest extends TestCase
{
    private AgePolicyService $service;
    private AgePolicyRepository|MockObject $agePolicyRepository;

    public function setUp(): void
    {
        $this->agePolicyRepository = $this->createStub(AgePolicyRepository::class);
        $this->service = new AgePolicyService(
            $this->agePolicyRepository
        );
    }

    public function testGetAdditionalCostReturnsCorrectCost()
    {
        // Arrange
        $age = 98;
        $agePolicy = AgePolicy::factory()->make([
            'age_from' => 70,
            'age_to' => 120,
            'additional_cost' => 99.99,
        ]);
        $this->agePolicyRepository
            ->method('findAgePolicy')
            ->with($age)
            ->willReturn($agePolicy);

        // Act
        $additionalCost = $this->service->getAdditionCost($age);

        // Assert
        $this->assertEquals($agePolicy->additional_cost, $additionalCost);
    }

    public function testGetAdditionalCostReturnsZeroWhenNoPolicy()
    {
        // Arrange
        $age = 54;
        $agePolicy = AgePolicy::factory()->make([
            'age_from' => 70,
            'age_to' => 120,
            'additional_cost' => 99.99,
        ]);
        $this->agePolicyRepository
            ->method('findAgePolicy')
            ->with($age)
            ->willReturn(null);

        // Act
        $additionalCost = $this->service->getAdditionCost($age);

        // Assert
        $this->assertEquals(0, $additionalCost);
    }
}
