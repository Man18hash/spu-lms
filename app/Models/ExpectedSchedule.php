<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpectedSchedule extends Model
{
    protected $fillable = [
        'loan_application_id',
        'due_date',
        'amount_due',
        'months_lapsed',
    ];

    protected $casts = [
        'due_date'      => 'date',
        'months_lapsed' => 'integer',
        'amount_due'    => 'decimal:2',
    ];

    public function application()
    {
        return $this->belongsTo(LoanApplication::class, 'loan_application_id');
    }

    public function repayments()
    {
        return $this->hasMany(Repayment::class);
    }
}
