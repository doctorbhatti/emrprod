@extends("layouts.website.layout")

@section("title", 'Healthy Life Clinic EMR | Reset Password')

@section("content")
<!-- ========== PAGE TITLE ========== -->

<section class="s-pageheader">
    <div class="row current">
        <div class="col-full">
            <h1 style="color: #fff" class="text-center">Reset Your Password</h1>
            <h5 style="color: #fff" class="text-center">You can log in to Healthy Life Clinic EMR once you reset the
                password</h5>
        </div>
    </div>
</section>

<!-- ========== PAGE CONTENT ========== -->
<section class="s-contact">
    <div class="row contact-content">
        <div class="col-full">
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/reset') }}">
                {!! csrf_field() !!}

                <input type="hidden" name="token" value="{{ $token }}">
                @if ($errors->has('token'))
                    <span class="help-block" style="color: red; margin-bottom: 15px;">
                        <strong>{{ $errors->first('token') }}</strong>
                    </span>
                @endif
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}"
                    style="display: flex; align-items: center; margin-bottom: 15px;">
                    <label class="control-label" style="color: white; margin-right: 10px;">E-Mail Address</label>
                    <input type="email" class="form-control" name="email" value="{{ $email ?? old('email') }}"
                        style="color: black; background-color: white; border: 1px solid white; flex: 1; width: 300px; caret-color: white;">

                    @if ($errors->has('email'))
                        <span class="help-block" style="color: red; margin-left: 10px;">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}"
                    style="display: flex; align-items: center; margin-bottom: 15px;">
                    <label class="control-label" style="color: white; margin-right: 10px;">Password</label>
                    <input type="password" class="form-control" name="password"
                        style="color: black; background-color: white; border: 1px solid white; flex: 1; width: 300px; caret-color: white;">

                    @if ($errors->has('password'))
                        <span class="help-block" style="color: red; margin-left: 10px;">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}"
                    style="display: flex; align-items: center; margin-bottom: 15px;">
                    <label class="control-label" style="color: white; margin-right: 10px;">Confirm Password</label>
                    <input type="password" class="form-control" name="password_confirmation"
                        style="color: black; background-color: white; border: 1px solid white; flex: 1; width: 300px; caret-color: white;">

                    @if ($errors->has('password_confirmation'))
                        <span class="help-block" style="color: red; margin-left: 10px;">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-4">
                        <button type="submit" class="btn btn--primary full-width">
                            <i class="fa fa-btn fa-refresh"></i> Reset Password
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<style>
    /* Additional styling to ensure visibility */
    .form-control {
        color: black !important;
        /* Ensure entered text is black */
        background-color: transparent !important;
        /* Ensure background is white */
        border: 1px solid white !important;
        /* Keep the border white */
        width: 100%;
        /* Make inputs full width */
    }

    .control-label {
        color: white !important;
        /* Ensure labels are white */
    }

    .help-block {
        color: red !important;
        /* Error message color */
    }

    .caret-color {
        color: white !important;
    }
</style>
@endsection