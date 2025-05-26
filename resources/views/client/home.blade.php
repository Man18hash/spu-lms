@extends('layouts.client')

@section('title', 'Client Home')

@section('content')
  <h1>Welcome, {{ auth()->user()->name }} (Client)</h1>

  <h3 class="mt-4">My Released Loans</h3>

  @if($releasedLoans->isEmpty())
    <p>You have no released loans at the moment.</p>
  @else
    <table class="table table-striped">
      <thead>
        <tr>
          <th>#</th>
          <th>Amount</th>
          <th>Term</th>
          <th>Released On</th>
          <th>View</th>
        </tr>
      </thead>
      <tbody>
        @foreach($releasedLoans as $loan)
          <tr>
            <td>{{ $loan->id }}</td>
            <td>₱{{ number_format($loan->amount, 2) }}</td>
            <td>{{ $loan->term }} months</td>
            <td>{{ $loan->status_changed_at?->format('M d, Y') ?? '—' }}</td>
            <td>
              <button class="btn btn-sm btn-outline-primary"
                      data-bs-toggle="modal"
                      data-bs-target="#ledgerModal{{ $loan->id }}">
                View Ledger
              </button>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif

  {{-- Ledger Modals --}}
  @foreach($releasedLoans as $loan)
    <div class="modal fade" id="ledgerModal{{ $loan->id }}" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">

          <div class="modal-header">
            <h5 class="modal-title">Subsidiary Ledger — Loan #{{ $loan->id }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>

          <div class="modal-body">
            <p><strong>Amount:</strong> ₱{{ number_format($loan->amount, 2) }}</p>
            <p><strong>Term:</strong> {{ $loan->term }} months</p>

            @if($loan->expectedSchedules->isEmpty())
              <p>No ledger entries available.</p>
            @else
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Month</th>
                    <th>Amount Due</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($loan->expectedSchedules->sortBy('due_date') as $entry)
                    <tr>
                      <td>{{ $entry->due_date->format('F Y') }}</td>
                      <td>₱{{ number_format($entry->amount_due, 2) }}</td>
                      <td>
                        @if($entry->repayments->isNotEmpty())
                          <span class="text-success">Paid</span>
                        @else
                          <span class="text-danger">Unpaid</span>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            @endif
          </div>

          <div class="modal-footer">
            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>

        </div>
      </div>
    </div>
  @endforeach
@endsection
