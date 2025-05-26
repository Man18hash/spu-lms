@extends('layouts.client')

@section('title', 'My Account')

@section('content')
<div class="row">
  <div class="col-md-8 offset-md-2">
    <h1 class="mb-4">My Account</h1>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Saved Details Card --}}
    @if(! empty($details))
      <div class="card mb-4">
        <div class="card-header">Saved Details</div>
        <div class="card-body">
          <dl class="row">
            @foreach([
              'Department'            => $details->department,
              'Position'              => $details->position,
              'Date Hired'            => \Illuminate\Support\Carbon::parse($details->date_hired)->format('M d, Y'),
              'Monthly Salary'        => '₱'.number_format($details->monthly_basic_salary,2),
              'Payroll Acc. #'        => $details->payroll_account_number,
              'Bank Name'             => $details->bank_name,
              'Bank Acc. #'           => $details->bank_account_number,
            ] as $label => $value)
              <dt class="col-sm-4">{{ $label }}</dt>
              <dd class="col-sm-8">{{ $value }}</dd>
            @endforeach

            <dt class="col-sm-4">Government-ID</dt>
            <dd class="col-sm-8">
              <a href="{{ Storage::url($details->gov_id_path) }}" target="_blank">Download</a>
            </dd>

            <dt class="col-sm-4">Payslip</dt>
            <dd class="col-sm-8">
              <a href="{{ Storage::url($details->payslip_path) }}" target="_blank">Download</a>
            </dd>

            <dt class="col-sm-4">Photo</dt>
            <dd class="col-sm-8">
              <img 
                src="{{ Storage::url($details->photo_path) }}" 
                alt="1×1 Photo" 
                class="img-thumbnail" 
                width="150">
            </dd>
          </dl>
        </div>
      </div>
    @endif

    {{-- Save / Update Form --}}
    <form action="{{ route('client.account.update') }}"
          method="POST"
          enctype="multipart/form-data">
      @csrf

      <h4>Employment Details</h4>
      <div class="row gx-3">
        <div class="col-md-6 mb-3">
          <label class="form-label">Department</label>
          <input type="text"
                 name="department"
                 class="form-control @error('department') is-invalid @enderror"
                 value="{{ old('department',$details->department ?? '') }}"
                 required>
          @error('department')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Position</label>
          <input type="text"
                 name="position"
                 class="form-control @error('position') is-invalid @enderror"
                 value="{{ old('position',$details->position ?? '') }}"
                 required>
          @error('position')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
      </div>

      <div class="row gx-3">
        <div class="col-md-6 mb-3">
          <label class="form-label">Date Hired</label>
          <input type="date"
                 name="date_hired"
                 class="form-control @error('date_hired') is-invalid @enderror"
                 value="{{ old('date_hired',$details->date_hired ?? '') }}"
                 required>
          @error('date_hired')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Monthly Salary</label>
          <input type="number"
                 step="0.01"
                 name="monthly_basic_salary"
                 class="form-control @error('monthly_basic_salary') is-invalid @enderror"
                 value="{{ old('monthly_basic_salary',$details->monthly_basic_salary ?? '') }}"
                 required>
          @error('monthly_basic_salary')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Payroll Account Number</label>
        <input type="text"
               name="payroll_account_number"
               class="form-control @error('payroll_account_number') is-invalid @enderror"
               value="{{ old('payroll_account_number',$details->payroll_account_number ?? '') }}"
               required>
        @error('payroll_account_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <h4>Bank / Disbursement Info</h4>
      <div class="row gx-3">
        <div class="col-md-6 mb-3">
          <label class="form-label">Bank Name</label>
          <input type="text"
                 name="bank_name"
                 class="form-control @error('bank_name') is-invalid @enderror"
                 value="{{ old('bank_name',$details->bank_name ?? '') }}"
                 required>
          @error('bank_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Bank Account Number</label>
          <input type="text"
                 name="bank_account_number"
                 class="form-control @error('bank_account_number') is-invalid @enderror"
                 value="{{ old('bank_account_number',$details->bank_account_number ?? '') }}"
                 required>
          @error('bank_account_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
      </div>

      <h4>Document Uploads</h4>
      <div class="mb-3">
        <label class="form-label">Government-Issued ID</label>
        <input type="file"
               name="gov_id"
               class="form-control @error('gov_id') is-invalid @enderror"
               required>
        @error('gov_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Latest Payslip (PDF)</label>
        <input type="file"
               name="payslip"
               class="form-control @error('payslip') is-invalid @enderror"
               required>
        @error('payslip')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <div class="mb-4">
        <label class="form-label">1×1 Photo</label>
        <input type="file"
               name="photo"
               class="form-control @error('photo') is-invalid @enderror"
               required>
        @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>

      <button type="submit" class="btn btn-success">
        {{ empty($details) ? 'Save Details' : 'Update Details' }}
      </button>
    </form>
  </div>
</div>
@endsection
