<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmploymentDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AccountController extends Controller
{
    /**
     * Show the “My Account” page with saved details (if any).
     */
    public function show()
    {
        // Load the user’s one‐to‐one record or null
        $details = Auth::user()->employmentDetail;

        return view('client.account', compact('details'));
    }

    /**
     * Validate & save or update the record, then redirect back.
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'department'             => 'required|string|max:255',
            'position'               => 'required|string|max:255',
            'date_hired'             => 'required|date',
            'monthly_basic_salary'   => 'required|numeric',
            'payroll_account_number' => 'required|string|max:255',
            'bank_name'              => 'required|string|max:255',
            'bank_account_number'    => 'required|string|max:255',
            'gov_id'                 => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'payslip'                => 'required|file|mimes:pdf|max:2048',
            'photo'                  => 'required|image|max:2048',
        ]);

        // store files in public disk
        $paths = [
            'gov_id_path'  => $request->file('gov_id')->store('employment/gov_ids','public'),
            'payslip_path' => $request->file('payslip')->store('employment/payslips','public'),
            'photo_path'   => $request->file('photo')->store('employment/photos','public'),
        ];

        // create or update
        EmploymentDetail::updateOrCreate(
            ['user_id' => Auth::id()],
            array_merge($data, $paths)
        );

        return redirect()
            ->route('client.account')
            ->with('success', 'Your details have been saved.');
    }
}
