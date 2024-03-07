<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @param \DateTime $from_date
 * @param \DateTime $to_date
 * @param float $cost
 * @param Car $car
 */
class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_date',
        'to_date',
        'car_id',
        'cost',
    ];

    protected $casts = [
        'from_date' => 'immutable_datetime:Y-m-d H:i:s',
        'to_date' => 'immutable_datetime:Y-m-d H:i:s',
    ];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }
}
