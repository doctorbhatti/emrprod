@extends("layouts.website.layout")

@section("title", 'Healthy Life Clinic EMR | Forgot Password')

@section("content")
    <!-- ========== PAGE TITLE ========== -->
    <section class="s-pageheader">
        <div class="row">
        <div class="col-full text-center" style="color: white;">
                <h1 class="display-1" style="color: white;">Forgot Your Password?</h1>
                <p class="lead" style="color: white;">
                    Only the password of the <strong>Admin Account</strong> of a clinic can be reset using email. 
                    To reset passwords of other types of accounts, please contact the clinic's admin.
                </p>
            </div>
        </div>
    </section>

    <!-- ========== PAGE CONTENT ========== -->
    <section class="s-contact">
        <div class="row">
            <div class="col-full">
                @if (session('status'))
                    <div class="alert-box alert-box--success">
                        <span>{{ session('status') }}</span>
                        <a href="#" class="alert-box__close"></a>
                    </div>
                @endif

                <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
                    {!! csrf_field() !!}

                    <div class="form-field{{ $errors->has('email') ? ' has-error' : '' }}">
                        <input type="email" class="full-width" name="email" value="{{ old('email') }}" placeholder="Email Address">
                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn--primary full-width">
                            <i class="fa fa-envelope"></i> Send Password Reset Link
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
