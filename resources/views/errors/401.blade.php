@extends(Auth::check() ? 'layouts.master' : 'layouts.app')

@section('content')
    <div class="container-fluid mt-4">
        <div class="alert alert-danger d-flex justify-content-between align-items-center" role="alert">
            <span>Unauthorized! The requested action is unauthorized.</span>
            <a class="btn btn-primary" href="{{ url('/') }}">
                Home <i class="fa fa-arrow-right"></i>
            </a>
        </div>
    </div>
@endsection
