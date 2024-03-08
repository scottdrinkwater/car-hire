<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @param int $age_from
 * @param int $age_to
 * @param float $additional_cost
 */
class AgePolicy extends Model
{
    use HasFactory;

    protected $casts = [
        'additional_cost' => 'float',
    ];
}
