<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with all released loans.
     */
    public function index()
    {
        $releasedApplications = LoanApplication::with('client')
            ->where('status', 'released')
            ->get();

        return view('admin.home', compact('releasedApplications'));
    }

    /**
     * Persist the edited subsidiary ledger into expected_schedules.
     *
     * Expects JSON: { entries: [ { month: 'YYYY-MM', payment: '1234.56' }, ... ] }
     */
    public function storeLedger(Request $request, LoanApplication $application)
    {
        // Only for released loans
        abort_unless($application->status === 'released', 403, 'Loan not released.');

        $data = $request->validate([
            'entries'           => 'required|array|min:1',
            'entries.*.month'   => 'required|date_format:Y-m',
            'entries.*.payment' => 'required|numeric|min:0',
        ]);

        // Remove any existing schedule entries
        $application->expectedSchedules()->delete();

        // Create a new schedule entry per row
        foreach ($data['entries'] as $entry) {
            $application->expectedSchedules()->create([
                // Store the first day of that month
                'due_date'      => Carbon::parse($entry['month'] . '-01')->startOfMonth(),
                'amount_due'    => $entry['payment'],
                'months_lapsed' => 0,
            ]);
        }

        return response()->json(['success' => true], 200);
    }

    /**
     * Show the saved ledger (read-only) with payment links.
     */
    public function viewLedger(LoanApplication $application)
    {
        // eager-load schedules + repayments
        $application->load('expectedSchedules.repayments');

        return view('admin.ledger', compact('application'));
    }
}
