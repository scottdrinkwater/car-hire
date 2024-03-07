<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\AvailableCarsRequest;
use App\Http\Resources\AvailableCarResource;
use App\Models\Booking;
use App\Services\AgePolicyService;
use App\Services\AvailableBookingsService;
use Carbon\CarbonImmutable;

class CarController extends Controller
{
    public AvailableBookingsService $availableBookingsService;
    public AgePolicyService $agePolicyService;

    public function __construct(
        AvailableBookingsService $availableBookingsService, 
        AgePolicyService $agePolicyService
    ) {
        $this->availableBookingsService = $availableBookingsService;
        $this->agePolicyService = $agePolicyService;
    }

    /**
     * Displays available cars.
     */
    public function availableCars(AvailableCarsRequest $request)
    {
        $fromDate = CarbonImmutable::parse($request->input('from_date'));
        $toDate = CarbonImmutable::parse($request->input('to_date'));
        $age = (int) $request->input('age');

        $additionalCost = $this->agePolicyService->getAdditionCost($age);
        // Pagination / chunking would be nice else this could become unwieldy 
        $availableBookings = $this->availableBookingsService->getBookingsAvailableBetween($fromDate, $toDate);
        // Do this outside of the service above to separate concerns
        $availableBookings->each(fn (Booking $availableBooking) => $availableBooking->cost += $additionalCost);

        // This will output as if it's the Car model, but with the age policy taken into account. Still conforms to being related to the Car noun.
        return AvailableCarResource::collection($availableBookings);
    }
}
