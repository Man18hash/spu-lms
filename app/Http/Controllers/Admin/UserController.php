<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EmploymentDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * List all clients.
     */
    public function index()
    {
        $users = User::with('employmentDetail')
                     ->where('role', 'client')
                     ->get();

        return view('admin.user', compact('users'));
    }

    /**
     * Update user, password, and employment detail.
     */
    public function update(Request $request, User $user)
    {
        // Validate inputs
        $data = $request->validate([
            'name'                   => 'required|string|max:255',
            'email'                  => 'required|email|unique:users,email,'.$user->id,
            'password'               => 'nullable|string|min:8|confirmed',
            'dob'                    => 'nullable|date',
            'address'                => 'nullable|string|max:500',

            'department'             => 'required|string|max:255',
            'position'               => 'required|string|max:255',
            'date_hired'             => 'required|date',
            'monthly_basic_salary'   => 'required|numeric',
            'payroll_account_number' => 'required|string|max:100',
            'bank_name'              => 'required|string|max:255',
            'bank_account_number'    => 'required|string|max:100',
        ]);

        // Update User fields
        $user->name    = $data['name'];
        $user->email   = $data['email'];
        $user->dob     = $data['dob'];
        $user->address = $data['address'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        // Update or create EmploymentDetail
        $user->employmentDetail()->updateOrCreate(
            ['user_id' => $user->id],
            [
              'department'             => $data['department'],
              'position'               => $data['position'],
              'date_hired'             => $data['date_hired'],
              'monthly_basic_salary'   => $data['monthly_basic_salary'],
              'payroll_account_number' => $data['payroll_account_number'],
              'bank_name'              => $data['bank_name'],
              'bank_account_number'    => $data['bank_account_number'],
              // leave existing file paths untouched
            ]
        );

        return redirect()->route('admin.user')
                         ->with('success','User & employment details updated!');
    }

    /**
     * Delete user (and cascade employment detail).
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.user')
                         ->with('success','User deleted.');
    }
}
