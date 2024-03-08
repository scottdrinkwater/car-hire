<?php
 
namespace Tests\Feature\Controllers;
 
use App\Models\AgePolicy;
use App\Models\Booking;
use App\Models\Car;
use Carbon\Carbon;
use Tests\FeatureTestCase;
 
class CarControllerTest extends FeatureTestCase
{
    const COMMON_HEADERS = ['Accept' => 'application/json'];

    public function testGetAvailableCarsFailsWhenMissingRequiredParameters()
    {
        // Act
        $url = route('available_cars');
        $response = $this->get($url, self::COMMON_HEADERS);

        // Assert
        $response->assertStatus(422);
    }

    public function testFetchesAllCarsWhenNoBookings()
    {
        // Arrange
        $cars = Car::factory()->count(3)->create();

        // Act
        $parameters = http_build_query([
            'from_date' => Carbon::now()->format('Y-m-d H:i:s'),
            'to_date' => Carbon::now()->add(1, 'week')->format('Y-m-d H:i:s'),
            'age' => 42,
        ]);
        $url = sprintf(
            '%s?%s',
            route('available_cars'),
            $parameters
        );
        $response = $this->get($url, self::COMMON_HEADERS);

        // Assert
        $expectedBodyFragments = $cars->map(
            fn (Car $car) => [
                'id' => $car->id,
                'name' => $car->model,
                'registration' => $car->registration,
                'cost' => $car->cost,
            ]
        );
        $response->assertStatus(200);
        $expectedBodyFragments->each(function (array $fragment) use ($response) {
            $response->assertJsonFragment($fragment);
        });
    }
 
    public function testFetchesCarsWithAgePolicyModifier()
    {
        // Arrange
        $cars = Car::factory()->count(3)->create();
        $additionalCost = 20.00;
        $age = 19;
        
        AgePolicy::factory()->create([
            'age_from' => 18,
            'age_to' => 25,
            'additional_cost' => $additionalCost,
        ]);

        // Act
        $parameters = http_build_query([
            'from_date' => Carbon::now()->format('Y-m-d H:i:s'),
            'to_date' => Carbon::now()->add(1, 'week')->format('Y-m-d H:i:s'),
            'age' => $age,
        ]);
        $url = sprintf(
            '%s?%s',
            route('available_cars'),
            $parameters
        );
        $response = $this->get($url, self::COMMON_HEADERS);

        // Assert
        $expectedBodyFragments = $cars->map(
            fn (Car $car) => [
                'id' => $car->id,
                'name' => $car->model,
                'registration' => $car->registration,
                'cost' => $car->cost + $additionalCost,
            ]
        );
        $response->assertStatus(200);
        $expectedBodyFragments->each(function (array $fragment) use ($response) {
            $response->assertJsonFragment($fragment);
        });
    }

    public function testFetchesAvailableCarsWhenBookings()
    {
        // Arrange
        $cars = Car::factory()->count(3)->create();
        $bookedCar = $cars->first();
        $booking = Booking::factory()->create([
            'car_id' => $bookedCar->id,
        ]);

        // Act
        $parameters = http_build_query([
            'from_date' => Carbon::now()->format('Y-m-d H:i:s'),
            'to_date' => Carbon::now()->add(1, 'week')->format('Y-m-d H:i:s'),
            'age' => 42,
        ]);
        $url = sprintf(
            '%s?%s',
            route('available_cars'),
            $parameters
        );
        $response = $this->get($url, self::COMMON_HEADERS);

        // Assert
        $expectedBodyFragments = $cars
            ->filter(fn (Car $car) => $car->id !== $bookedCar->id)
            ->map(
                fn (Car $car) => [
                    'id' => $car->id,
                    'name' => $car->model,
                    'registration' => $car->registration,
                    'cost' => $car->cost,
                ]
            );
        $response->assertStatus(200);
        $expectedBodyFragments->each(function (array $fragment) use ($response) {
            $response->assertJsonFragment($fragment);
        });
    }
}