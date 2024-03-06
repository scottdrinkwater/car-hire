<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Booking;
use App\Models\Car;
use App\Repositories\BookingRepository;
use Illuminate\Support\Collection;


class AvailableBookingsService
{
    private BookingRepository $bookingRepository;

    public function __construct(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    /**
     * Get all available car bookings within a date range.
     * 
     * @param \DateTimeImmutable $fromDate
     * @param \DateTimeImmutable $toDate
     * @return Collection<Booking>
     */
    public function getBookingsAvailableBetween(
        \DateTimeImmutable $fromDate, 
        \DateTimeImmutable $toDate
    ): Collection {
        $existingBookings = $this->bookingRepository
            ->findOverlapping($fromDate, $toDate);
        
        $bookedCarIds = $existingBookings->pluck('car_id');
        
        $availableCars = Car::query()
            ->whereNotIn('id', $bookedCarIds)
            ->get();

        $availableBookings = $availableCars->map(fn (Car $car) => $this->getAvailableBooking($fromDate, $toDate, $car));

        return $availableBookings;
    }

    /**
     * Generate an available booking from the car that is available and the desired date range.
     * 
     * @param \DateTimeImmutable $fromDate
     * @param \DateTimeImmutable $toDate
     * @param Car $car
     * @return Booking
     */
    private function getAvailableBooking(
        \DateTimeImmutable $fromDate, 
        \DateTimeImmutable $toDate, 
        Car $car
    ): Booking {
        $attributes = [
            'from_date' => $fromDate,
            'to_date' => $toDate,
            'cost' => $car->cost,
        ];

        $booking = new Booking($attributes);
        $booking->setRelation('car', $car);

        return $booking;
    }
}