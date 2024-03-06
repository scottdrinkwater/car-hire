<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Booking;
use App\Models\Car;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class BookingRepository
{
    public function findOverlapping(
        \DateTimeImmutable $fromDate, 
        \DateTimeImmutable $toDate,
        ?Car $car = null
    ): Collection {
        return $this->getOverlapQueryBuilder($fromDate, $toDate, $car)->get();
    }

    public function findOverlappingExcludingId(
        \DateTimeImmutable $fromDate, 
        \DateTimeImmutable $toDate,
        ?Car $car = null,
        int $bookingId
    ): Collection {
        return $this
            ->getOverlapQueryBuilder($fromDate, $toDate, $car)
            ->whereNot('id', $bookingId)
            ->get();
    }

    public function countOverlapping(
        \DateTimeImmutable $fromDate, 
        \DateTimeImmutable $toDate,
        ?Car $car = null
    ): int {
        return $this->getOverlapQueryBuilder($fromDate, $toDate, $car)->count();
    }

    public function countOverlappingExcludingId(
        \DateTimeImmutable $fromDate, 
        \DateTimeImmutable $toDate,
        ?Car $car = null,
        int $bookingId
    ): int {
        return $this
            ->getOverlapQueryBuilder($fromDate, $toDate, $car)
            ->whereNot('id', $bookingId)
            ->count();
    }

    private function getOverlapQueryBuilder(
        \DateTimeImmutable $fromDate, 
        \DateTimeImmutable $toDate,
        ?Car $car = null
    ): Builder {
        $builder =  Booking::query()
            ->where('from_date', '<=', $toDate->format('Y-m-d H:i:s'))
            ->where('to_date', '>=', $fromDate->format('Y-m-d H:i:s'));
        if ($car !== null) {
            $builder->where('car_id', '=', $car);
        }

        return $builder;
    }
}