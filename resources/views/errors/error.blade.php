@extends(Auth::check() ? 'layouts.master' : 'layouts.app')

@section('content')
    <div class="container-fluid d-flex justify-content-center align-items-center min-vh-100">
        <div class="alert alert-danger text-center">
            <h4 class="alert-heading">Oops! An error occurred.</h4>
            <p class="mb-4">Something went wrong, and we couldn't process your request.</p>
            <a class="btn btn-primary" href="{{ url('/') }}">
                Home <i class="fa fa-arrow-right"></i>
            </a>
        </div>
    </div>
@endsection
