<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Car;
use Illuminate\Support\Collection;

final class CarRepository
{
    public function findNotInIds(Collection $ids): Collection
    {
        return Car::query()
            ->whereNotIn('id', $ids)
            ->get();
    } 
}