<?php

namespace App\Http\Controllers\Bookkeeper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Repayment;
use App\Models\ExpectedSchedule;
use App\Models\LoanApplication;
use Carbon\Carbon;

class PaymentController extends Controller
{
    // Show the payments dashboard for Bookkeeper
    public function index()
    {
        $releasedApplications = LoanApplication::with(['client', 'expectedSchedules.repayments'])
            ->where('status', 'released')
            ->get();

        return view('bookkeeper.payment', compact('releasedApplications'));
    }

    // Show the subsidiary ledger for a loan application
    public function viewLedger(LoanApplication $application)
    {
        $application->load('expectedSchedules.repayments', 'client');
        return view('bookkeeper.ledger', compact('application'));
    }

    // Save the edited subsidiary ledger (from modal/form)
    public function storeLedger(Request $request, LoanApplication $application)
    {
        $data = $request->validate([
            'entries'           => 'required|array|min:1',
            'entries.*.month'   => 'required|date_format:Y-m',
            'entries.*.payment' => 'required|numeric|min:0',
        ]);

        $application->expectedSchedules()->delete();

        foreach ($data['entries'] as $entry) {
            $application->expectedSchedules()->create([
                'due_date'      => Carbon::parse($entry['month'] . '-01')->startOfMonth(),
                'amount_due'    => $entry['payment'],
                'months_lapsed' => 0,
            ]);
        }

        return response()->json(['success' => true], 200);
    }

    // Show repayment entry form for a specific expected schedule
    public function createRepayment(ExpectedSchedule $expectedSchedule)
    {
        $expectedSchedule->load('application.client');
        return view('bookkeeper.repayment', compact('expectedSchedule'));
    }

    // Store the repayment
    public function storeRepayment(Request $request)
    {
        $data = $request->validate([
            'expected_schedule_id' => 'required|exists:expected_schedules,id',
            'payment_amount'       => 'nullable|numeric|min:0',
            'payment_date'         => 'nullable|date',
            'or_number'            => 'nullable|string',
            'or_date'              => 'nullable|date',
            'penalty_amount'       => 'nullable|numeric|min:0',
            'returned_check'       => 'nullable|boolean',
            'deferred'             => 'nullable|boolean',
            'deferred_date'        => 'nullable|date',
            'pdc_number'           => 'nullable|string',
            'pdc_date'             => 'nullable|date',
            'remarks'              => 'nullable|string|max:255',
        ]);

        $schedule    = ExpectedSchedule::findOrFail($data['expected_schedule_id']);
        $application = $schedule->application;

        // normalize for DB NOT NULL
        $data['payment_amount'] = isset($data['payment_amount']) && $data['payment_amount'] !== '' ? $data['payment_amount'] : 0;
        $data['penalty_amount'] = isset($data['penalty_amount']) && $data['penalty_amount'] !== '' ? $data['penalty_amount'] : 0;
        $data['returned_check'] = $request->has('returned_check');
        $data['deferred']       = $request->has('deferred');

        $repayment = Repayment::create($data);

        // Defer logic
        if ($data['deferred']) {
            $origAmount = $schedule->amount_due;
            $schedule->update(['amount_due' => 0]);

            $last = $application->expectedSchedules()
                                ->orderBy('due_date','desc')
                                ->first();

            $nextDue = Carbon::parse($last->due_date)
                              ->addMonth()
                              ->startOfMonth();

            $application->expectedSchedules()->create([
                'due_date'      => $nextDue->format('Y-m-d'),
                'amount_due'    => $origAmount,
                'months_lapsed' => max(0, $nextDue->diffInMonths(now())),
            ]);
        }

        return redirect()
            ->route('bookkeeper.ledger.view', $application->id)
            ->with('success','Repayment recorded.');
    }

    // Show the edit form for a repayment
    public function editRepayment(Repayment $repayment)
    {
        $expectedSchedule = $repayment->expectedSchedule->load('application.client');
        return view('bookkeeper.repayment_edit', compact('repayment','expectedSchedule'));
    }

    // Update a repayment
    public function updateRepayment(Request $request, Repayment $repayment)
    {
        $data = $request->validate([
            'payment_amount'       => 'nullable|numeric|min:0',
            'payment_date'         => 'nullable|date',
            'or_number'            => 'nullable|string',
            'or_date'              => 'nullable|date',
            'penalty_amount'       => 'nullable|numeric|min:0',
            'returned_check'       => 'nullable|boolean',
            'deferred'             => 'nullable|boolean',
            'deferred_date'        => 'nullable|date',
            'pdc_number'           => 'nullable|string',
            'pdc_date'             => 'nullable|date',
            'remarks'              => 'nullable|string|max:255',
        ]);

        $schedule    = $repayment->expectedSchedule;
        $application = $schedule->application;

        $data['payment_amount'] = isset($data['payment_amount']) && $data['payment_amount'] !== '' ? $data['payment_amount'] : 0;
        $data['penalty_amount'] = isset($data['penalty_amount']) && $data['penalty_amount'] !== '' ? $data['penalty_amount'] : 0;
        $data['returned_check'] = $request->has('returned_check');
        $data['deferred']       = $request->has('deferred');

        $repayment->update($data);

        // Defer logic
        if ($data['deferred']) {
            $origAmount = $schedule->amount_due;
            $schedule->update(['amount_due' => 0]);

            $last = $application->expectedSchedules()
                                ->orderBy('due_date','desc')
                                ->first();

            $nextDue = Carbon::parse($last->due_date)
                              ->addMonth()
                              ->startOfMonth();

            $application->expectedSchedules()->create([
                'due_date'      => $nextDue->format('Y-m-d'),
                'amount_due'    => $origAmount,
                'months_lapsed' => max(0, $nextDue->diffInMonths(now())),
            ]);
        }

        return redirect()
            ->route('bookkeeper.ledger.view', $application->id)
            ->with('success','Repayment updated.');
    }

    // Delete a repayment
    public function destroyRepayment(Repayment $repayment)
    {
        $schedule    = $repayment->expectedSchedule;
        $application = $schedule->application;

        $repayment->delete();

        return redirect()
            ->route('bookkeeper.ledger.view', $application->id)
            ->with('success','Repayment deleted.');
    }
}
