@extends('layouts.app')

@section('title', 'Câu hỏi thường gặp - VietTinMart')

@section('content')
<!-- Breadcrumb -->
<div class="breadcrumb-area">
    <div class="container">
        <div class="breadcrumb-content">
            <ul class="breadcrumb-list">
                <li><a href="{{ route('home') }}">Trang chủ</a></li>
                <li class="active">Câu hỏi thường gặp</li>
            </ul>
        </div>
    </div>
</div>

<!-- FAQ Section -->
<section class="faq-area section-padding-tb">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="section-title text-center mb-5">
                    <h2>Câu hỏi thường gặp</h2>
                    <p>Tìm câu trả lời cho những thắc mắc phổ biến nhất của khách hàng</p>
                </div>

                <div class="accordion" id="faqAccordion">
                    @php
                    $faqs = [
                        ['q' => 'VietTinMart giao hàng đến những khu vực nào?', 'a' => 'Hiện tại chúng tôi giao hàng toàn quốc. Với các đơn hàng tại TP.HCM và Hà Nội, chúng tôi hỗ trợ giao trong ngày cho đơn đặt trước 12h trưa.'],
                        ['q' => 'Phí giao hàng được tính như thế nào?', 'a' => 'Miễn phí giao hàng cho đơn từ 500.000đ. Đơn dưới 500.000đ phí giao hàng từ 20.000đ - 40.000đ tùy khu vực.'],
                        ['q' => 'Tôi có thể đổi trả hàng không?', 'a' => 'Chúng tôi chấp nhận đổi trả trong vòng 7 ngày kể từ ngày nhận hàng nếu sản phẩm bị lỗi, hư hỏng hoặc không đúng mô tả. Liên hệ hotline để được hỗ trợ.'],
                        ['q' => 'Sản phẩm hữu cơ có chứng nhận không?', 'a' => 'Tất cả sản phẩm hữu cơ của chúng tôi đều có chứng nhận từ các tổ chức uy tín như USDA Organic, EU Organic hoặc VietGAP. Thông tin chứng nhận được hiển thị trên trang sản phẩm.'],
                        ['q' => 'Tôi có thể thanh toán bằng những hình thức nào?', 'a' => 'Chúng tôi chấp nhận: Tiền mặt khi nhận hàng (COD), Chuyển khoản ngân hàng, Ví điện tử (MoMo, ZaloPay, VNPay), Thẻ tín dụng/ghi nợ.'],
                        ['q' => 'Làm thế nào để theo dõi đơn hàng?', 'a' => 'Sau khi đặt hàng thành công, bạn sẽ nhận được email xác nhận kèm mã đơn hàng. Bạn có thể theo dõi trạng thái đơn hàng tại trang "Theo dõi đơn hàng" hoặc trong tài khoản của bạn.'],
                        ['q' => 'Sản phẩm có được bảo quản đúng cách không?', 'a' => 'Chúng tôi sử dụng hệ thống kho lạnh và xe tải lạnh để đảm bảo thực phẩm tươi sạch trong suốt quá trình vận chuyển. Đặc biệt với thịt, hải sản và sữa.'],
                    ];
                    @endphp

                    @foreach($faqs as $i => $faq)
                    <div class="accordion-item mb-3 border rounded">
                        <h2 class="accordion-header">
                            <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }} fw-semibold"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#faq{{ $i }}"
                                aria-expanded="{{ $i === 0 ? 'true' : 'false' }}">
                                {{ $faq['q'] }}
                            </button>
                        </h2>
                        <div id="faq{{ $i }}" class="accordion-collapse collapse {{ $i === 0 ? 'show' : '' }}" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted">
                                {{ $faq['a'] }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="text-center mt-5">
                    <p class="text-muted">Không tìm thấy câu trả lời bạn cần?</p>
                    <a href="{{ route('contact.index') }}" class="btn btn-primary">Liên hệ với chúng tôi</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
