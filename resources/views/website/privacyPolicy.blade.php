@extends("layouts.website.layout")

@section("title", 'Healthy Life Clinic EMR | Privacy Policy')

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
                Privacy Policy
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
<section id='services' class="s-services">
    <div class="row">
        <div class="col-twelve tab-full">
            <h3>Privacy Policy</h3>
            <p class="drop-cap">
                This Privacy Policy governs your use of the Healthy Life Clinic EMR Systems ("Application"). Our
                Application
                is designed to manage patient records through a centralized cloud system.

            <h2>Information Collection and Use</h2>
            <h3>User-Provided Information</h3>
            When you register with the Application, we collect the information you provide, which includes your name,
            email
            address, age, username, password, and other registration details. Additionally, we gather
            transaction-related
            information, such as purchase details, responses to offers, and app usage. We may also collect information
            when
            you contact us for support or enter data into our system, including patient information. This data may be
            used
            to communicate important updates, notices, and promotional offers.

            <h3>Automatically Collected Information</h3>
            We automatically collect certain details about your device and usage, including your mobile device type,
            unique
            device ID, IP address, operating system, and browser type.

            <h3>Disclosure of Information</h3>
            We may share your information with third parties in the following circumstances:

            As required by law, such as in response to a subpoena or similar legal process.
            When necessary to protect our rights, your safety, or the safety of others, or to respond to a government
            request.
            With trusted service providers who assist us in operating the Application and have agreed to follow our
            privacy
            guidelines.

            <h3>Data Retention and Management</h3>
            We retain user-provided data as long as you use the Application and for a reasonable period thereafter.
            Automatically collected information is retained for up to 24 months and may be stored in aggregate form.
            Note
            that some user-provided data is essential for the Application’s functionality.

            <h3>Children's Privacy</h3>
            We do not knowingly collect information from children under 13. If a parent or guardian discovers that their
            child has provided us with information without consent, please contact us at <a
                href="mailto:healthylifeclinicemr@gmail.com">healthylifeclinicemr@gmail.com</a>, and we will delete the information
            promptly.

            <h3>Security</h3>
            We are committed to protecting your information with physical, electronic, and procedural safeguards. Access
            to
            data is limited to authorized personnel who require it for Application operations. However, no system is
            entirely secure, and we cannot guarantee absolute protection against all breaches.

            <h3>Policy Updates</h3>
            We may update this Privacy Policy periodically. Any changes will be posted here, and we will notify you via
            email or text message. Your continued use of the Application signifies your acceptance of any changes.
            Please
            review this policy regularly.

            <h3>Your Consent</h3>
            By using the Application, you consent to our processing of your information as described in this Privacy
            Policy.
            "Processing" includes collecting, storing, using, and disclosing information. If you are located outside the
            United States, your information will be processed and stored according to U.S. privacy standards.
            </p>
        </div>
    </div>
</section>
@endsection