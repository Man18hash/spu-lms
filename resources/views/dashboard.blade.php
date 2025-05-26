{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.guest')

@section('title', 'Dashboard')

@section('content')
<style>
  /* ----- Variables ----- */
  :root {
    --primary-color: #0d6efd; /* Accent color */
    --overlay-gradient: linear-gradient(180deg, rgba(0,0,0,0.1), rgba(0,0,0,0.4));
  }

  /* ----- Intro Header ----- */
  .intro-header {
    text-align: center;
    margin-bottom: 2.5rem;
  }
  .intro-header h2 {
    font-size: 2.75rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
  }
  .intro-header p {
    font-size: 1.125rem;
    color: #6c757d;
    max-width: 800px;
    margin: 0 auto;
    line-height: 1.6;
  }

  /* ----- Carousel (Fade + Overlay) ----- */
  .carousel {
    margin-bottom: 2.5rem;
  }
  .carousel.carousel-fade .carousel-item {
    transition: opacity 0.5s ease-in-out;
  }
  .carousel-item {
    position: relative;
  }
  .carousel-item img {
    width: 100%;
    height: 450px;
    object-fit: cover;
    border-radius: 1rem;
    box-shadow: 0 8px 25px rgba(0,0,0,0.2);
  }
  .carousel-item::before {
    content: '';
    position: absolute;
    top: 0; right: 0; bottom: 0; left: 0;
    background: var(--overlay-gradient);
    border-radius: 1rem;
  }

  /* ----- Carousel Indicators (Box Style) ----- */
  .carousel-indicators {
    bottom: 15px;
  }
  .carousel-indicators [data-bs-target] {
    width: 12px;
    height: 12px;
    border-radius: 2px;              /* small rounding for box corners */
    background-color: rgba(0, 0, 0, 0.2);
    margin: 0 4px;                   /* space between boxes */
    transition: background-color 0.3s;
  }
  .carousel-indicators .active {
    background-color: var(--primary-color);
  }

  /* ----- Feature Cards ----- */
  .feature-card {
    border: none;
    border-radius: 1rem;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    transition: transform 0.3s, box-shadow 0.3s;
    padding: 1.5rem;
  }
  .feature-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
  }
 .feature-image {
  width: 100%;
  height: 180px;          /* adjust to taste */
  object-fit: cover;
  border-radius: 0.75rem 0.75rem 0 0;  /* rounded top corners to match card */
  display: block;
  margin-bottom: 1rem;
}
  .feature-title {
    margin-bottom: 0.5rem;
    font-weight: 600;
    font-size: 1.125rem;
  }
  .feature-text {
    color: #6c757d;
    line-height: 1.6;
  }
</style>

<div class="container py-5">

  <!-- 1) Introduction -->
  <div class="intro-header">
    <h2>Welcome to SPU Multi-Purpose Cooperative</h2>
    <p>
      For over four decades, we’ve empowered our community with tailored financial solutions—savings, loans, and investments—that build futures and strengthen bonds.
    </p>
  </div>

  <!-- 2) Fade Carousel -->
  <div id="elegantCarousel" class="carousel carousel-fade" data-bs-ride="carousel" data-bs-interval="2000">
    <div class="carousel-inner">
      @for($i = 1; $i <= 5; $i++)
        <div class="carousel-item @if($i === 1) active @endif">
          <img src="{{ asset("images/{$i}.jpg") }}" alt="Slide {{ $i }}">
        </div>
      @endfor
    </div>
    <div class="carousel-indicators">
      @for($i = 0; $i < 5; $i++)
        <button type="button"
                data-bs-target="#elegantCarousel"
                data-bs-slide-to="{{ $i }}"
                class="@if($i === 0) active @endif"
                aria-current="{{ $i === 0 ? 'true' : 'false' }}"
                aria-label="Slide {{ $i+1 }}">
        </button>
      @endfor
    </div>
  </div>

  <!-- 3) Feature Cards -->
  <div class="row g-4">
    <div class="col-md-4">
      <div class="card feature-card text-center h-100">
        <img src="{{ asset('images/savings.jpg') }}" alt="Savings Programs" class="feature-image">
        <div class="feature-title">Savings Programs</div>
        <div class="feature-text">
          Enjoy competitive dividends on regular, special, and time deposit accounts tailored for every member’s goals.
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card feature-card text-center h-100">
        <img src="{{ asset('images/loan.jpg') }}" alt="Loan Products" class="feature-image">
        <div class="feature-title">Loan Products</div>
        <div class="feature-text">
          Flexible salary, emergency, and educational loans designed to meet your needs with transparent terms.
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card feature-card text-center h-100">
        <img src="{{ asset('images/3.jpg') }}" alt="Member Services" class="feature-image">
        <div class="feature-title">Member Services</div>
        <div class="feature-text">
          Access online portals, attend financial literacy seminars, and enjoy dedicated support every step of the way.
        </div>
      </div>
    </div>
  </div>

</div>
@endsection
