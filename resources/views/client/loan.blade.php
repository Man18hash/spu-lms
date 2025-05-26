{{-- resources/views/loans.blade.php --}}
@extends('layouts.client')

@section('title', 'Loans')

@section('content')
<div class="container py-5">
  <div class="text-center mb-4">
    <h1 class="display-4">Our Loan Products</h1>
    <p class="lead text-muted">Choose the loan that best fits your needs.</p>
  </div>

  @php
    $loans = [
      ['key'=>'appliance','title'=>'Appliance Loan','image'=>'appliance.png'],
      ['key'=>'calamity','title'=>'Calamity Loan','image'=>'calamity.png'],
      ['key'=>'education','title'=>'Educational Loan','image'=>'education.png'],
      ['key'=>'house','title'=>'House Improvement Loan','image'=>'house.png'],
      ['key'=>'laptop','title'=>'Laptop Loan','image'=>'laptop.png'],
      ['key'=>'medical','title'=>'Medical Loan','image'=>'medical.png'],
      ['key'=>'regular','title'=>'Regular Loan','image'=>'reg-loan.png'],
      ['key'=>'special','title'=>'Special Loan','image'=>'spec-loan.png'],
    ];
  @endphp

  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
    @foreach($loans as $loan)
      <div class="col">
        <div class="card h-100 shadow-sm">
          <img src="{{ asset('images/'.$loan['image']) }}"
               class="card-img-top"
               style="height:180px; object-fit:cover;"
               alt="{{ $loan['title'] }}">
          <div class="card-body text-center">
            <h5 class="card-title">{{ $loan['title'] }}</h5>
            <a href="{{ asset('forms/'.$loan['key'].'.doc') }}"
               class="btn btn-outline-secondary btn-sm mt-2"
               download>
              Download Form
            </a>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  {{-- Apply for Loan Button --}}
  <div class="text-center mt-5">
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#applyLoanModal">
      Apply for Loan
    </button>
  </div>
</div>

{{-- Apply Modal --}}
<div class="modal fade" id="applyLoanModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('client.apply') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Apply for Loan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Loan Type</label>
            <select name="loan_key" class="form-select" required>
              <option value="" disabled selected>Select a loan</option>
              @foreach($loans as $loan)
                <option value="{{ $loan['key'] }}">{{ $loan['title'] }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Upload Completed Form</label>
            <input type="file"
                   name="form_file"
                   class="form-control @error('form_file') is-invalid @enderror"
                   required>
            @error('form_file')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3">
            <label class="form-label">Amount (₱)</label>
            <input type="number"
                   name="amount"
                   class="form-control @error('amount') is-invalid @enderror"
                   placeholder="e.g. 50000"
                   required>
            @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3">
            <label class="form-label">Term (months)</label>
            <select name="term" class="form-select @error('term') is-invalid @enderror" required>
              <option value="" disabled selected>Select term</option>
              @foreach([6,12,18,24,36] as $m)
                <option value="{{ $m }}">{{ $m }} months</option>
              @endforeach
            </select>
            @error('term')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </div>
        <div class="modal-footer">
          <button type="button"
                  class="btn btn-secondary"
                  data-bs-dismiss="modal">
            Cancel
          </button>
          <button type="submit" class="btn btn-primary">
            Submit Application
          </button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection