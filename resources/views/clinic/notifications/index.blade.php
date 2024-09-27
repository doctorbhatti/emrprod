@extends('layouts.master')
@section('page_header')
    Notifications
@endsection

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('root') }}"><i class="fa fa-home"></i> Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Notifications</li>
        </ol>
    </nav>
@endsection
@section('content')
<div class="container">
    <h3>All Notifications</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Message</th>
                <th>Date Sent</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($notifications as $notification)
            <tr>
                <td>{{ $notification->message }}</td>
                <td>{{ $notification->created_at->format('d M Y, h:i A') }}</td>
                <td>{{ $notification->read_status ? 'Read' : 'Unread' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $notifications->links() }} <!-- Pagination links -->
</div>
@endsection
