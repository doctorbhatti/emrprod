@extends("layouts.website.layout")

@section("title", 'Healthy Life Clinic EMR | Register Clinic')

@section("content")
  <!-- Bootstrap 5 CSS  -->
  <link rel="stylesheet" href="{{asset('dist/css/bootstrap@5.3.0.min.css')}}">

<!-- Bootstrap 5 JS  -->
<script src="{{asset('dist/js/bootstrap@5.3.0.min.js')}}"></script>
<section id="home" class="s-home target-section" data-parallax="scroll"
    data-image-src="{{asset('FrontTheme/images/hero-bg2.jpeg')}}" data-natural-width=3000 data-natural-height=2000
    data-position-y=center>

    <div class="overlay"></div>
    <div class="shadow-overlay"></div>

    <div class="home-content">
        <!-- ========== PAGE TITLE ========== -->
        <div class="container">
            <div class="outer">
                <div class="inner text-center">
                    <h1 class="display-1 text-uppercase" style="color: white;">Register Clinic</h1>
                    <p class="lead" style="color: white; font-weight: 300;">
                        You are one step away from experiencing the awesomeness of Healthy Life Clinic EMR
                    </p>
                </div>
            </div>
        </div>


        <div ng-app="HIS" ng-controller="ClinicRegistrationController" class="registration-section" style="margin: 25px">
            <input hidden ng-init="baseUrl='{{url("/")}}';token='{{csrf_token()}}';">
            <div class="row">
                <div ng-cloak>
                    <form role="form" method="POST" action="{{ route('registerClinic') }}"
                        ng-controller="ClinicRegistrationController">
                        {!! csrf_field() !!}

                        {{-- Error Message --}}
                        @if(session()->has('error'))
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h4><i class="icon fa fa-ban"></i> Oops!</h4>
                                {{ session('error') }}
                            </div>
                        @endif

                        @if($errors->has('terms'))
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h4><i class="icon fa fa-ban"></i> Oops!</h4>
                                {{ $errors->first('terms') }}
                            </div>
                        @endif

                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label class="text-white">Clinic Name</label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                            @if ($errors->has('name'))
                                <span class="help-block text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                            <label class="text-white">E-Mail Address</label>
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                            @if ($errors->has('email'))
                                <span class="help-block text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('address') ? 'has-error' : '' }}">
                            <label class="text-white">Address</label>
                            <input type="text" class="form-control" name="address" value="{{ old('address') }}">
                            @if ($errors->has('address'))
                                <span class="help-block text-danger">{{ $errors->first('address') }}</span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
                            <label class="text-white">Phone</label>
                            <input type="tel" class="form-control" name="phone" value="{{ old('phone') }}">
                            @if ($errors->has('phone'))
                                <span class="help-block text-danger">{{ $errors->first('phone') }}</span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('country') ? 'has-error' : '' }}">
                            <label class="text-white">Country</label>
                            <select name="country" ng-model="countryCode" ng-change="getTimezones()"
                                ng-init="countryCode='{{ old("country") }}';getTimezones()" class="form-control">
                                <option value="">None</option>
                                @foreach(App\Lib\Support\Country::$countries as $code => $country)
                                    <option value="{{$code}}" @if(old('country') === $code) selected @endif>{{$country}}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('country'))
                                <span class="help-block text-danger">{{ $errors->first('country') }}</span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('timezone') ? 'has-error' : '' }}">
                            <label class="text-white">Timezone</label>
                            <select name="timezone" ng-disabled="!countryCode" class="form-control">
                                <option ng-repeat="timezone in timezones">[[timezone]]</option>
                            </select>
                            @if ($errors->has('timezone'))
                                <span class="help-block text-danger">{{ $errors->first('timezone') }}</span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('currency') ? 'has-error' : '' }}">
                            <label class="text-white">Currency</label>
                            <input type="text" class="form-control" name="currency" value="{{ old('currency') }}">
                            @if ($errors->has('currency'))
                                <span class="help-block text-danger">{{ $errors->first('currency') }}</span>
                            @endif
                        </div>

                        {{-- Admin account panel --}}
                        <div class="box box-default">
                            <div class="box-body">
                                <div class="alert alert-info">
                                    <h4><i class="icon fa fa-info"></i> Important!</h4>
                                    An admin account is created when registering a clinic. Please fill in the preferred
                                    admin account username and password.
                                </div>

                                <div class="form-group {{ $errors->has('adminName') ? 'has-error' : '' }}">
                                    <label class="text-white">Admin's Name</label>
                                    <input type="text" class="form-control" name="adminName"
                                        value="{{ old('adminName') }}">
                                    @if ($errors->has('adminName'))
                                        <span class="help-block text-danger">{{ $errors->first('adminName') }}</span>
                                    @endif
                                </div>

                                <div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
                                    <label class="text-white">Admin Username</label>
                                    <input type="text" class="form-control" name="username"
                                        value="{{ old('username') }}">
                                    @if ($errors->has('username'))
                                        <span class="help-block text-danger">{{ $errors->first('username') }}</span>
                                    @endif
                                </div>

                                <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                                    <label class="text-white">Password</label>
                                    <input type="password" class="form-control" name="password"
                                        value="{{ old('password') }}">
                                    @if ($errors->has('password'))
                                        <span class="help-block text-danger">{{ $errors->first('password') }}</span>
                                    @endif
                                </div>

                                <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                                    <label class="text-white">Password Confirmation</label>
                                    <input type="password" class="form-control" name="password_confirmation"
                                        value="{{ old('password_confirmation') }}">
                                    @if ($errors->has('password_confirmation'))
                                        <span
                                            class="help-block text-danger">{{ $errors->first('password_confirmation') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('terms') ? 'has-error' : '' }}">
                            <div class="checkbox icheck">
                                <label class="text-white">
                                    <input type="checkbox" name="terms" id="checkbox"> I hereby agree to Healthy Life
                                    Clinic
                                    EMR's
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#privacyPolicyModal"
                                        class="text-white">Privacy Policy</a> and
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal"
                                        class="text-white">Terms
                                        & Conditions</a>
                                </label>
                            </div>
                            @if ($errors->has('terms'))
                                <span class="help-block text-danger">{{ $errors->first('terms') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-lg btn-flat">
                                <i class="fa fa-btn fa-check"></i> Register
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@include('auth.modals.privacyPolicy')
@include('auth.modals.terms')

{{--AngularJs Scripts--}}
<script src="{{asset('plugins/angularjs/angular.min.js')}}"></script>
<script src="{{asset('js/services.js')}}"></script>
<script src="{{asset('js/ClinicRegistrationController.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
@endsection