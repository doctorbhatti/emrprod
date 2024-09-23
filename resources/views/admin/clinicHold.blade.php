@extends('layouts.app')

@section('title', 'Clinic On Hold')

@section('content')
<div class="container mt-5">
    <div class="alert alert-warning text-center">
        <h4 class="alert-heading">Clinic On Hold</h4>
        <p>Your clinic is currently on hold due to pending payment or other administrative reasons. Please contact support for further assistance.</p>
        <hr>
        <a href="{{ url('/') }}" class="btn btn-primary">Return to Home</a>
    </div>
</div>
@endsection
