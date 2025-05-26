<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ExpectedSchedule;
use App\Models\Setup;
use App\Models\Beneficiary;
use App\Models\User;

class LoanApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_id',
        'loan_key',
        'form_path',
        'amount',
        'term',
        'status',
        'status_changed_at',
    ];

    /**
     * Cast attributes to appropriate types
     */
    protected $casts = [
        'status_changed_at' => 'datetime',  // ✅ fix added
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
    ];

    /**
     * The borrowing client (a User).
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * The assigned employee (also a User).
     */
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    /**
     * The subsidiary‐ledger entries (expected schedules).
     */
    public function expectedSchedules()
    {
        return $this->hasMany(ExpectedSchedule::class);
    }

    /**
     * Optional: Repayments via expected schedules (if needed).
     */
    public function repayments()
    {
        return $this->hasManyThrough(
            \App\Models\Repayment::class,
            ExpectedSchedule::class,
            'loan_application_id',  // FK on ExpectedSchedule
            'expected_schedule_id', // FK on Repayment
            'id',                   // PK on LoanApplication
            'id'                    // PK on ExpectedSchedule
        );
    }
}
