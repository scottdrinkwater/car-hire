<?php

namespace Tests\Unit\Services;

use App\Models\Booking;
use App\Models\Car;
use App\Repositories\BookingRepository;
use App\Repositories\CarRepository;
use App\Services\AvailableBookingsService;
use Carbon\CarbonImmutable;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class AvailableBookingsServiceTest extends TestCase
{
    private AvailableBookingsService $service;
    private BookingRepository|MockObject $bookingRepository;
    private CarRepository|MockObject $carRepository;

    public function setUp(): void
    {
        $this->bookingRepository = $this->createStub(BookingRepository::class);
        $this->carRepository = $this->createStub(CarRepository::class);
        $this->service = new AvailableBookingsService(
            $this->bookingRepository,
            $this->carRepository
        );
    }

    public function testGetBookingsAvailableBetweenWhenNoExistingBookings()
    {
        // Arrange
        $fromDate = CarbonImmutable::tomorrow();
        $toDate = CarbonImmutable::now()->add(7, 'days');
        $cars = Car::factory()->count(2)->make();
        $existingBookings = collect();

        $this->bookingRepository
            ->method('findOverlapping')
            ->with($fromDate, $toDate)
            ->willReturn($existingBookings);
        $this->carRepository
            ->method('findNotInIds')
            ->with($existingBookings->pluck('car_id'))
            ->willReturn($cars);

        // Act
        $availableBookings = $this->service->getBookingsAvailableBetween($fromDate, $toDate);

        // Assert
        $this->assertCount(2, $availableBookings);
        
        $this->assertEquals($fromDate, $availableBookings[0]->from_date);
        $this->assertEquals($toDate, $availableBookings[0]->to_date);
        $this->assertEquals($cars[0]->cost, $availableBookings[0]->cost);
        $this->assertEquals($cars[0], $availableBookings[0]->car);

        $this->assertEquals($fromDate, $availableBookings[1]->from_date);
        $this->assertEquals($toDate, $availableBookings[1]->to_date);
        $this->assertEquals($cars[1]->cost, $availableBookings[1]->cost);
        $this->assertEquals($cars[1], $availableBookings[1]->car);
    }

    public function testGetBookingsAvailableBetweenWhenExistingBookings()
    {
        // Arrange
        $fromDate = CarbonImmutable::tomorrow();
        $toDate = CarbonImmutable::now()->add(7, 'days');
        $availableCars = Car::factory()->count(2)->make();
        $bookedCar = Car::factory()->make();

        $existingBooking = Booking::factory()->make([
            'car_id' => $bookedCar->id,
        ]);
        $existingBooking->setRelation('car', $bookedCar);
        $existingBookings = collect([$existingBooking]);

        $this->bookingRepository
            ->method('findOverlapping')
            ->with($fromDate, $toDate)
            ->willReturn($existingBookings);
        $this->carRepository
            ->method('findNotInIds')
            ->with(collect($bookedCar->id))
            ->willReturn($availableCars);

        // Act
        $availableBookings = $this->service->getBookingsAvailableBetween($fromDate, $toDate);

        // Assert
        $this->assertCount(2, $availableBookings);
        
        $this->assertEquals($fromDate, $availableBookings[0]->from_date);
        $this->assertEquals($toDate, $availableBookings[0]->to_date);
        $this->assertEquals($availableCars[0]->cost, $availableBookings[0]->cost);
        $this->assertEquals($availableCars[0], $availableBookings[0]->car);

        $this->assertEquals($fromDate, $availableBookings[1]->from_date);
        $this->assertEquals($toDate, $availableBookings[1]->to_date);
        $this->assertEquals($availableCars[1]->cost, $availableBookings[1]->cost);
        $this->assertEquals($availableCars[1], $availableBookings[1]->car);
    }
}
