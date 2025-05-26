<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'employee_id',
        'dob',
        'address',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Convenience methods
    public function isClient()      { return $this->role === 'client'; }
    public function isBookkeeper() { return $this->role === 'bookkeeper'; }
    public function isAdmin()      { return $this->role === 'admin'; }

    /**
     * Return all loan applications this user has made.
     */
    public function loanApplications()
    {
        return $this->hasMany(\App\Models\LoanApplication::class, 'user_id');
    }

    /**
     * The one employment detail record for this user.
     */
    public function employmentDetail()
    {
        return $this->hasOne(\App\Models\EmploymentDetail::class, 'user_id');
    }
}
