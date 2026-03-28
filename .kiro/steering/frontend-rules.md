# Frontend Rules — VietTinMart

## QUAN TRỌNG: Không tự tạo CSS/class frontend

Dự án này sử dụng **Ekomart HTML Template** làm giao diện client hoàn chỉnh.

### Quy tắc bắt buộc

1. **KHÔNG được tự viết CSS mới** cho phần client (frontend). Không thêm `<style>` block, không tạo file `.css` mới cho giao diện người dùng.

2. **KHÔNG được tự đặt tên class mới** cho HTML frontend. Mọi class phải lấy trực tiếp từ Ekomart template.

3. **Trước khi viết bất kỳ view blade nào cho client**, phải đọc file HTML gốc tương ứng trong:
   ```
   VietTinMart/resources/ekomart-html/
   ```
   Mapping file:
   - Cart page → `cart.html`
   - Checkout page → `checkout.html`
   - Shop listing → `shop-grid-sidebar.html` hoặc `shop-list-sidebar.html`
   - Product detail → `shop-details.html`
   - Blog listing → `blog.html`
   - Blog detail → `blog-details.html`
   - Login → `login.html`
   - Register → `register.html`
   - Account → `account.html`
   - Track order → `trackorder.html`
   - Contact → `contact.html`
   - Homepage → `index.html`

4. **Copy đúng cấu trúc HTML và class** từ file Ekomart, chỉ thay nội dung tĩnh bằng Blade/PHP dynamic.

5. **JavaScript**: Chỉ viết JS tối thiểu cho logic động (AJAX, form submit). Không viết lại animation hay UI behavior đã có trong `theme/js/`.

6. **Admin panel** là ngoại lệ — phần admin có thể dùng Bootstrap utility classes tự do vì admin không dùng Ekomart theme.

### Ví dụ đúng

```blade
{{-- LẤY class từ ekomart cart.html --}}
<div class="rts-cart-area rts-section-gap bg_light-1">
    <div class="rts-cart-list-area">
        <div class="single-cart-area-list head">...</div>
        <div class="single-cart-area-list main item-parent">...</div>
    </div>
    <div class="cart-total-area-start-right">...</div>
</div>
```

### Ví dụ sai ❌

```blade
{{-- TỰ TẠO class mới — KHÔNG được làm --}}
<style>
.vtm-cart-table { ... }
.cart-totals-box { ... }
</style>
<div class="vtm-cart-table">...</div>
```

---

## Sử dụng FULL template Ekomart

Khi làm bất kỳ trang client nào, phải sử dụng **toàn bộ** cấu trúc HTML của trang Ekomart tương ứng — không được cắt bớt section, không được bỏ qua wrapper, không được đơn giản hóa layout.

### Quy trình bắt buộc

1. **Đọc toàn bộ file HTML gốc** (dùng `readFile` với `skipPruning: true`, đọc hết từng chunk 1000 dòng cho đến hết file).

2. **Giữ nguyên toàn bộ cấu trúc** — bao gồm:
   - Breadcrumb area
   - Tất cả wrapper div và class ngoài cùng
   - Tất cả section con bên trong (progress bar, coupon area, shipping options, v.v.)
   - Bottom service bar (`rts-shorts-service-area`) nếu có trong trang gốc

3. **Chỉ thay thế nội dung tĩnh** bằng Blade dynamic, giữ nguyên toàn bộ HTML skeleton.

4. **Không được rút gọn** — ví dụ cart.html có `cart-top-area-note` (progress bar free shipping), `bottom-cupon-code-cart-area` (coupon input), `cart-total-area-start-right` với radio shipping options → tất cả phải có trong blade.

5. **Checkout**: giữ nguyên `coupon-input-area-1`, `rts-billing-details-area` với `single-input` / `half-input-wrapper`, `right-card-sidebar-checkout` với `single-shop-list`, `cottom-cart-right-area` với radio payment options.

### Danh sách section cần giữ theo trang

| Trang | Sections bắt buộc |
|-------|-------------------|
| Cart | `cart-top-area-note` (progress), `rts-cart-list-area` (rows), `bottom-cupon-code-cart-area`, `cart-total-area-start-right` (shipping radio + totals) |
| Checkout | `coupon-input-area-1` (login + coupon), `rts-billing-details-area` (form), `right-card-sidebar-checkout` (order summary + payment radio + place order) |
| Shop | Sidebar filter, toolbar (sort + layout switch), product grid/list, pagination |
| Product detail | Gallery + thumbnails, price area, variant buttons, quantity-edit, add-to-cart, tabs (description/reviews), related products |
| Blog | Post grid, sidebar (search + recent posts + categories) |
| Account | Sidebar nav tabs, profile form, orders table |