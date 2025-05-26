@extends('layouts.bookkeeper')

@section('content')
<h1>Welcome, {{ auth()->user()->name }} (Bookkeeper)</h1>
@endsection
