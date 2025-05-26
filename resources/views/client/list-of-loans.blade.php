@extends('layouts.client')

@section('title', 'My Loans')

@section('content')
  <h1>My Loans</h1>
  <p>Here’s a list of your current and past loan applications:</p>

  <table class="table table-striped">
    <thead>
      <tr>
        <th>#</th>
        <th>Loan Type</th>
        <th>Amount (₱)</th>
        <th>Term (months)</th>
        <th>Status</th>
        <th>Status Changed</th>
        <th>Applied On</th>
      </tr>
    </thead>
    <tbody>
      @forelse($loans as $loan)
      <tr>
        <td>{{ $loan->id }}</td>
        <td>{{ ucfirst($loan->loan_key) }}</td>
        <td>{{ number_format($loan->amount, 2) }}</td>
        <td>{{ $loan->term }}</td>
        <td>{{ ucfirst($loan->status) }}</td>
        <td>
          {{ optional($loan->status_changed_at)
               ->format('M d, Y h:ia') ?? '—' }}
        </td>
        <td>{{ $loan->created_at->format('M d, Y') }}</td>
      </tr>
      @empty
      <tr>
        <td colspan="7" class="text-center">No loans found.</td>
      </tr>
      @endforelse
    </tbody>
  </table>
@endsection
