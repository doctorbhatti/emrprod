<!DOCTYPE html>
<!--[if lt IE 9 ]><html class="no-js oldie" lang="en"> <![endif]-->
<!--[if IE 9 ]><html class="no-js oldie ie9" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html class="no-js" lang="en">
<!--<![endif]-->

<head>

    <!--- basic page needs
    ================================================== -->
    <meta charset="utf-8">
    <!-- ========== PAGE TITLE ========== -->
    <title>@yield("title", 'Healthy Life Clinic | EMR')</title>
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- mobile specific metas
    ================================================== -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS
    ================================================== -->
    <link rel="stylesheet" href="{{asset('FrontTheme/styles/base.css')}}">
    <link rel="stylesheet" href="{{asset('FrontTheme/styles/vendor.css')}}">
    <link rel="stylesheet" href="{{asset('FrontTheme/styles/main.css')}}">

    <!-- script
    ================================================== -->
    <script src="{{asset('FrontTheme/scripts/modernizr.js')}}"></script>
    <script src="{{asset('FrontTheme/scripts/pace.min.js')}}"></script>

    <!-- favicons
    ================================================== -->
    <link rel="shortcut" href="{{asset('favicon.ico')}}" />
    <link rel="apple-touch-icon" href="{{asset('FrontTheme/images/apple-touch-icon.png')}}">


</head>


<!-- ========== BODY ==========
.light-header: for light colored header
.dark-header: for dark colored header
==========  ========== -->

<body id="top">

    <!-- header
    ================================================== -->
    <header class="s-header">

        <div class="header-logo">
            <a class="site-logo" href="{{url('/')}}">
                <img src="{{asset('logo.png')}}" alt="Healthy Life Clinic | EMR">
            </a>
        </div>

        <nav class="header-nav">

            <a href="#0" class="header-nav__close" title="close"><span>Close</span></a>

            <div class="header-nav__content">
                <h3>Navigation</h3>

                <ul class="header-nav__list">
                    <li class="current"><a href="{{url('/')}}" title="home">Home</a></li>
                    <li><a href="{{url('/web/aboutUs')}}" title="aboutUs">About Us</a></li>
                    <li><a href="{{url('/web/features')}}" title="features">Features</a></li>
                    <li><a href="{{url('/web/privacyPolicy')}}" title="privacyPolicy">Privacy
                            Policy</a></li>
                    <li><a href="{{url('web/contactUs')}}" title="contactUs">Contact Us</a></li>
                    <li><a href="{{url('login')}}" title="login">Login</a></li>
                </ul>

                <h3 style="margin-bottom: 2rem !important; margin-top: 0.5rem !important">Meet the Developer</h3>
                <p><strong><a href="https://doctorbhatti.github.io/">Dr. Hassan Ashfaq</a></strong> created Healthy Life Clinic EMR Systems with firsthand medical insight, ensuring the platform is tailored to healthcare professionals' needs.</p>

                <!-- <ul class="header-nav__social">
                    <li>
                        <a href="#"><i class="fa fa-facebook"></i></a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-twitter"></i></a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-instagram"></i></a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-behance"></i></a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-dribbble"></i></a>
                    </li>
                </ul> -->

            </div> <!-- end header-nav__content -->

        </nav> <!-- end header-nav -->

        <a class="header-menu-toggle" href="#0">
            <span class="header-menu-text">Menu</span>
            <span class="header-menu-icon"></span>
        </a>

    </header> <!-- end s-header -->

    @yield("content")
    <!-- footer
    ================================================== -->
    <footer>

        <div class="row footer-main">

            <div class="col-six tab-full left footer-desc">

                <div class="footer-logo">
                </div>
                <strong>Healthy Life Clinic EMR Systems</strong> is dedicated to revolutionizing healthcare management
                with its innovative and user-friendly platform. We provide comprehensive solutions designed to
                streamline clinic operations and enhance patient care. Our commitment to security, efficiency, and ease
                of use ensures that healthcare professionals can focus on what matters most: delivering exceptional
                care. For more information or support, please contact us—we’re here to help you every step of the way.

            </div>

            <div class="col-six tab-full right footer-desc">

                <h4>Workflow</h4>
                <p><strong>Healthy Life Clinic EMR Systems</strong> simplifies clinic management through a seamless interface. The application integrates patient records, drug inventory, and appointment scheduling into a unified system. Healthcare professionals can easily access and update patient information, manage prescriptions, and track inventory in real-time. With intuitive navigation and automated features, the platform enhances efficiency and accuracy, allowing clinics to deliver superior patient care while streamlining administrative tasks.</p>

            </div>

        </div> <!-- end footer-main -->

        <div class="row footer-bottom">

            <div class="col-twelve">
                <div class="copyright">
                    <span>© 2024 Copyright Healthy Life Clinic EMR Systems | All rights reserved.</span>
                    <span>Empowering healthcare professionals with intuitive, secure, and
                        efficient solutions.</span>
                </div>

                <div class="go-top">
                    <a class="smoothscroll" title="Back to Top" href="#top"><i class="icon-arrow-up"
                            aria-hidden="true"></i></a>
                </div>
            </div>

        </div> <!-- end footer-bottom -->

    </footer> <!-- end footer -->

    <script src="{{asset('FrontTheme/scripts/jquery-3.2.1.min.js')}}"></script>
    <script src="{{asset('FrontTheme/scripts/plugins.js')}}"></script>
    <script src="{{asset('FrontTheme/scripts/main.js')}}"></script>


    {{-- Google Analytics --}}
    @include('analytics.googleAnalytics')

</body>

</html>