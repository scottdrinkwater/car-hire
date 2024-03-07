<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Car;
use App\Repositories\BookingRepository;
use App\Services\AgePolicyService;
use Illuminate\Http\Response;

class BookingController extends Controller
{
    private BookingRepository $bookingRepository;
    private AgePolicyService $agePolicyService;
    
    public function __construct(
        BookingRepository $bookingRepository,
        AgePolicyService $agePolicyService
    ) {
        $this->bookingRepository = $bookingRepository;
        $this->agePolicyService = $agePolicyService;
    }

    public function store(BookingRequest $request)
    {
        $age = (int) $request->input('age');
        $carId = $request->input('car_id');

        $car = Car::find($carId);

        $additionalCost = $this->agePolicyService->getAdditionCost($age);

        $booking = Booking::make($request->validated());
        $booking->cost = $car->cost + $additionalCost;

        $overlappingBookingCount = $this->bookingRepository->countOverlapping(
            $booking->from_date, 
            $booking->to_date,
            $car
        );

        if ($overlappingBookingCount > 0) {
            return response()->json(['error' => 'A booking already exists at this time for this car.'], Response::HTTP_CONFLICT);
        }

        $booking->save();

        return BookingResource::make($booking);
    }

    public function update(BookingRequest $request, Booking $booking)
    {
        $requestedCar = Car::find($request->car_id);

        $age = (int) $request->input('age');
        $additionalCost = $this->agePolicyService->getAdditionCost($age);

        $booking->from_date = $request->from_date;
        $booking->to_date = $request->to_date;
        $booking->car_id = $requestedCar->getKey();
        $booking->cost = $requestedCar->cost + $additionalCost;

        $overlappingBookingCount = $this->bookingRepository->countOverlappingExcludingId(
            $booking->from_date, 
            $booking->to_date,
            $requestedCar,
            $booking->getKey()
        );

        if ($overlappingBookingCount > 0) {
            return response()->json(['error' => 'A booking already exists at this time for this car.'], Response::HTTP_CONFLICT);
        }

        $booking->save();

        return BookingResource::make($booking)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();

        return response()->json([], Response::HTTP_ACCEPTED);
    }
}
