<p>Dear Admin,</p>
<p>You have received complain from {{ $user->name }} <br>({{ $user->clinic->email }}), <br>{{$user->clinic->name}}</p>

<p><strong>Issue:</strong></p>
<p>{{ $feedback }}</p>

@if($user)
    <p><strong>User Info:</strong> {{ $user->name }} ({{ $user->clinic->email }}) <br> {{$user->clinic->name}} <br  > {{$user->clinic->phone}}</p>
@endif

@if(isset($attachmentPath))
    <p><strong>Attached Image:</strong></p>
    <img src="{{ asset('storage/' . $attachmentPath) }}" alt="Attached Image" style="max-width: 100%; height: auto;">
@endif

<p>Best regards,</p>
<p>Your System</p>
