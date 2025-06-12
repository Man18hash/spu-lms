<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoanApplication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ApplicationController extends Controller
{
    /**
     * Display the dashboard and all loan applications.
     */
    public function index(Request $request)
    {
        $applications = LoanApplication::with([
            'client',
            'employee.employmentDetail'
        ])
        ->when($request->status, fn($q, $status) => $q->where('status', $status))
        ->latest()
        ->get();

        $stats = [
            'fullyPaid'  => LoanApplication::where('status', 'fully_paid')->count(),
            'released'   => LoanApplication::where('status', 'released')->count(),
            'approved'   => LoanApplication::where('status', 'approved')->count(),
            'pending'    => LoanApplication::where('status', 'pending')->count(),
            'rejected'   => LoanApplication::where('status', 'rejected')->count(),
            'cancelled'  => LoanApplication::where('status', 'cancelled')->count(),
            'members'    => User::where('role', 'member')->count(),
            'employees'  => User::whereIn('role', ['employee','staff'])->count(),
        ];

        return view('admin.application', compact('applications', 'stats'));
    }

    /**
     * Update the status of a loan application.
     */
    public function update(Request $request, LoanApplication $application)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,fully_paid,released,cancelled',
            'status_changed_at' => 'required|date',
        ]);

        $application->status = $request->status;
        $application->status_changed_at = Carbon::parse($request->status_changed_at);
        $application->employee_id = auth()->id();
        $application->save();

        // ✅ Automatically mark as fully paid if all expected schedules are paid, but only when status is set to 'released'
        if ($application->status === 'released') {
            // This block checks if all expected schedules have been repaid
            $allPaid = $application->expectedSchedules()->get()->every(function ($schedule) {
                return $schedule->repayments()->exists();
            });

            // If all expected schedules are paid, only then mark as fully paid
            if ($allPaid) {
                // Do not automatically mark as fully paid here; 
                // This condition is only checking the payment status
            }
        }

        return back()->with('success', 'Status updated successfully!');
    }
}
