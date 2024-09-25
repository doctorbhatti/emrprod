@extends('layouts.master')

@section('page_header')
Settings
@endsection

@section('content')

{{-- Success Message --}}
@if(session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong><i class="fa fa-check"></i> Success!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Error Message --}}
@if(session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong><i class="fa fa-ban"></i> Error!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Change Password --}}
<div class="card mb-4">
    <div class="card-header">
        <h4 class="card-title">Change Password</h4>
    </div>
    <div class="card-body">
        <form method="post" action="{{ route('changePassword') }}">
            @csrf

            <div class="mb-3">
                <label for="current_password" class="form-label">Current Password</label>
                <input type="password" class="form-control" id="current_password" name="current_password">
                @error('current_password')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">New Password</label>
                <input type="password" class="form-control" id="password" name="password">
                @error('password')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">New Password Confirmation</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                @error('password_confirmation')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fa fa-edit"></i> Change Password
            </button>
        </form>
    </div>
</div>
{{-- /Change Password --}}

@can('register', 'App\Models\User')
    {{-- Create New User --}}
    <div class="card mb-4">
        <div class="card-header">
            <h4 class="card-title">Create New User</h4>
        </div>
        <div class="card-body">
            <form method="post" action="{{ route('createAccount') }}">
                @csrf

                <div class="mb-3">
                    <label for="user_name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="user_name" name="user_name" value="{{ old('user_name') }}">
                    @error('user_name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="user_username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="user_username" name="user_username"
                        value="{{ old('user_username') }}">
                    @error('user_username')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="user_role" class="form-label">Role</label>
                    <select id="user_role" name="user_role" class="form-select">
                        <option value="">N/A</option>
                        @foreach(\App\Models\Role::where('role', '<>', 'Admin')->get() as $role)
                            <option value="{{ $role->id }}" {{ old('user_role') == $role->id ? 'selected' : '' }}>
                                {{ $role->role }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_role')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="user_password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="user_password" name="user_password">
                    @error('user_password')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="user_password_confirmation" class="form-label">Password Confirmation</label>
                    <input type="password" class="form-control" id="user_password_confirmation"
                        name="user_password_confirmation">
                    @error('user_password_confirmation')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Create User
                </button>
            </form>
        </div>
    </div>
    {{-- /Create New User --}}
@endcan

<div class="card mb-4">
    <div class="card-header">
        <h5>Update Avatar</h5>
    </div>
    <div class="card-body">
        <!-- Display current avatar -->
        <div class="text-center mb-4">
            <img src="{{ $user->avatar ? asset($user->avatar) : asset('dist/img/my_avatar.png') }}" 
                 class="img-thumbnail rounded-circle" 
                 style="width: 150px; height: 150px;" 
                 alt="User Avatar">
        </div>

        <!-- Avatar upload form -->
        <form action="{{ route('settings.updateAvatar') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="avatar" class="form-label">Upload New Avatar</label>
                <input type="file" class="form-control @error('avatar') is-invalid @enderror" id="avatar" name="avatar">
                @error('avatar')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Update Avatar</button>
            </div>
        </form>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Update Logo --}}
<div class="card mb-4">
    <div class="card-header">
        <h4 class="card-title">Update Clinic Logo</h4>
    </div>
    <div class="card-body">
        <form method="post" action="{{ route('updateLogo') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="logo" class="form-label">Upload New Logo</label>
                <input type="file" class="form-control" id="logo" name="logo">
                @error('logo')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-upload"></i> Update Logo
                </button>
            </div>
        </form>

        {{-- Display current logo --}}
        <div class="mt-4">
            <h5>Current Logo:</h5>

            @if($currentLogo)
                {{-- Display the clinic's specific logo if available --}}
                <img src="{{ asset($currentLogo) }}" alt="Current Logo" style="max-width: 150px;">
            @else
                {{-- Display the default logo if no clinic logo is set --}}
                <img src="{{ asset('FrontTheme/images/logo.png') }}" alt="Default Logo" style="max-width: 150px;">
            @endif
        </div>

    </div>
</div>

{{-- Clinic's Users --}}
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Clinic's Users</h4>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-condensed table-striped table-hover text-center">
            <thead>
                <tr>
                    <th class="col-md-4">Name</th>
                    <th class="col-md-3">Username</th>
                    <th class="col-md-3">Role</th>
                    <th class="col-md-2"></th>
                </tr>
            </thead>
            <tbody>
                @foreach(\App\Models\Clinic::getCurrentClinic()->users as $user)
                    <tr class="@if(\App\Models\User::getCurrentUser()->id === $user->id) table-success
                    @elseif($user->deactivated()) table-danger @endif">
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->role->role }}</td>
                        <td>
                            @can('delete', $user)
                                <a class="btn btn-sm @if($user->deactivated()) btn-success @else btn-danger @endif"
                                    href="{{ route('deleteAccount', ['id' => $user->id]) }}">
                                    <i class="fa @if($user->deactivated()) fa-check @else fa-recycle @endif"></i>
                                </a>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection