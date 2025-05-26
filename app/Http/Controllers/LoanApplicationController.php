<?php
// File: app/Http/Controllers/LoanApplicationController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoanApplication;
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
            'loan_key'  => 'required|string',
            'form_file' => 'required|file|mimes:doc,docx,pdf|max:4096',
            'amount'    => 'required|numeric|min:1',
            'term'      => 'required|integer|min:1',
        ]);

        $path = $request->file('form_file')
                        ->store('applications/forms', 'public');

        LoanApplication::create([
            'user_id'   => Auth::id(),
            'loan_key'  => $data['loan_key'],
            'form_path' => $path,
            'amount'    => $data['amount'],
            'term'      => $data['term'],
        ]);

        return back()->with('success', 'Your loan application has been submitted.');
    }
    public function home()
{
    $releasedLoans = auth()->user()
        ->loanApplications()
        ->with('expectedSchedules.repayments')
        ->where('status', 'released')
        ->get();

    return view('client.home', compact('releasedLoans'));
}

}
