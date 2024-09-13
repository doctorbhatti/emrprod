@extends("layouts.website.layout")

@section("title", 'Healthy Life Clinic EMR| Contact Us')

@section("content")
<section id="home" class="s-home target-section" data-parallax="scroll"
    data-image-src="{{asset('FrontTheme/images/hero-bg2.jpeg')}}" data-natural-width=3000 data-natural-height=2000
    data-position-y=center>

    <div class="overlay"></div>
    <div class="shadow-overlay"></div>

    <div class="home-content">

        <div class="row home-content__main">

            <h3>Healthy Life Clinic EMR Systems</h3>

            <h1>
                Contact Us
            </h1>

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
<!-- contact
    ================================================== -->
<section id="contact" class="s-contact">

    <div class="overlay"></div>
    <div class="contact__line"></div>

    <div class="row section-header" data-aos="fade-up">
        <div class="col-full">
            <h3 class="subhead">We value your opinion and suggestions for further improvements! If you need any
                clarification,send us a message with your contact details requesting a free call below. We will
                contact you.</h3>
            <h1 class="display-2 display-2--light">Contact Us</h1>
        </div>
    </div>

    <div class="row contact-content" data-aos="fade-up">

        <div class="contact-primary">

            <h3 class="h6">Send Us A Message</h3>

            <form class="contactForm" action="{{route('contactUs')}}" method="post" id="form">
                {{csrf_field()}}
                @if ($errors->any())
                    <div class="alert-box alert-box--error hideit">
                        <p>Please fill all the fields correctly.</p>
                        <i class="fa fa-times alert-box__close" aria-hidden="true"></i>
                    </div><!-- /error -->

                @endif

                @if(session()->has('success'))
                    <div class="alert-box alert-box--success hideit">
                        <p>{{session('success')}}</p>
                        <i class="fa fa-times alert-box__close" aria-hidden="true"></i>
                    </div><!-- /success -->

                @endif

                <div class="form-field">

                    <input type="text" class="full-width" id="name" name="name" placeholder="Your Name ..."
                        value="{{old('name')}}" required>
                </div> <!-- end form-group -->
                <div class="form-field">

                    <input type="tel" class="full-width" id="contact" name="contact" placeholder="With country code ..."
                        value="{{old('contact')}}" required="">
                </div> <!-- end form-group -->
                <div class="form-field">

                    <input type="email" class="full-width" name="email" id="email" placeholder="Your Email Address"
                        value="{{old('email')}}" required="">
                </div> <!-- end form-group -->
                <div class="form-field">

                    <textarea class="full-width" name="message" id="message" rows="5"
                        placeholder="Enter your message here..">{{old('message')}}</textarea>
                </div> <!-- end form-group -->

                <div class="form-field">
                    <button type="submit" class="full-width btn--primary">Submit</button>
                    <div class="submit-loader">
                        <div class="text-loader">Sending...</div>
                        <div class="s-loader">
                            <div class="bounce1"></div>
                            <div class="bounce2"></div>
                            <div class="bounce3"></div>
                        </div>
                    </div>
                </div>
                <!-- end text-center -->
            </form>

            <!-- contact-warning -->
            <div class="message-warning">
                Something went wrong. Please try again.
            </div>

            <!-- contact-success -->
            <div class="message-success">
                Your message was sent, thank you!<br>
            </div>

        </div> <!-- end contact-primary -->

        <div class="contact-secondary">
            <div class="contact-info">

                <h3 class="h6 hide-on-fullwidth">Contact Info</h3>

                <div class="cinfo">
                    <h5>Where to Find Us</h5>
                    <p>
                        Shop # 2, Al Hafeez Garden <br>
                        Phase 1, Ghora Chowk, Manawan,<br>
                        Lahore, Pakistan.
                    </p>
                </div>

                <div class="cinfo">
                    <h5>Email Us At</h5>
                    <p>
                        <a href="mailto:dochassan12@outlook.com">dochassan12@outlook.com</a>
                    </p>
                </div>

                <div class="cinfo">
                    <h5>Call Us At</h5>
                    <p>
                        Phone: <a href="tel:00923276798673">(+92) 327 6798673</a>
                    </p>
                </div>

                <!-- <ul class="contact-social">
                        <li>
                            <a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-behance" aria-hidden="true"></i></a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-dribbble" aria-hidden="true"></i></a>
                        </li>
                    </ul> end contact-social -->

            </div> <!-- end contact-info -->
        </div> <!-- end contact-secondary -->

    </div> <!-- end contact-content -->

</section> <!-- end s-contact -->

@endsection