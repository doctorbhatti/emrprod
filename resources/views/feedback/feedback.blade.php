@extends('layouts.master')

@section('page_header','Feedback')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Give Us Your Feedback!</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <h4>We will always be free!</h4>
                    <p>
                        Our objective is to <strong>continue to provide this service for free</strong>. Your
                        <strong>complaints, suggestions, and ideas are important to us</strong>. Please spend a few minutes to
                        give us your feedback, so that we can provide you with better service in the future.
                        <strong>Thank you in advance!</strong>
                    </p>
                </div>

                {{-- Success Message --}}
                @if(session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong><i class="fas fa-check"></i> Success!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ url('feedback') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="feedback" class="form-label">Complaints, Suggestions, and Ideas</label>
                        <textarea id="feedback" class="form-control @error('feedback') is-invalid @enderror" rows="8" name="feedback"
                                  placeholder="Complaints, Suggestions, and Ideas" required>{{ old('feedback') }}</textarea>
                        @error('feedback')
                            <div class="invalid-feedback">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <button class="btn btn-success" type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
