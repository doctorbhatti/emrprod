@extends('layouts.app')

@section('title', "Admin | HIS")

@section('content')
<div class="container-fluid table-responsive">
    <div class="container-fluid">
        <a href="{{ route('adminLogout') }}" class="btn btn-primary">Logout</a>
    </div>

    <br>
    <h4>Clinics To Be Accepted</h4>

    {{-- Success Message --}}
    @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <h4><i class="icon fa fa-check"></i> Success!</h4>
            {{ session('success') }}
        </div>
    @endif

    {{-- Error Message --}}
    @if(session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <h4><i class="icon fa fa-ban"></i> Error!</h4>
            {{ session('error') }}
        </div>
    @endif

    <table class="table table-hover table-condensed table-bordered text-center">
        <thead class="table-dark">
            <tr>
                <th class="col-md-2">Name</th>
                <th class="col-md-1">Email</th>
                <th class="col-md-2">Address</th>
                <th class="col-md-1">Phone</th>
                <th class="col-md-1">Country</th>
                <th class="col-md-1">Currency</th>
                <th class="col-md-1">Timezone</th>
                <th class="col-md-1">Registered At (UTC)</th>
                <th class="col-md-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($clinics as $clinic)
                <tr>
                    <td>{{ $clinic->name }}</td>
                    <td>{{ $clinic->email }}</td>
                    <td>{{ $clinic->address }}</td>
                    <td>{{ $clinic->phone }}</td>
                    <td>{{ $clinic->country }}</td>
                    <td>{{ $clinic->currency }}</td>
                    <td>{{ $clinic->timezone }}</td>
                    <td>{{ $clinic->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        <a href="{{ route('acceptClinic', ['id' => $clinic->id]) }}" class="btn btn-sm btn-success">
                            Accept
                        </a>
                        <a href="{{ route('deleteClinic', ['id' => $clinic->id]) }}" class="btn btn-sm btn-danger">
                            Delete
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">No clinics awaiting acceptance.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="container-fluid table-responsive mt-4">
    <h4>Accepted Clinics</h4>

    <table class="table table-hover table-condensed table-bordered text-center">
        <thead class="table-dark">
            <tr>
                <th class="col-md-2">Name</th>
                <th class="col-md-1">Email</th>
                <th class="col-md-2">Address</th>
                <th class="col-md-1">Phone</th>
                <th class="col-md-1">Country</th>
                <th class="col-md-1">Currency</th>
                <th class="col-md-1">Timezone</th>
                <th class="col-md-1">Registered At (UTC)</th>
                <th class="col-md-1">Patients Added</th>
                <th class="col-md-2">Actions</th> <!-- Added new Actions column -->
            </tr>
        </thead>
        <tbody>
            @forelse($acceptedClinics as $clinic)
                <tr>
                    <td>{{ $clinic->name }}</td>
                    <td>{{ $clinic->email }}</td>
                    <td>{{ $clinic->address }}</td>
                    <td>{{ $clinic->phone }}</td>
                    <td>{{ $clinic->country }}</td>
                    <td>{{ $clinic->currency }}</td>
                    <td>{{ $clinic->timezone }}</td>
                    <td>{{ $clinic->created_at->format('Y-m-d H:i') }}</td>
                    <td>{{ $clinic->patients()->count() }}</td>
                    <td>
                        <!-- Hold Button -->
                        @if(!$clinic->is_held)
                            <a href="{{ route('holdClinic', ['id' => $clinic->id]) }}" class="btn btn-sm btn-warning">
                                Hold
                            </a>
                        @else
                            <a href="{{ route('unholdClinic', ['id' => $clinic->id]) }}" class="btn btn-sm btn-secondary">
                                Unhold
                            </a>
                        @endif
                        <!-- Delete Button -->
                        <a href="{{ route('deleteClinic', ['id' => $clinic->id]) }}" class="btn btn-sm btn-danger">
                            Delete
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10">No accepted clinics available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
