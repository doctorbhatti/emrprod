@extends("layouts.website.layout")

@section("title", 'Healthy Life Clinic EMR | Pricing')

@section("content")

<style>
    h2,
    h3 {
        color: white !important;
    }

    h1 {
        color: #0D6CB3 !important;
    }

    .pricing-list {
        display: flex;
        justify-content: center;
        gap: 30px;
        /* Adjust spacing between the two items */
    }
    
     /* Media query for mobile devices */
    @media (max-width: 768px) {
        .pricing-list {
            flex-direction: column;
            align-items: center;
            /* Ensure items are centered when stacked */
        }

        .pricing-item {
            width: 100%;
            /* Ensure each pricing item takes up full width */
        }
    }
</style>

<section id="home" class="s-home target-section" data-parallax="scroll"
    data-image-src="{{ asset('FrontTheme/images/hero-bg2.jpeg') }}" data-natural-width=3000 data-natural-height=2000
    data-position-y=center>

    <div class="overlay"></div>
    <div class="shadow-overlay"></div>

    <div class="home-content">

        <div class="row home-content__main">

            <h1>Choose Your Plan</h1>

            <h3>
                Healthy Life Clinic EMR offers flexible pricing plans tailored to the needs of small-scale clinics.
                Pick the plan that best suits your clinic, with no hidden fees or extra costs.
            </h3>

            <div class="home-content__buttons">
                <a href="#pricing" class="btn btn--stroke smoothscroll"
                    style="display: flex; justify-content: center; align-items: center;">
                    View Pricing Plans
                </a>
            </div>

        </div>

        <div class="home-content__scroll">
            <a href="#pricing" class="scroll-link smoothscroll">
                <span>Scroll Down</span>
            </a>
        </div>

        <div class="home-content__line"></div>

    </div> <!-- end home-content -->
</section>

<section id="pricing" class="s-pricing">

    <div class="row section-header has-bottom-sep" data-aos="fade-up">
        <div class="col-full">
            <h3 class="subhead"></h3>
            <h1 class="display-2">Our Pricing Plans</h1>
            <h3 class="subhead">Affordable & Transparent</h3>
        </div>
    </div> <!-- end section-header -->

    <div class="row pricing-content">

        <div class="pricing-list">

            <div class="col-block pricing-item" data-aos="fade-up">
                <div class="pricing-icon">
                    <i class="icon-wallet"></i>
                </div>
                <div class="pricing-text">
                    <h3 class="h2">Basic Plan</h3>
                    <p class="pricing-amount">
                        <span>Free</span> / 7 Days
                    </p>
                    <ul class="pricing-features">
                        <ul>
                            <li><strong>7-day free trial</strong></li>
                            <li>Use in real-time</li>
                            <li>No cost involved</li>
                            <li>Experience all features</li>
                            <li>Purchase if satisfied</li>
                            <li>No commitment needed</li>
                            <li>No hidden fees</li>
                            <li>Seamless management solution</li>
                        </ul>


                    </ul>
                    <a href="{{ route('registerClinic') }}" class="btn btn--stroke">
                        Get Started
                    </a>
                </div>
            </div>

            <div class="col-block pricing-item" data-aos="fade-up">
                <div class="pricing-icon">
                    <i class="icon-trophy"></i>
                </div>
                <div class="pricing-text">
                    <h3 class="h2">Premium Plan</h3>
                    <p class="pricing-amount">
                        <span>Rs.7999</span> / 3 Months
                    </p>
                    <ul class="pricing-features">
                        <li>All Features Included</li>
                        <li>Priority Support</li>
                        <li>Unlimited Patients</li>
                        <li>Unlimited Users</li>
                        <li>Unlimited Drugs</li>
                        <li>Continuous Updates</li>
                    </ul>
                    <a href="{{ route('registerClinic') }}" class="btn btn--stroke">
                        Get Started
                    </a>
                </div>
            </div>

        </div> <!-- end pricing-list -->

    </div> <!-- end pricing-content -->

</section> <!-- end s-pricing -->

@endsection