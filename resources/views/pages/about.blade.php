@extends('layouts.app')

@section('title', 'About Us - Ekomart-Grocery-Store')

@section('content')
<!-- rts banner area about -->
<div class="about-banner-area-bg rts-section-gap bg_iamge" style="background-image: url('{{ asset('theme/images/about/01.jpg') }}')">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="inner-content-about-area">
                    <h1 class="title">Do You Want To Know Us?</h1>
                    <p class="disc">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque pretium mollis ex, vel interdum augue faucibus sit amet. Proin tempor purus ac suscipit sagittis. Nunc finibus euismod enim, eu finibus nunc ullamcorper et.
                    </p>
                    <a href="{{ route('contact.index') }}" class="rts-btn btn-primary">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- rts banner area about end -->

<!-- rts counter area start -->
<div class="rts-counter-area">
    <div class="container-3">
        <div class="row">
            <div class="col-lg-12">
                <div class="counter-area-main-wrapper">
                    <div class="single-counter-area">
                        <h2 class="title"><span class="counter">60</span>M+</h2>
                        <p>Happy <br> Customers</p>
                    </div>
                    <div class="single-counter-area">
                        <h2 class="title"><span class="counter">105</span>M+</h2>
                        <p>Grocery <br> Products</p>
                    </div>
                    <div class="single-counter-area">
                        <h2 class="title"><span class="counter">80</span>K+</h2>
                        <p>Active <br> Salesman</p>
                    </div>
                    <div class="single-counter-area">
                        <h2 class="title"><span class="counter">60</span>K+</h2>
                        <p>Store <br> Worldwide</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- rts counter area end -->

<!-- about area start -->
<div class="rts-about-area rts-section-gap2">
    <div class="container-3">
        <div class="row align-items-center">
            <div class="col-lg-4">
                <div class="thumbnail-left">
                    <img src="{{ asset('theme/images/about/02.jpg') }}" alt="about">
                </div>
            </div>
            <div class="col-lg-8 pl--60 pl_md--10 pt_md--30 pl_sm--10 pt_sm--30">
                <div class="about-content-area-1">
                    <h2 class="title">Your Destination for Quality Produce <br> and Pantry Essentials</h2>
                    <p class="disc">
                        Venenatis augue consequat class magnis sed purus, euismod ligula nibh congue quis vestibulum nostra, cubilia varius velit vitae rhoncus. Turpis malesuada fringilla urna dui est torquent aliquet, mi nec fermentum placerat nisi venenatis sapien, mattis nunc nullam rutrum feugiat porta. Pharetra mi nisl consequat semper quam litora aenean eros conubia molestie erat, et cursus integer rutrum sollicitudin auctor curae inceptos senectus sagittis est.
                    </p>
                    <div class="check-main-wrapper">
                        <div class="single-check-area">Elementum sociis rhoncus aptent auctor urna justo</div>
                        <div class="single-check-area">Habitasse venenatis gravida nisl, sollicitudin posuere</div>
                        <div class="single-check-area">Uisque cum convallis nostra in sapien nascetur, netus</div>
                        <div class="single-check-area">Class nunc aliquet nulla dis senectus lputate porta</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- about area end -->

<!-- meet our expart team -->
<div class="meet-our-expart-team rts-section-gap2">
    <div class="container-3">
        <div class="row text-center">
            <div class="col-lg-12">
                <div class="title-center-area-main">
                    <h2 class="title">Meet Our Expert Team</h2>
                    <p class="disc">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque pretium mollis ex.</p>
                </div>
            </div>
        </div>
        <div class="row g-5 mt--40">
            @for($i=1; $i<=4; $i++)
            <div class="col-lg-3 col-md-6">
                <div class="single-team-style-one">
                    <a href="#" class="thumbnail">
                        <img src="{{ asset('theme/images/team/0'.$i.'.jpg') }}" alt="team">
                    </a>
                    <div class="bottom-content-area text-center">
                        <div class="top">
                            <h3 class="title">Member {{ $i }}</h3>
                            <span class="designation">Design Director</span>
                        </div>
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </div>
</div>
<!-- meet our expart end -->
@endsection
