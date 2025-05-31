@extends('layouts.bookkeeper')

@section('title','SUBSIDIARY LEDGER - SET UP')

@section('content')
<style>
  .ledger-header {
    margin-bottom: 1.5rem;
  }
  .ledger-header h2 {
    margin: 0 0 0.5rem;
  }
  .ledger-header .org {
    font-size: 0.9rem;
    color: #666;
  }
</style>

<div class="ledger-header">
  <h2>SUBSIDIARY LEDGER - SET UP</h2>
  <div class="org">SPU LMS</div>

  {{-- Basic loan info --}}
  <p><strong>Borrower:</strong> {{ $application->client->name }}</p>
  <p><strong>Loan #:</strong> {{ $application->loan_key }}</p>
  <p><strong>Amount:</strong> ₱{{ number_format($application->amount,2) }} over <strong>{{ $application->term }}</strong> months</p>
</div>

<table class="table table-bordered">
  <thead class="table-light text-center">
    <tr>
      <th>Month Due</th>
      <th>Amount Due</th>
      <th># Mos. Lapsed</th>
      <th>Penalty</th>
      <th>Payment</th>
      <th>OR No. / Date</th>
      <th>Returned Check</th>
      <th>PDC No. / Date</th>
      <th>Deferred?</th>
      <th>Deferred Date</th>
      <th>Remarks</th>
      <th>Balance</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    @php
      $running = $application->amount;
      $now = \Carbon\Carbon::now();
      $pastDue = 0;
    @endphp

    @foreach($application->expectedSchedules->sortBy('due_date') as $sch)
      @php
        $lapsed = max(0, \Carbon\Carbon::parse($sch->due_date)->diffInMonths($now,false));
        $paid   = $sch->repayments->sum('payment_amount');
        $pen    = $sch->repayments->sum('penalty_amount');
        $running = $running - $paid + $pen;
        $last   = $sch->repayments->last();
      @endphp

      <tr>
        <td class="text-center">{{ \Carbon\Carbon::parse($sch->due_date)->format('M-Y') }}</td>
        <td class="text-end">₱{{ number_format($sch->amount_due,2) }}</td>
        <td class="text-center">{{ $lapsed }}</td>
        <td class="text-end">{{ $pen ? '₱'.number_format($pen,2) : '-' }}</td>
        <td class="text-end">{{ $paid ? '₱'.number_format($paid,2) : '-' }}</td>
        <td class="text-center">
          @if($last && $last->or_number)
            {{ $last->or_number }}<br>{{ optional($last->or_date)->format('d/m/Y') }}
          @else
            -
          @endif
        </td>
        <td class="text-center">{{ $last && $last->returned_check ? 'Yes' : '-' }}</td>
        <td class="text-center">
          @if($last && $last->pdc_number)
            {{ $last->pdc_number }}<br>{{ optional($last->pdc_date)->format('d/m/Y') }}
          @else
            -
          @endif
        </td>
        <td class="text-center">{{ $last && $last->deferred ? 'Yes' : '-' }}</td>
        <td class="text-center">
          {{ $last && $last->deferred_date 
               ? \Carbon\Carbon::parse($last->deferred_date)->format('d/m/Y') 
               : '-' }}
        </td>
        <td>{{ $last->remarks ?? '-' }}</td>
        <td class="text-end">₱{{ number_format($running,2) }}</td>
        <td class="text-center">
          {{-- Replace these with Bookkeeper routes if needed. Or make read-only --}}
          <a href="{{ route('bookkeeper.repayments.create', $sch) }}" class="btn btn-sm btn-success">
            Payment
          </a>
          @if($last)
            <a href="{{ route('bookkeeper.repayments.edit', $last) }}" class="btn btn-sm btn-warning">Edit</a>
            <form method="POST"
                  action="{{ route('bookkeeper.repayments.destroy', $last) }}"
                  class="d-inline"
                  onsubmit="return confirm('Delete this repayment?')">
              @csrf @method('DELETE')
            </form>
          @endif
        </td>
      </tr>
    @endforeach
  </tbody>
</table>

{{-- Summary --}}
@php $totalRemaining = $running; @endphp
<div class="mt-4 p-3 border rounded bg-light">
  <h5><strong>Summary</strong></h5>
  <div class="row">
    <div class="col-md-3"><strong>Total Assisted:</strong></div>
    <div class="col-md-3 text-end">₱{{ number_format($application->amount,2) }}</div>
    <div class="col-md-3"><strong>Total Past Due:</strong></div>
    <div class="col-md-3 text-end">₱{{ number_format($pastDue,2) }}</div>
  </div>
  <div class="row mt-2">
    <div class="col-md-3"><strong>Total Remaining:</strong></div>
    <div class="col-md-3 text-end">₱{{ number_format($totalRemaining,2) }}</div>
  </div>
</div>
@endsection
