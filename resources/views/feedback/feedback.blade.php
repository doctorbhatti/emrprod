@extends('layouts.master')

@section('page_header', 'Complain/Issue')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Submit a Support Ticket</h4>
        </div>
        <div class="card-body">
            <div class="alert alert-warning">
                <h4>Submit a Support Ticket</h4>
                <p>
                    If you're experiencing any issues while using the app,
                    <strong>please fill out the form below to submit a support ticket</strong>.
                    Describe the problem you're facing, and our team will get back to you as soon as possible.
                    <br><br>
                    Your feedback helps us resolve any technical issues quickly, so don't hesitate to reach out.
                    <strong>Thank you for your patience!</strong>
                    <br><br>
                    In case of urgency click on this <a href="https://wa.me/923276798673?text=I%20have%20ran%20into%20an%20issue%20while%20using%20HLC%20EMR%20app.">link</a> to get connected on whatsapp.
                </p>
            </div>

            {{-- Success Message --}}
            @if(session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong><i class="fas fa-check"></i> Success!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ url('feedback') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="feedback" class="form-label">Complaints / Issues</label>
                    <textarea id="feedback" class="form-control @error('feedback') is-invalid @enderror" rows="8"
                        name="feedback" placeholder="Describe the issue you are facing. Attach an image if needed."
                        required>{{ old('feedback') }}</textarea>
                    @error('feedback')
                        <div class="invalid-feedback">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="attachment" class="form-label">Attach Image (optional)</label>
                    <input type="file" class="form-control @error('attachment') is-invalid @enderror" id="attachment"
                        name="attachment" accept="image/*">
                    @error('attachment')
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