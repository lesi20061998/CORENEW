@extends('layouts.app')

@section('title', 'Contact Us - Ekomart-Grocery-Store')

@section('content')
<!-- rts contact main wrapper -->
<div class="rts-contact-main-wrapper-banner bg_image" style="background-image: url('{{ asset('theme/images/contact/01.jpg') }}')">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="contact-banner-content">
                    <h1 class="title">Ask Us Question</h1>
                    <p class="disc">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque pretium mollis ex.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- rts contact main wrapper end -->

<div class="rts-map-contact-area rts-section-gap2">
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="contact-left-area-main-wrapper">
                    <h2 class="title">You can ask us questions !</h2>
                    <p class="disc">Contact us for all your questions and opinions, or you can solve your problems in a shorter time with our contact offices.</p>
                    
                    <div class="location-single-card">
                        <div class="icon"><i class="fa-light fa-location-dot"></i></div>
                        <div class="information">
                            <h3 class="title">Main Office</h3>
                            <p>259 Daniel Road, FKT 2589 Berlin, Germany.</p>
                            <a href="#" class="number">+856 (76) 259 6328</a>
                            <a href="#" class="email">info@example.com</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d14602.288851207937!2d90.47855065!3d23.798243149999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sbd!4v1716725338558!5m2!1sen!2sbd" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </div>
</div>

<!-- rts contact-form area start -->
<div class="rts-contact-form-area rts-section-gapBottom">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="bg_light-1 contact-form-wrapper-bg">
                    <div class="row">
                        <div class="col-lg-7 pr--30">
                            <div class="contact-form-wrapper-1">
                                <h3 class="title mb--50">Fill Up The Form If You Have Any Question</h3>
                                <form action="#" class="contact-form-1">
                                    <div class="contact-form-wrapper--half-area">
                                        <div class="single"><input type="text" placeholder="name*" required></div>
                                        <div class="single"><input type="email" placeholder="Email*" required></div>
                                    </div>
                                    <textarea name="message" placeholder="Write Message Here" required></textarea>
                                    <button class="rts-btn btn-primary mt--20">Send Message</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-5 mt_md--30">
                            <div class="thumbnail-area">
                                <img src="{{ asset('theme/images/contact/02.jpg') }}" alt="contact">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- rts contact-form area end -->
@endsection
