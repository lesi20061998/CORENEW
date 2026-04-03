<!-- rts about us area start -->
<div class="rts-about-area rts-section-gap">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="about-image-area pr--50 pr_md--0 pr_sm--0">
                    <img src="{{ asset('assets/images/about/01.jpg') }}" alt="about">
                    <div class="experience-area">
                        <div class="inner">
                            <h2 class="title">25+</h2>
                            <p>Years of Experience</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt_md--50 mt_sm--50">
                <div class="about-content-area">
                    <span class="sub-title">Về Chúng Tôi</span>
                    <h2 class="title mb--30">{{ $page->title }}</h2>
                    <div class="content entry-content">
                        {!! $page->content !!}
                    </div>
                    <div class="row g-4 mt--20">
                        <div class="col-lg-6 col-md-6">
                            <div class="single-about-feature">
                                <div class="icon">
                                    <img src="{{ asset('assets/images/about/icon/01.svg') }}" alt="icon">
                                </div>
                                <div class="content">
                                    <h4 class="title">Sản Phẩm Tươi Sạch</h4>
                                    <p>Cam kết chất lượng mỗi ngày.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="single-about-feature">
                                <div class="icon">
                                    <img src="{{ asset('assets/images/about/icon/02.svg') }}" alt="icon">
                                </div>
                                <div class="content">
                                    <h4 class="title">Hỗ Trợ 24/7</h4>
                                    <p>Luôn sẵn sàng giúp đỡ bạn.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- rts about us area end -->
