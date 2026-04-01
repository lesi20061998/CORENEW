/* 
   =========================================
   EXCLUSIVE QUICK VIEW (QV) LOGIC 
   =========================================
*/

const qvAction = {
    init: function () {
        this.bindEvents();
    },

    bindEvents: function () {
        // Delegate Thumbnail Switching (Improved Single Image Engine)
        $(document).on('click', '.qv-thumb-item', function () {
            const imgUrl = $(this).data('img');
            const mainImg = $('#qv-main-img');
            const mainZoom = $('#qv-main-zoom');

            $(this).addClass('active').siblings().removeClass('active');

            // Subtle transition effect
            mainImg.fadeOut(100, function () {
                $(this).attr('src', imgUrl).fadeIn(200);
                mainZoom.css('background-image', 'url(' + imgUrl + ')');
            });
        });

        // Close Modal Event (Improved for qv-modal-wrapper)
        $(document).on('click', '.qv-close-btn, .qv-modal-wrapper', function (e) {
            // Only close if clicking the wrapper itself or the close button
            if (e.target === this || $(this).hasClass('qv-close-btn') || $(this).parents('.qv-close-btn').length) {
                $('#anywhere-home').removeClass('bgshow'); // Remove theme overlay
                $('#quick-view-modal-container').fadeOut(200, function () {
                    $(this).empty();
                });
                $('body').css('overflow', ''); // Restore scroll
            }
        });

        // Prevent closing when clicking inside content
        $(document).on('click', '.qv-modal-content', function (e) {
            e.stopPropagation();
        });

        // Quantity Controls (Supports Modal and Card)
        $(document).on('click', '.qv-qty-btn', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const container = $(this).closest('.quantity-edit, .qv-quantity-control');
            const input = container.find('.qv-qty-input');
            let val = parseInt(input.val());

            if ($(this).hasClass('qv-plus')) {
                val++;
            } else if (val > 1) {
                val--;
            }
            input.val(val < 10 ? '0' + val : val);
        });
    },

    zoom: function (event) {
        const zoomer = event.currentTarget;
        let offsetX, offsetY;

        // Safe offset calculation for mouse and touch
        if (event.offsetX !== undefined && event.offsetY !== undefined) {
            offsetX = event.offsetX;
            offsetY = event.offsetY;
        } else if (event.touches && event.touches[0]) {
            // Touch support fallback
            const rect = zoomer.getBoundingClientRect();
            offsetX = event.touches[0].clientX - rect.left;
            offsetY = event.touches[0].clientY - rect.top;
        } else {
            return; // Not a valid event for zooming
        }

        const x = offsetX / zoomer.offsetWidth * 100;
        const y = offsetY / zoomer.offsetHeight * 100;
        zoomer.style.backgroundPosition = x + '% ' + y + '%';
    }
};

// Main Global Action Handler
const cwAction = {
    // QUICK VIEW TRIGGER (FIXED)
    quickView: function (productId) {
        const modalContainer = $('#quick-view-modal-container');

        // Show loading if you want, but AJAX is fast
        $.ajax({
            url: '/quick-view/' + productId,
            method: 'GET',
            success: function (response) {
                // IMPORTANT: Use response.html as the controller sends a JSON object
                modalContainer.html(response.html).fadeIn(300);
                $('body').css('overflow', 'hidden'); // Lock scroll when open
            },
            error: function () {
                Swal.fire('Error!', 'Could not load product details.', 'error');
            }
        });
    },

    addWishlist: function (id, btn) {
        const $btn = $(btn);
        $.ajax({
            url: '/wishlist/add',
            method: 'POST',
            data: {
                product_id: id,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                // Update UI count
                $('.wishlist .number').text(response.count);

                const icon = $btn.find('i');
                icon.removeClass('fa-light').addClass('fa-solid').css('color', '#fff');
                $btn.css('background', '#629d23');
                Swal.fire({
                    title: 'Thành công!',
                    text: response.message,
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                });
            },
            error: function () {
                Swal.fire('Lỗi!', 'Không thể thêm vào danh sách yêu thích.', 'error');
            }
        });
    },

    addCompare: function (id, btn) {
        const $btn = $(btn);
        $.ajax({
            url: '/compare/add',
            method: 'POST',
            data: {
                product_id: id,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                // Update UI count
                $('.compare .number').text(response.count);

                const icon = $btn.find('i');
                icon.removeClass('fa-light').addClass('fa-solid').css('color', '#fff');
                $btn.css('background', '#629d23');

                if (response.count >= 2) {
                    Swal.fire({
                        title: 'So sánh ngay!',
                        text: 'Đã có ' + response.count + ' sản phẩm trong danh sách. Đang chuyển hướng...',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = '/so-sanh';
                    });
                } else {
                    Swal.fire({
                        title: 'Thành công!',
                        text: response.message,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            },
            error: function () {
                Swal.fire('Lỗi!', 'Không thể thêm vào danh sách so sánh.', 'error');
            }
        });
    }
};

// Global Cart Handler
const cart = {
    add: function (productId, btn, quantity = null) {
        const $btn = $(btn);
        const originalHtml = $btn.html();

        // Auto-detect quantity from nearby input if not explicitly provided
        if (quantity === null || quantity === undefined || typeof quantity === 'object') {
            const container = $btn.closest('.cart-counter-action, .quantity-edit, .qv-cta-group, .product-card, .qv-quantity-control');
            const qtyInput = container.find('.qv-qty-input, .input');
            quantity = qtyInput.length ? parseInt(qtyInput.val()) : 1;
        }

        $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i>');

        $.ajax({
            url: '/gio-hang/them',
            method: 'POST',
            data: {
                product_id: productId,
                qty: quantity,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                // Update cart counts in UI
                $('.cart .number, .shopping-cart-number').each(function () {
                    $(this).text(response.count);
                });

                // Show success message
                Swal.fire({
                    title: 'Thành công!',
                    text: 'Sản phẩm đã được thêm vào giỏ hàng',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                });

                cart.updateDropdown();
            },
            complete: function () {
                $btn.prop('disabled', false).html(originalHtml);
            }
        });
    },

    remove: function (rowId) {
        $.ajax({
            url: '/gio-hang/xoa',
            method: 'POST',
            data: {
                rowId: rowId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                cart.updateDropdown();
                // Update header counts
                $.get('/gio-hang/so-luong', function (data) {
                    $('.cart .number').text(data.count);
                });
            }
        });
    },

    updateDropdown: function () {
        const dropdownContainer = $('.cart-dropdown-container');
        if (dropdownContainer.length) {
            $.get('/gio-hang/dropdown', function (html) {
                dropdownContainer.html(html);
            });
        }
    }
};

// Global UI Action Handler
window.cwAction = cwAction;
window.cart = cart;

// Initialize on Load
$(document).ready(function () {
    qvAction.init();

    // Delegate Add to Cart clicks (Modern approach)
    $(document).on('click', '.add-to-cart', function (e) {
        e.preventDefault();
        const id = $(this).data('product-id');
        cart.add(id, this);
    });


    $.get('/gio-hang/so-luong', function (data) {
        $('.cart .number').text(data.count);
    });
});

// Windows Global Helpers (Legacy & Shortcut)
window.zoom = function (e) { qvAction.zoom(e); }
window.quickView = function (id) { cwAction.quickView(id); }
window.addWishlist = function (id, btn) { cwAction.addWishlist(id, btn); }
window.addCompare = function (id, btn) { cwAction.addCompare(id, btn); }
