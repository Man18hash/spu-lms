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
            <button class="btn btn-outline-secondary btn-sm mt-2"
                    data-bs-toggle="modal"
                    data-bs-target="#applyLoanModal"
                    data-loan="{{ $loan['key'] }}">
              Apply Now
            </button>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</div>

{{-- Apply Modal --}}
<div class="modal fade" id="applyLoanModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <form action="{{ route('client.apply') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="loan_key" id="loan_key_input">
      <div class="modal-content">

        {{-- Header --}}
        <div class="modal-header">
          <h5 class="modal-title">Application for Loan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          {{-- Coop Info --}}
          <div class="text-center mb-4">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height:80px;">
            <h4 class="mt-2">SPU Multi-Purpose Cooperative</h4>
            <p class="mb-0">Tuguegarao City, 3500 Cagayan Valley</p>
            <p class="mb-0">CDA Reg. No. 9520-02000436 | Coop. ID No. 0104020540</p>
            <p>Email: <a href="mailto:spumultipurposecoop@yahoo.com">spumultipurposecoop@yahoo.com</a></p>
          </div>

          {{-- Member’s Personal Data --}}
          <h6 class="bg-light p-2">Member’s Personal Data</h6>
          <div class="row gy-3">
            <div class="col-md-8">
              <div class="row g-2">
                <div class="col-sm-4">
                  <input type="text" name="last_name" class="form-control" placeholder="Last Name" required>
                </div>
                <div class="col-sm-4">
                  <input type="text" name="given_name" class="form-control" placeholder="Given Name" required>
                </div>
                <div class="col-sm-4">
                  <input type="text" name="middle_name" class="form-control" placeholder="Middle Name">
                </div>
              </div>
              <textarea name="address" class="form-control mt-2" rows="2" placeholder="Home and Residence Address" required></textarea>
            </div>
            <div class="col-md-4">
              <div class="mb-2">
                <label class="form-label">Date</label>
                <input type="date" name="application_date" class="form-control" required>
              </div>
              <div class="mb-2">
                <label class="form-label d-block">Civil Status</label>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="civil_status" value="Single" required>
                  <label class="form-check-label">Single</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="civil_status" value="Married">
                  <label class="form-check-label">Married</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="civil_status" value="Widowed/Separated">
                  <label class="form-check-label">Widowed/Separated</label>
                </div>
              </div>
              <div class="mb-2">
                <input type="text" name="nationality" class="form-control" placeholder="Nationality" required>
              </div>
              <div class="mb-2">
                <input type="text" name="contact_numbers" class="form-control" placeholder="Contact Number(s)" required>
              </div>
              <div>
                <input type="text" name="department" class="form-control" placeholder="Department" required>
              </div>
            </div>
            <div class="col-12">
              <label class="form-label">Employment Status</label><br>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="employment_status" value="Permanent" required>
                <label class="form-check-label">Permanent</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="employment_status" value="Probationary">
                <label class="form-check-label">Probationary</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="employment_status" value="Other">
                <label class="form-check-label">Other</label>
                <input type="text" name="employment_status_other" class="form-control d-inline-block ms-2" style="width:auto;" placeholder="Specify">
              </div>
            </div>
          </div>

          {{-- Loan Information --}}
          <h6 class="bg-light p-2 mt-4">Loan Information</h6>
          <div class="mb-3">
            <textarea name="amount_in_words" class="form-control" rows="2" placeholder="Amount of Loan in Words" required></textarea>
          </div>
          <div class="row gy-3 align-items-end">
            <div class="col-md-4">
              <label class="form-label">Amount of Loan (₱)</label>
              <input type="number" step="0.01" name="amount" class="form-control" required>
            </div>
            <div class="col-md-4">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="loan_type" value="New Loan" required>
                <label class="form-check-label">New Loan</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="loan_type" value="Re-Loan">
                <label class="form-check-label">Re-Loan</label>
              </div>
            </div>
            <div class="col-md-4">
              <label class="form-label">Term of Loan (months)</label>
              <input type="number" name="term" class="form-control" required>
            </div>
          </div>

          {{-- Loan Re-Payment Scheme --}}
          <h6 class="bg-light p-2 mt-4">Loan Re-Payment Scheme</h6>
          <div class="mb-3">
            <p>To repay said Appliance Loan, I promise to pay starting
              <input type="date" name="repayment_start" required>
              through:
            </p>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="repayment_mode" value="Salary Deduction" required>
              <label class="form-check-label">Salary Deduction</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="repayment_mode" value="Over-The-Counter">
              <label class="form-check-label">Over-The-Counter</label>
            </div>
          </div>
          <div class="row gy-3 mb-3">
            <div class="col-md-4">
              <label class="form-label">Amount per payment (₱)</label>
              <input type="number" step="0.01" name="repayment_amount" class="form-control" required>
            </div>
            <div class="col-md-4"></div>
            <div class="col-md-4">
              <p class="form-text">Until the loan is fully paid.</p>
            </div>
          </div>

          {{-- Additional Clauses --}}
          <div class="mb-3">
            <label class="form-label">Mortgage / Collateral</label>
            <textarea name="mortgage_details" class="form-control" rows="2"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Withdrawal / Membership Termination Authorization</label>
            <textarea name="withdrawal_authorization" class="form-control" rows="3"></textarea>
          </div>

          {{-- Signatures & Notary --}}
          <div class="mb-3">
            <label class="form-label">SALARY DEDUCTION FORM (scanned)</label>
            <input type="file" name="member_signature_file" accept="image/*,application/pdf" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Co-maker’s Signature (scan or draw)</label>
            <input type="file" name="comaker_signature_file" accept="image/*,application/pdf" class="form-control">
          </div>
          <div class="mb-3">
            <label class="form-label">Notary Acknowledgement (scanned)</label>
            <input type="file" name="notary_file" accept="image/*,application/pdf" class="form-control" required>
          </div>
        </div>

        {{-- Footer --}}
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Submit Application</button>
        </div>
      </div>
    </form>
  </div>
</div>

{{-- Script to update loan_key and modal title --}}
<script>
  const loanButtons = document.querySelectorAll('[data-loan]');
  const loanInput = document.getElementById('loan_key_input');
  const modalTitle = document.querySelector('.modal-title');

  loanButtons.forEach(button => {
    button.addEventListener('click', () => {
      const key = button.getAttribute('data-loan');
      const title = button.closest('.card').querySelector('.card-title').textContent;

      loanInput.value = key;
      modalTitle.textContent = `Application for ${title}`;
    });
  });
</script>
@endsection
