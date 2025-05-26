@extends('layouts.admin')

@section('title','Transaction History')

@section('content')
  <h1>Payment History</h1>

  @if($payments->isEmpty())
    <p>No payments recorded yet.</p>
  @else
    <div class="table-responsive">
      <table class="table table-sm table-striped align-middle">
        <thead class="table-light">
          <tr>
            <th>Date</th>
            <th>Borrower</th>
            <th>Loan #</th>
            <th class="text-end">Amount</th>
            <th>OR No.</th>
            <th>OR Date</th>
            <th class="text-end">Penalty</th>
            <th>Deferred?</th>
            <th>Remarks</th>
          </tr>
        </thead>
        <tbody>
          @foreach($payments as $p)
            @php
              $sched  = $p->expectedSchedule;
              $app    = $sched?->application;
              $client = $app?->client?->name ?? 'N/A';
            @endphp
            <tr>
              <td style="white-space: nowrap;">
                {{ optional($p->payment_date)->format('Y-m-d') ?? '—' }}
              </td>
              <td>{{ $client }}</td>
              <td>{{ $app?->id ?? '—' }}</td>
              <td class="text-end">₱{{ number_format($p->payment_amount,2) }}</td>
              <td>{{ $p->or_number ?? '—' }}</td>
              <td>{{ optional($p->or_date)->format('Y-m-d') ?? '—' }}</td>
              <td class="text-end">₱{{ number_format($p->penalty_amount,2) }}</td>
              <td>{{ $p->deferred ? 'Yes' : 'No' }}</td>
              <td style="white-space: pre-wrap;">{{ $p->remarks ?? '—' }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif
@endsection
