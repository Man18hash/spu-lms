@extends('layouts.bookkeeper')

@section('title', 'Bookkeeper Home')

@section('content')
  <h1>Welcome, {{ auth()->user()->name }} (Bookkeeper)</h1>
  <p>This is your dashboard. Use the navigation to access features.</p>
@endsection
