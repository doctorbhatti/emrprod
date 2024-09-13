@extends("layouts.website.layout")

@section("title", 'Healthy Life Clinic EMR | About Us')

@section("content")
<section id="home" class="s-home target-section" data-parallax="scroll"
    data-image-src="{{asset('FrontTheme/images/hero-bg2.jpeg')}}" data-natural-width=3000 data-natural-height=2000
    data-position-y=center>

    <div class="overlay"></div>
    <div class="shadow-overlay"></div>

    <div class="home-content">

        <div class="row home-content__main">

            <h1>Who Am I?</h1>

            <h3>
                I am Dr. Muhammad Hassan Ashfaq,
                <br>full time Medico, hobbyist developer.
            </h3>

            <div class="home-content__buttons">
                <a href="https://doctorbhatti.github.io/" class=" btn btn--stroke">
                    My Works
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
</section>
<!-- about
    ================================================== -->
<section id='about' class="s-about">

    <div class="row section-header has-bottom-sep" data-aos="fade-up">
        <div class="col-full">
            <h3 class="subhead subhead--dark">More about me</h3>
            <h1 class="display-1 display-1--light">Why this EMR?</h1>
        </div>
    </div> <!-- end section-header -->

    <div class="row about-desc" data-aos="fade-up">
        <div class="col-full">
            <p>
                At Healthy Life Clinic EMR Systems, our journey began with a personal challenge. As a practicing
                physician, I found it increasingly difficult to manage patient records and clinic operations manually.
                The complexity of tracking patient information, prescriptions, and medical history using traditional
                methods was time-consuming and prone to errors.
                <br>
                Driven by the need for a more efficient solution, I set out to develop an EMR system that would address
                these challenges. Over time, I meticulously designed and enhanced the application, incorporating
                features that streamline clinic management and improve patient care. Each addition to the system was
                crafted with the real-world needs of healthcare professionals in mind.
                <br>
                Today, Healthy Life Clinic EMR Systems is ready for commercial use, offering a comprehensive solution
                that simplifies clinic operations, enhances data security, and provides flexible access to patient
                information. Our goal is to empower healthcare providers with an intuitive, reliable tool that makes
                managing patient records and clinic workflows effortless.
            </p>
        </div>
    </div> <!-- end about-desc -->

    <div class="about__line"></div>

</section> <!-- end s-about -->
@endsection