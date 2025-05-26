<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repayment extends Model
{
    protected $fillable = [
        'expected_schedule_id',
        'payment_amount',
        'payment_date',
        'or_number',
        'or_date',
        'penalty_amount',
        'returned_check',
        'deferred',
        'remarks',
    ];

    protected $casts = [
        'payment_date'   => 'date',
        'or_date'        => 'date',
        'returned_check' => 'boolean',
        'deferred'       => 'boolean',
    ];

    /**
     * The expected schedule this repayment belongs to.
     */
    public function expectedSchedule()
    {
        return $this->belongsTo(\App\Models\ExpectedSchedule::class);
    }
}
