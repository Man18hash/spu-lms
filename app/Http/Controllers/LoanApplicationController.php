<?php

namespace App\Http\Controllers;

use App\Models\LoanApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanApplicationController extends Controller
{
    /**
     * Show list of this user’s loans.
     */
    public function index()
    {
        $loans = Auth::user()
                     ->loanApplications()
                     ->latest()
                     ->get();

        return view('client.list-of-loans', compact('loans'));
    }

    /**
     * Handle the pop-up “Apply for Loan” form submission.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'loan_key' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'term' => 'required|integer|min:1',
            'last_name' => 'required|string',
            'given_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'application_date' => 'required|date',
            'address' => 'required|string',
            'civil_status' => 'required|string',
            'nationality' => 'required|string',
            'contact_numbers' => 'required|string',
            'department' => 'required|string',
            'employment_status' => 'required|string',
            'employment_status_other' => 'nullable|string',
            'amount_in_words' => 'required|string',
            'loan_type' => 'required|string',
            'repayment_start' => 'required|date',
            'repayment_mode' => 'required|string',
            'repayment_amount' => 'required|numeric',
            'mortgage_details' => 'nullable|string',
            'withdrawal_authorization' => 'nullable|string',
            'member_signature_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:4096',
            'comaker_signature_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
            'notary_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:4096',
        ]);

        // File uploads
        if ($request->hasFile('member_signature_file')) {
            $data['member_signature_file'] = $request->file('member_signature_file')->store('signatures', 'public');
        }

        if ($request->hasFile('comaker_signature_file')) {
            $data['comaker_signature_file'] = $request->file('comaker_signature_file')->store('signatures', 'public');
        }

        if ($request->hasFile('notary_file')) {
            $data['notary_file'] = $request->file('notary_file')->store('notaries', 'public');
        }

        $data['user_id'] = Auth::id();
        $data['status'] = 'pending';

        LoanApplication::create($data);

        return back()->with('success', 'Your loan application has been submitted.');
    }

    /**
     * Show user dashboard with released loans.
     */
    public function home()
    {
        $releasedLoans = Auth::user()
            ->loanApplications()
            ->with('expectedSchedules.repayments')
            ->where('status', 'released')
            ->get();

        return view('client.home', compact('releasedLoans'));
    }
}
