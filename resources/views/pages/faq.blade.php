@extends('layouts.app')

@section('title', "FAQ's - Ekomart-Grocery-Store")

@section('content')
<!-- rts contact main wrapper -->
<div class="rts-contact-main-wrapper-banner bg_image" style="background-image: url('{{ asset('theme/images/contact/01.jpg') }}')">
    <div class="container">
        <div class="row text-center">
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

<!-- rts faq-area start -->
<div class="rts-faq-area-start rts-section-gap">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-4">
                <div class="faq-content-left-main-wrapper">
                    <h3 class="title">Frequently Asked Questions</h3>
                    <p class="disc">Turpis nullam sollicitudin habitasse orci mattis nostra ullamcorper vel fringilla rutrum ac commodo platea.</p>
                    <form action="#" class="contact-form-1">
                        <div class="single"><input type="text" placeholder="name*" required></div>
                        <div class="single"><input type="email" placeholder="Email*" required></div>
                        <textarea name="message" placeholder="Write Message Here" required></textarea>
                        <button class="rts-btn btn-primary mt--20">Submit Now</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-7 offset-lg-1">
                <div class="accordion-main-area-wrapper-style-1">
                    <div class="accordion" id="accordionExample">
                        @php 
                            $faqs = [
                                "What are your store hours?",
                                "What is the capital of France?",
                                "Who wrote 'Romeo and Juliet'?",
                                "What is the largest planet in our solar system?",
                                "How many continents are there?"
                            ];
                        @endphp
                        @foreach($faqs as $index => $q)
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="{{ $index == 0 ? 'true' : 'false' }}">
                                    {{ $q }}
                                </button>
                            </h2>
                            <div id="collapse{{ $index }}" class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    Etiam penatibus congue tincidunt et aliquam blandit condimentum sapien erat placerat, mi habitant tempus in per nisl parturient enim vel.
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- rts faq-area end -->
@endsection
