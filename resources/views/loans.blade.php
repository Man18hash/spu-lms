{{-- resources/views/loans.blade.php --}}
@extends('layouts.guest')

@section('title', 'Loans')

@section('content')
<div class="container py-5">
  <div class="text-center mb-5">
    <h1 class="display-4">Our Loan Products</h1>
    <p class="lead text-muted">Choose the loan that best fits your needs.</p>
  </div>

  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
    @php
      $loans = [
        ['title' => 'Appliance Loan',         'image' => 'appliance.png'],
        ['title' => 'Calamity Loan',          'image' => 'calamity.png'],
        ['title' => 'Educational Loan',       'image' => 'education.png'],
        ['title' => 'House Improvement Loan', 'image' => 'house.png'],
        ['title' => 'Laptop Loan',            'image' => 'laptop.png'],
        ['title' => 'Medical Loan',           'image' => 'medical.png'],
        ['title' => 'Regular Loan',           'image' => 'reg-loan.png'],
        ['title' => 'Special Loan',           'image' => 'spec-loan.png'],
      ];
    @endphp

    @foreach($loans as $loan)
      <div class="col">
        <div class="card h-100 shadow-sm">
          <img
            src="{{ asset('images/' . $loan['image']) }}"
            class="card-img-top"
            alt="{{ $loan['title'] }}"
            style="height: 180px; object-fit: cover; border-radius: .5rem .5rem 0 0;"
          >
          <div class="card-body text-center">
            <h5 class="card-title">{{ $loan['title'] }}</h5>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</div>
@endsection
