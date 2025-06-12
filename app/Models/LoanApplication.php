<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ExpectedSchedule;
use App\Models\Repayment;
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

        // Form fields
        'last_name',
        'given_name',
        'middle_name',
        'application_date',
        'address',
        'civil_status',
        'nationality',
        'contact_numbers',
        'department',
        'employment_status',
        'employment_status_other',
        'amount_in_words',
        'loan_type',
        'repayment_start',
        'repayment_mode',
        'repayment_amount',
        'mortgage_details',
        'withdrawal_authorization',
        'member_signature_file',
        'comaker_signature_file',
        'notary_file',
    ];

    protected $casts = [
        'status_changed_at' => 'datetime',
        'application_date' => 'date',
        'repayment_start' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function expectedSchedules()
    {
        return $this->hasMany(ExpectedSchedule::class);
    }

    public function repayments()
    {
        return $this->hasManyThrough(
            Repayment::class,
            ExpectedSchedule::class,
            'loan_application_id',
            'expected_schedule_id',
            'id',
            'id'
        );
    }
}
