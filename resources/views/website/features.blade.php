@extends("layouts.website.layout")

@section("title", 'Healthy Life Clinic EMR | Features')

@section("content")


<section id="home" class="s-home target-section" data-parallax="scroll"
    data-image-src="{{asset('FrontTheme/images/hero-bg2.jpeg')}}" data-natural-width=3000 data-natural-height=2000
    data-position-y=center>

    <div class="overlay"></div>
    <div class="shadow-overlay"></div>

    <div class="home-content">

        <div class="row home-content__main">

            <h1>What does Healthy Life Clinic EMR Systems offer?</h1>

            <h3>
            Healthy Life Clinic EMR provides simple and easy to use interfaces to handle all the
                    day-to-day
                    tasks of small scale clinics including patient management and inventory management.
            </h3>

            <div class="home-content__buttons">
                <a href="{{route('registerClinic')}}" class=" btn btn--stroke">
                    Register now
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
<section id='services' class="s-services">

    <div class="row section-header has-bottom-sep" data-aos="fade-up">
        <div class="col-full">
            <h3 class="subhead"></h3>
            <h1 class="display-2">Features in detail ...
            </h1>
        </div>
    </div> <!-- end section-header -->

    <div class="row services-list block-1-2 block-tab-full">

        <div class="col-block service-item" data-aos="fade-up">
            <div class="service-icon">
                <i class="icon-invoice"></i>
            </div>
            <div class="service-text">
                <h3 class="h2">Patient Record Management</h3>
                <p>Effortlessly manage all patient records, including prescriptions and medical history. Our system provides seamless access to patient information anytime and from anywhere, allowing you to update records quickly and efficiently to ensure the best care.
                </p>
            </div>
        </div>

        <div class="col-block service-item" data-aos="fade-up">
            <div class="service-icon">
                <i class="icon-syringe"></i>
            </div>
            <div class="service-text">
                <h3 class="h2">Drug Inventory</h3>
                <p>Efficiently manage your entire drug inventory and track stock levels with ease. Our system ensures you stay informed with real-time notifications when stocks are running low, so you can take timely action to reorder and maintain optimal inventory levels.
                </p>
            </div>
        </div>

        <div class="col-block service-item" data-aos="fade-up">
            <div class="service-icon">
                <i class="icon-users"></i>
            </div>
            <div class="service-text">
                <h3 class="h2">Queue Management</h3>
                <p>Efficiently manage patient queues by issuing numbers and tracking their progress. Our system allows you to update the queue in real-time as patients are seen and complete their visits, ensuring smooth and organized clinic operations.
                </p>
            </div>
        </div>

        <div class="col-block service-item" data-aos="fade-up">
            <div class="service-icon">
                <i class="icon-network"></i>
            </div>
            <div class="service-text">
                <h3 class="h2">Hierarchy</h3>
                <p>With three levels of access—Doctor, Nurse, and System Administrator— <strong>Healthy Life Clinic EMR Systems</strong> ensures that confidential information remains secure. Each user role has tailored permissions, so you can trust that sensitive data is protected and only accessible to authorized individuals.
                </p>
            </div>
        </div>

        <div class="col-block service-item" data-aos="fade-up">
            <div class="service-icon">
                <i class="icon-padlock"></i>
            </div>
            <div class="service-text">
                <h3 class="h2">Security &amp; Portability</h3>
                <p>We leverage cutting-edge technologies to safeguard your data while providing the flexibility to access it securely from anywhere. Enjoy peace of mind with top-tier security and effortless access, wherever you are.
                </p>
            </div>
        </div>
        <div class="col-block service-item" data-aos="fade-up">
            <div class="service-icon"><i class="icon-printer"></i></div>
            <div class="service-text">
                <h3 class="h2">Issue, Repeat &amp; Print Prescriptions</h3>
                <p>Effortlessly issue and print prescriptions directly from the system with a single click. Additionally, our platform allows you to quickly repeat prescriptions on the fly, streamlining your workflow and ensuring a seamless, efficient process for both you and your patients.
                </p>
            </div>
        </div>

    </div> <!-- end services-list -->

</section> <!-- end s-services -->

@endsection