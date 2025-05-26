@extends('layouts.guest')

@section('title','Edit Payment')

@section('content')
  <h1>
    Edit Payment for 
    {{ optional($expectedSchedule->application->client)->name ?? 'N/A' }}
    ({{ \Carbon\Carbon::parse($expectedSchedule->due_date)->format('M-Y') }})
  </h1>

  <form action="{{ route('admin.repayments.update', $repayment) }}" method="POST">
    @csrf
    @method('PUT')
    <input type="hidden" name="expected_schedule_id" value="{{ $expectedSchedule->id }}">

    {{-- 🟢 Payment Details --}}
    <div class="border rounded p-3 mb-4">
      <h5 class="mb-3">🟢 Payment Details</h5>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label>Payment Amount</label>
          <input type="number" step="0.01" name="payment_amount"
                 class="form-control"
                 value="{{ old('payment_amount', $repayment->payment_amount) }}">
        </div>
        <div class="col-md-6 mb-3">
          <label>Payment Date</label>
          <input type="date" name="payment_date"
                 class="form-control"
                 value="{{ old('payment_date', optional($repayment->payment_date)->format('Y-m-d')) }}">
        </div>
        <div class="col-md-6 mb-3">
          <label>OR Number</label>
          <input type="text" name="or_number"
                 class="form-control"
                 value="{{ old('or_number', $repayment->or_number) }}">
        </div>
        <div class="col-md-6 mb-3">
          <label>OR Date</label>
          <input type="date" name="or_date"
                 class="form-control"
                 value="{{ old('or_date', optional($repayment->or_date)->format('Y-m-d')) }}">
        </div>
      </div>
    </div>

    {{-- 🔴 Penalty --}}
    <div class="border rounded p-3 mb-4">
      <h5 class="mb-3">🔴 Penalty</h5>
      <div class="mb-3">
        <label>Penalty Amount</label>
        <input type="number" step="0.01" name="penalty_amount"
               class="form-control"
               value="{{ old('penalty_amount', $repayment->penalty_amount) }}">
      </div>
    </div>

    {{-- 🟡 Defer Payment --}}
    <div class="border rounded p-3 mb-4">
      <h5 class="mb-3">🟡 Defer Payment</h5>
      <div class="form-check mb-3">
        <input type="checkbox" name="deferred" value="1"
               class="form-check-input" id="deferred"
               {{ old('deferred', $repayment->deferred) ? 'checked':'' }}>
        <label class="form-check-label" for="deferred">
          Defer this payment
        </label>
      </div>
      <div class="mb-3">
        <label>Deferred Date</label>
        <input type="date" name="deferred_date"
               class="form-control"
               value="{{ old('deferred_date', optional($repayment->deferred_date)->format('Y-m-d')) }}">
      </div>
    </div>

    {{-- 🔵 Other Info --}}
    <div class="border rounded p-3 mb-4">
      <h5 class="mb-3">🔵 Other Info</h5>
      <div class="form-check mb-3">
        <input type="checkbox" name="returned_check" value="1"
               class="form-check-input" id="returned_check"
               {{ old('returned_check', $repayment->returned_check) ? 'checked':'' }}>
        <label class="form-check-label" for="returned_check">
          Returned Check
        </label>
      </div>
      <div class="mb-3">
        <label>PDC Number</label>
        <input type="text" name="pdc_number"
               class="form-control"
               value="{{ old('pdc_number', $repayment->pdc_number) }}">
      </div>
      <div class="mb-3">
        <label>PDC Date</label>
        <input type="date" name="pdc_date"
               class="form-control"
               value="{{ old('pdc_date', optional($repayment->pdc_date)->format('Y-m-d')) }}">
      </div>
    </div>

    {{-- 📝 Remarks --}}
    <div class="border rounded p-3 mb-4">
      <h5 class="mb-3">📝 Remarks</h5>
      <div class="mb-3">
        <textarea name="remarks" rows="3"
                  class="form-control">{{ old('remarks', $repayment->remarks) }}</textarea>
      </div>
    </div>

    {{-- Buttons --}}
    <div class="d-flex gap-2">
      <button type="submit" class="btn btn-primary">Update Payment</button>
      <a href="{{ route('admin.ledger.view', $expectedSchedule->id) }}"
         class="btn btn-secondary">Cancel</a>
    </div>
  </form>
@endsection
