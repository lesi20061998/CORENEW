{{-- Quick View Popup (cấu trúc từ HTML gốc) --}}
<div class="product-details-popup-wrapper">
    <div class="rts-product-details-section rts-product-details-section2 product-details-popup-section">
        <div class="product-details-popup">
            <button class="product-details-close-btn"><i class="fal fa-times"></i></button>
            <div class="details-product-area">
                <div class="product-thumb-area">
                    <div class="cursor"></div>
                    <div class="thumb-wrapper one filterd-items figure">
                        <div class="product-thumb zoom" id="qv-thumb-main"
                            style="background-image: url({{ asset('theme/images/grocery/01.jpg') }})">
                            <img src="{{ asset('theme/images/grocery/01.jpg') }}" alt="product-thumb" id="qv-img-main">
                        </div>
                    </div>
                    <div class="product-thumb-filter-group" id="qv-thumb-filters"></div>
                </div>
                <div class="contents" id="qv-contents">
                    <div class="product-status">
                        <span class="product-catagory" id="qv-category"></span>
                        <div class="rating-stars-group">
                            <div class="rating-star"><i class="fas fa-star"></i></div>
                            <div class="rating-star"><i class="fas fa-star"></i></div>
                            <div class="rating-star"><i class="fas fa-star"></i></div>
                            <div class="rating-star"><i class="fas fa-star"></i></div>
                            <div class="rating-star"><i class="fas fa-star-half-alt"></i></div>
                        </div>
                    </div>
                    <h2 class="product-title" id="qv-title">Tên sản phẩm <span class="stock">Còn hàng</span></h2>
                    <span class="product-price" id="qv-price"></span>
                    <p id="qv-desc"></p>
                    <div class="product-bottom-action">
                        <div class="cart-edit">
                            <div class="quantity-edit action-item">
                                <button class="button"><i class="fal fa-minus minus"></i></button>
                                <input type="text" class="input" value="1" id="qv-qty" />
                                <button class="button plus">+<i class="fal fa-plus plus"></i></button>
                            </div>
                        </div>
                        <a href="#" class="rts-btn btn-primary radious-sm with-icon" id="qv-add-cart">
                            <div class="btn-text">Thêm vào giỏ</div>
                            <div class="arrow-icon"><i class="fa-regular fa-cart-shopping"></i></div>
                            <div class="arrow-icon"><i class="fa-regular fa-cart-shopping"></i></div>
                        </a>
                        <a href="javascript:void(0);" class="rts-btn btn-primary ml--20"><i class="fa-light fa-heart"></i></a>
                    </div>
                    <div class="product-uniques">
                        <span class="sku product-unipue"><span>SKU: </span><span id="qv-sku"></span></span>
                        <span class="catagorys product-unipue"><span>Danh mục: </span><span id="qv-cat-name"></span></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Compare Modal --}}
<div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                <p class="text-center py-4">Tính năng so sánh sản phẩm đang được phát triển.</p>
            </div>
        </div>
    </div>
</div>

{{-- Wishlist toast --}}
<div class="successfully-addedin-wishlist">
    <div class="d-flex" style="align-items:center;gap:15px;">
        <i class="fa-regular fa-check"></i>
        <p>Đã thêm vào danh sách yêu thích!</p>
    </div>
</div>
