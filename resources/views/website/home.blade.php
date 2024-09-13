@extends("layouts.website.layout")

@section("content")

<!-- home
    ================================================== -->
<section id="home" class="s-home target-section" data-parallax="scroll"
    data-image-src="{{asset('FrontTheme/images/hero-bg2.jpeg')}}" data-natural-width=3000 data-natural-height=2000
    data-position-y=center>

    <div class="overlay"></div>
    <div class="shadow-overlay"></div>

    <div class="home-content">

        <div class="row home-content__main">

            <h3>Welcome to</h3>

            <h1>
                Healthy Life Clinic EMR Sytems
            </h1>

            <h3>Secure | Simple | Practical</h3>

            <div class="home-content__buttons">
                <a href="{{route('registerClinic')}}" class=" btn btn--stroke">
                    Register now
                </a>
                <a href="{{url('login')}}" class=" btn btn--stroke">
                    Login
                </a>
            </div>

        </div>

        <div class="home-content__scroll">
            <a href="#about" class="scroll-link smoothscroll">
                <span>Scroll Down</span>
            </a>
        </div>

        <div class="home-content__line"></div>

    </div> <!-- end home-content -->


    <!-- <ul class="home-social">
        <li>
            <a href="#0"><i class="fa fa-facebook" aria-hidden="true"></i><span>Facebook</span></a>
        </li>
        <li>
            <a href="#0"><i class="fa fa-twitter" aria-hidden="true"></i><span>Twiiter</span></a>
        </li>
        <li>
            <a href="#0"><i class="fa fa-instagram" aria-hidden="true"></i><span>Instagram</span></a>
        </li>
        <li>
            <a href="#0"><i class="fa fa-behance" aria-hidden="true"></i><span>Behance</span></a>
        </li>
        <li>
            <a href="#0"><i class="fa fa-dribbble" aria-hidden="true"></i><span>Dribbble</span></a>
        </li>
    </ul> -->
    <!-- end home-social -->

</section> <!-- end s-home -->
<!-- about
    ================================================== -->
<section id='about' class="s-about">

    <div class="row section-header has-bottom-sep" data-aos="fade-up">
        <div class="col-full">
            <h3 class="subhead subhead--dark">Managing Your Patients</h3>
            <h1 class="display-1 display-1--light">Is Now Very Easy!</h1>
        </div>
    </div> <!-- end section-header -->

    <div class="row about-desc" data-aos="fade-up">
        <div class="col-full">
            <p>
                Welcome to <strong>Healthy Life Clinic EMR Systems</strong>, a cutting-edge solution designed to
                streamline healthcare management for clinics of all sizes. Our EMR platform simplifies patient
                management, drug inventory, queue handling, and prescription management, ensuring efficient, accurate,
                and secure medical workflows. With <strong>Healthy Life Clinic EMR</strong>, healthcare providers can
                focus on delivering quality care while our system takes care of the administrative complexities.
                Experience a smarter, more organized clinic with seamless access to patient records, real-time updates,
                and enhanced operational control.
            </p>
        </div>
    </div> <!-- end about-desc -->
    <div class="about__line"></div>

</section> <!-- end s-about -->

<!-- services
    ================================================== -->
<section id='services' class="s-services">

    <div class="row section-header has-bottom-sep" data-aos="fade-up">
        <div class="col-full">
            <h3 class="subhead">Features</h3>
            <h1 class="display-2">Experience the standout features of <strong>Healthy Life Clinic EMR Systems</strong>.
            </h1>
        </div>
    </div> <!-- end section-header -->

    <div class="row services-list block-1-2 block-tab-full">

        <div class="col-block service-item" data-aos="fade-up">
            <div class="service-icon">
                <i class="icon-paint-brush"></i>
            </div>
            <div class="service-text">
                <h3 class="h2">Doctor-Designed</h3>
                <p><strong>Healthy Life Clinic EMR Systems</strong> was developed by <strong><a
                            href="https://doctorbhatti.github.io">Dr. Hassan Ashfaq</a></strong>, ensuring a practical
                    and user-friendly design that addresses the real needs of healthcare professionals, thanks to his
                    firsthand medical experience.
                </p>
            </div>
        </div>

        <div class="col-block service-item" data-aos="fade-up">
            <div class="service-icon">
                <i class="icon-padlock"></i>
            </div>
            <div class="service-text">
                <h3 class="h2">Security</h3>
                <p>All the records are protected by SSL end-to-end encryption so they are only accessed by only you
                    and the people who you grant access to.
                </p>
            </div>
        </div>

        <div class="col-block service-item" data-aos="fade-up">
            <div class="service-icon">
                <i class="icon-target"></i>
            </div>
            <div class="service-text">
                <h3 class="h2">Easy to Set-Up</h3>
                <p>No installing, updating or maintaining is required by the user. We will do all that for you.
                    Once your account is approved you can immediately start using the system.
                </p>
            </div>
        </div>

        <div class="col-block service-item" data-aos="fade-up">
            <div class="service-icon">
                <i class="icon-earth"></i>
            </div>
            <div class="service-text">
                <h3 class="h2">Easy Access</h3>
                <p>The entire system is running on cloud technology, so you can securely access your
                    records from anywhere, anytime. All you need is a computer, tablet or a smartphone and an
                    internet connection.
                </p>
            </div>
        </div>

        <div class="col-block service-item" data-aos="fade-up">
            <div class="service-icon">
                <i class="icon-fire"></i>
            </div>
            <div class="service-text">
                <h3 class="h2">Revamped Codebase</h3>
                <p><strong>Healthy Life Clinic EMR Systems</strong> is powered by <strong>Laravel 11</strong> on the
                    backend and <strong>AngularJS</strong> on the frontend, with <strong>Bootstrap 5</strong> for a
                    sleek, responsive design. This combination ensures fast, secure data management and a user-friendly
                    experience across all devices.
                </p>
            </div>
        </div>
        <div class="col-block service-item" data-aos="fade-up">
            <div class="service-icon"><i class="icon-bank-note"></i></div>
            <div class="service-text">
                <h3 class="h2">Affordable</h3>
                <p><strong>Healthy Life Clinic EMR Systems</strong> provides cost-effective pricing plans, offering
                    high-quality features at affordable rates to fit any clinic's budget.
                </p>
            </div>
        </div>

    </div> <!-- end services-list -->

</section> <!-- end s-services -->
@endsection