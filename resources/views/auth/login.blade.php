@extends("layouts.website.layout")

@section("title", 'Healthy Life Clinic EMR | Sign In')

@section("content")

<section id="home" class="s-home target-section" data-parallax="scroll"
    data-image-src="{{asset('FrontTheme/images/hero-bg2.jpeg')}}" data-natural-width=3000 data-natural-height=2000
    data-position-y=center>

    <div class="overlay"></div>
    <div class="shadow-overlay"></div>

    <div class="home-content">
        <div class="myContainer">
            <h3>Please Sign in to Continue</h3>

            <div id="formDiv">
                <div class="myheader">
                    <div>HLC<span> | EMR</span></div>
                </div>
                <br>
                <div class="login">
                    <form action="{{url('login')}}" method="post">
                        {!! csrf_field() !!}

                        {{-- Success Message --}}
                        @if(session()->has('success'))
                            <div class="alert-box alert-box--success overlay-message">
                                <p>Success!</p> {{ session('success') }}
                                <i class="fa fa-times alert-box__close" aria-hidden="true"></i>
                            </div><!-- /success -->
                        @endif

                        {{-- General error message --}}
                        @if ($errors->has('general'))
                            <div class="alert-box alert-box--error overlay-message">
                                <p>Oops!</p>{{ $errors->first('general') }}
                                <i class="fa fa-times alert-box__close" aria-hidden="true"></i>
                            </div><!-- /error -->
                        @endif

                        @if (session('status'))
                            <div class="alert-box alert-box--success overlay-message">
                                {{ session('status') }}
                                <i class="fa fa-times alert-box__close" aria-hidden="true"></i>
                            </div>
                        @endif

                        <div class="form-field has-feedback {{ $errors->has('username') ? 'has-error' : '' }}">
                            <input type="text" placeholder="Username" name="username" id="username"
                                value="{{ old('username') }}">
                            @if ($errors->has('username'))
                                <div class="alert-box alert-box--error overlay-message">
                                    <p>Oops!</p>{{ $errors->first('username') }}
                                    <i class="fa fa-times alert-box__close" aria-hidden="true"></i>
                                </div><!-- /error -->
                            @endif
                        </div><br>

                        <div class="form-field has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
                            <input type="password" placeholder="Password" name="password" id="password-field"
                                value="{{ old('password') }}">
                            @if ($errors->has('password'))
                                <div class="alert-box alert-box--error overlay-message">
                                    <p>Oops!</p>{{ $errors->first('password') }}
                                    <i class="fa fa-times alert-box__close" aria-hidden="true"></i>
                                </div><!-- /error -->
                            @endif
                        </div>

                        <input type="checkbox" onclick="showHidePassword()">
                        <strong style="color: #fff;">Show Password</strong>
                        <br>
                        <div class="center-links">
                            <button type="submit" class="btn btn--stroke">Login</button>
                            <p class="text-end"><a href="{{ url('/password/reset') }}">Forgot Password?</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="home-content__line"></div>
    </div>
</section>
<style>
    .overlay-message {
    position: absolute;
    top: 10px; /* Adjust as necessary */
    left: 50%;
    transform: translateX(-50%);
    z-index: 9999;
    width: 80%;
    padding: 10px;
    color: #fff;
    text-align: center;
    border-radius: 5px;
}

.alert-box__close {
    position: absolute;
    right: 10px;
    top: 5px;
    cursor: pointer;
}

.myContainer {
    position: relative;
}

</style>
<script>
    function showHidePassword() {
        var x = document.getElementById("password-field");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
</script>

@endsection
