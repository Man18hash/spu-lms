<?php
// app/Http/Controllers/RepaymentController.php

namespace App\Http\Controllers;

use App\Models\Repayment;
use App\Models\ExpectedSchedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RepaymentController extends Controller
{
    public function create(ExpectedSchedule $expectedSchedule)
    {
        $expectedSchedule->load('application.client');
        return view('repayments.create', compact('expectedSchedule'));
    }

    public function store(Request $request)
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

        // normalize
        $data['payment_amount'] = $data['payment_amount'] ?? 0;
        $data['penalty_amount'] = $data['penalty_amount'] ?? 0;
        $data['returned_check'] = $request->has('returned_check');
        $data['deferred']       = $request->has('deferred');

        $repayment = Repayment::create($data);

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
            ->route('admin.ledger.view', $application->id)  // ← pass LoanApplication ID
            ->with('success','Repayment recorded.');
    }

    public function edit(Repayment $repayment)
    {
        $expectedSchedule = $repayment->expectedSchedule->load('application.client');
        return view('repayments.edit', compact('repayment','expectedSchedule'));
    }

    public function update(Request $request, Repayment $repayment)
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

        $data['payment_amount'] = $data['payment_amount'] ?? 0;
        $data['penalty_amount'] = $data['penalty_amount'] ?? 0;
        $data['returned_check'] = $request->has('returned_check');
        $data['deferred']       = $request->has('deferred');

        $repayment->update($data);

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
            ->route('admin.ledger.view', $application->id)  // ← pass LoanApplication ID
            ->with('success','Repayment updated.');
    }

    public function destroy(Repayment $repayment)
    {
        $schedule    = $repayment->expectedSchedule;
        $application = $schedule->application;

        $repayment->delete();

        return redirect()
            ->route('admin.ledger.view', $application->id)  // ← pass LoanApplication ID
            ->with('success','Repayment deleted.');
    }
}
