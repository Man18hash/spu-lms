<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmploymentDetail extends Model
{
    protected $fillable = [
        'user_id',
        'department',
        'position',
        'date_hired',
        'monthly_basic_salary',
        'payroll_account_number',
        'bank_name',
        'bank_account_number',
        'gov_id_path',
        'payslip_path',
        'photo_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
