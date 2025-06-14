@extends('layouts.admin')

@section('title','Dashboard')

@section('content')
<h1 class="mb-1">Dashboard</h1>
<p class="text-muted mb-4">Loan applications</p>

{{-- Stats cards --}}
<div class="row g-4 mb-5">
  @foreach([
    ['Fully paid', $stats['fullyPaid'], 'fully_paid', 'bg-info'],
    ['Released', $stats['released'], 'released', 'bg-success'],
    ['Approved', $stats['approved'], 'approved', 'bg-primary'],
    ['Pending', $stats['pending'], 'pending', 'bg-warning'],
    ['Rejected', $stats['rejected'], 'rejected', 'bg-danger'],
    ['Cancelled', $stats['cancelled'], 'cancelled', 'bg-secondary'],
  ] as $c)
  <div class="col-sm-6 col-lg-3">
    <div class="card text-white {{ $c[3] }}">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div>{{ $c[0] }}</div>
        <div class="fs-2">{{ $c[1] }}</div>
      </div>
      <a href="{{ route('admin.application', ['status' => $c[2]]) }}"
         class="card-footer text-white text-decoration-none d-flex justify-content-between align-items-center">
        <span>View All</span>
        <i class="bi bi-chevron-right"></i>
      </a>
    </div>
  </div>
  @endforeach
</div>

{{-- Applications table --}}
<table class="table table-striped mb-5">
  <thead>
    <tr>
      <th>#</th>
      <th>Borrower</th>
      <th>Status</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    @foreach($applications as $app)
    <tr>
      <td>{{ $app->id }}</td>
      <td>{{ $app->client->name }}</td>
      <td>
        <span class="badge @if($app->status=='approved') bg-success @elseif($app->status=='rejected') bg-danger @elseif($app->status=='pending') bg-warning @else bg-secondary @endif">
          {{ ucfirst(str_replace('_',' ',$app->status)) }}
        </span>
      </td>
      <td>
        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailsModal{{ $app->id }}">View / Edit</button>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>

{{-- Detail/Edit Modals --}}
@foreach($applications as $app)
<div class="modal fade" id="detailsModal{{ $app->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Application #{{ $app->id }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        {{-- Borrower Details --}}
        <div class="card mb-4 border-secondary">
          <div class="card-header bg-light"><strong>Borrower Details</strong></div>
          <div class="card-body">
            <p><strong>Name:</strong> {{ $app->client->name }}</p>
            <p><strong>Email:</strong> {{ $app->client->email }}</p>
          </div>
        </div>

        {{-- Employment Details --}}
        <div class="card mb-4 border-secondary">
          <div class="card-header bg-light"><strong>Employment Details</strong></div>
          <div class="card-body">
            @if($ed = $app->client->employmentDetail)
              <p><strong>Department:</strong> {{ $ed->department }}</p>
              <p><strong>Position:</strong> {{ $ed->position }}</p>
              <p><strong>Date Hired:</strong> {{ \Illuminate\Support\Carbon::parse($ed->date_hired)->format('M d, Y') }}</p>
              <p><strong>Monthly Salary:</strong> ₱{{ number_format($ed->monthly_basic_salary,2) }}</p>
              <p><strong>Payroll Acct #:</strong> {{ $ed->payroll_account_number }}</p>
              <p><strong>Bank:</strong> {{ $ed->bank_name }}</p>
              <p><strong>Bank Acct #:</strong> {{ $ed->bank_account_number }}</p>
              <p><strong>Gov ID:</strong>
                <a href="{{ asset('storage/'.$ed->gov_id_path) }}" target="_blank">View</a>
              </p>
              <p><strong>Payslip:</strong>
                <a href="{{ asset('storage/'.$ed->payslip_path) }}" target="_blank">View</a>
              </p>
              <p><strong>Photo:</strong><br>
                <img src="{{ asset('storage/'.$ed->photo_path) }}" class="img-thumbnail" style="max-width:150px;">
              </p>
            @else
              <p><em>No employment details on file.</em></p>
            @endif
          </div>
        </div>

        {{-- Loan Application Details --}}
        <div class="card mb-4 border-secondary">
          <div class="card-header bg-light"><strong>Loan Application Details</strong></div>
          <div class="card-body">
            <p><strong>Loan Type:</strong> {{ ucfirst(str_replace('_',' ',$app->loan_key)) }}</p>
            <p><strong>Application Date:</strong> {{ \Carbon\Carbon::parse($app->application_date)->format('F d, Y') }}</p>
            <p><strong>Loan Category:</strong> {{ $app->loan_type }}</p>
            <p><strong>Loan Term:</strong> {{ $app->term }} month(s)</p>
            <p><strong>Loan Amount:</strong> ₱{{ number_format($app->amount, 2) }}</p>
            <p><strong>Amount in Words:</strong> {{ $app->amount_in_words }}</p>

            <hr>
            <p><strong>Address:</strong> {{ $app->address }}</p>
            <p><strong>Civil Status:</strong> {{ $app->civil_status }}</p>
            <p><strong>Nationality:</strong> {{ $app->nationality }}</p>
            <p><strong>Contact Numbers:</strong> {{ $app->contact_numbers }}</p>
            <p><strong>Department:</strong> {{ $app->department }}</p>
            <p><strong>Employment Status:</strong> {{ $app->employment_status }}
              @if($app->employment_status === 'Other' && $app->employment_status_other)
                ({{ $app->employment_status_other }})
              @endif
            </p>

            <hr>
            <p><strong>Repayment Start:</strong> {{ \Carbon\Carbon::parse($app->repayment_start)->format('F d, Y') }}</p>
            <p><strong>Repayment Mode:</strong> {{ $app->repayment_mode }}</p>
            <p><strong>Repayment Amount:</strong> ₱{{ number_format($app->repayment_amount, 2) }}</p>

            <hr>
            <p><strong>Mortgage / Collateral:</strong><br>{{ $app->mortgage_details ?: 'N/A' }}</p>
            <p><strong>Withdrawal / Termination Authorization:</strong><br>{{ $app->withdrawal_authorization ?: 'N/A' }}</p>

            <hr>
            <p><strong>Member Signature:</strong>
              @if($app->member_signature_file)
                <a href="{{ asset('storage/'.$app->member_signature_file) }}" target="_blank">View</a>
              @else
                N/A
              @endif
            </p>
            <p><strong>Co-maker Signature:</strong>
              @if($app->comaker_signature_file)
                <a href="{{ asset('storage/'.$app->comaker_signature_file) }}" target="_blank">View</a>
              @else
                N/A
              @endif
            </p>
            <p><strong>Notary Acknowledgement:</strong>
              @if($app->notary_file)
                <a href="{{ asset('storage/'.$app->notary_file) }}" target="_blank">View</a>
              @else
                N/A
              @endif
            </p>
          </div>
        </div>

        {{-- Edit Status and Date --}}
        <div class="card border-secondary">
          <div class="card-header bg-light"><strong>Edit Status</strong></div>
          <div class="card-body">
            <form action="{{ route('admin.application.update', $app->id) }}" method="POST">
              @csrf @method('PATCH')
              <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select" required>
                  @foreach(['pending','approved','rejected','fully_paid','released','cancelled'] as $status)
                    <option value="{{ $status }}" {{ $app->status === $status ? 'selected' : '' }}>
                      {{ ucfirst(str_replace('_',' ',$status)) }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label">Date of Status Change</label>
                <input type="date" name="status_changed_at" class="form-control" required
                  value="{{ optional($app->status_changed_at)->format('Y-m-d') }}">
              </div>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
@endforeach
@endsection
