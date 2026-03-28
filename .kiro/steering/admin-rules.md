# Admin Rules — VietTinMart

## Stack & Layout

Admin panel dùng **Laravel + Blade + Alpine.js + Tailwind CDN**. Layout gốc là `admin.layouts.app`.
Mọi view admin phải `@extends('admin.layouts.app')` và dùng đúng các class đã định nghĩa sẵn trong layout.

---

## 1. Trước khi làm bất kỳ chức năng admin nào — kiểm tra Settings trước

Nhiều chức năng **không cần bảng DB mới** vì đã có bảng `settings` (key/value/group).

**Quy trình bắt buộc:**
1. Đọc `SettingController.php` để xem các group đã có
2. Đọc `Setting` model — hỗ trợ type: `text`, `boolean`, `json`, `textarea`, `image`
3. Nếu chức năng chỉ cần lưu cấu hình (on/off, text, URL, số) → **dùng Settings**, không tạo migration mới
4. Chỉ tạo migration mới khi cần lưu dữ liệu có quan hệ (nhiều bản ghi, foreign key, v.v.)

**Ví dụ nên dùng Settings thay vì DB mới:**
- Cấu hình phí ship, ngưỡng miễn phí ship → group `shipping`
- Thông tin liên hệ, hotline → group `contact`
- Bật/tắt tính năng → boolean setting
- Nội dung email template → textarea setting
- Cấu hình thanh toán → group `payment`

---

## 2. Class CSS có sẵn trong admin layout — dùng lại, không tự viết

Layout `admin.layouts.app` đã định nghĩa đầy đủ các class sau. **Bắt buộc dùng lại:**

### Buttons
```html
<button class="btn btn-primary">Lưu</button>
<button class="btn btn-secondary">Hủy</button>
<button class="btn btn-danger">Xóa</button>
<button class="btn btn-ghost">Xem</button>
<button class="btn btn-sm btn-primary">Nhỏ</button>
```

### Form inputs
```html
<label class="form-label">Tên trường</label>
<input class="form-input" type="text">
<select class="form-select">...</select>
<textarea class="form-textarea"></textarea>
<p class="form-hint">Gợi ý nhỏ bên dưới</p>
```

### Cards
```html
<div class="card">
    <div class="card-header">
        <span class="card-title">Tiêu đề</span>
        <button class="btn btn-primary btn-sm">Action</button>
    </div>
    <div class="card-body">...</div>
</div>
```

### Tables
```html
<div class="tbl-wrap">
    <table style="width:100%;border-collapse:collapse;">
        <thead class="tbl-head">
            <tr><th class="tbl-th">Cột</th></tr>
        </thead>
        <tbody>
            <tr class="tbl-tr"><td class="tbl-td">...</td></tr>
        </tbody>
    </table>
</div>
```

### Badges
```html
<span class="badge badge-green">Hoạt động</span>
<span class="badge badge-yellow">Chờ xử lý</span>
<span class="badge badge-red">Đã hủy</span>
<span class="badge badge-blue">Thông tin</span>
<span class="badge badge-gray">Nháp</span>
<span class="badge badge-purple">Đặc biệt</span>
<span class="badge badge-orange">Cảnh báo</span>
```

### Action buttons (trong table)
```html
<a href="#" class="act-btn view"><i class="fa-solid fa-eye"></i></a>
<a href="#" class="act-btn edit"><i class="fa-solid fa-pen"></i></a>
<button class="act-btn del"><i class="fa-solid fa-trash"></i></button>
```

### Flash messages
```html
<div class="flash flash-success"><i class="fa-solid fa-circle-check"></i> Thành công</div>
<div class="flash flash-error"><i class="fa-solid fa-circle-xmark"></i> Lỗi</div>
```

---

## 3. Components có sẵn — dùng lại khi cần

Các component trong `resources/views/admin/components/` đã được build sẵn:

| Component | Dùng khi |
|-----------|----------|
| `admin.components.editor` | Cần WYSIWYG editor (bài viết, trang, mô tả sản phẩm) |
| `admin.components.media-picker` | Cần chọn ảnh từ thư viện (đã include sẵn trong layout) |
| `admin.components.seo-checklist` | Form SEO (meta title, description, keywords, canonical) |

**Cách dùng:**
```blade
{{-- Editor --}}
@include('admin.components.editor', ['name' => 'content', 'value' => $post->content, 'height' => 400])

{{-- Media picker (đã có trong layout, chỉ cần gọi JS) --}}
<input type="text" id="image-input" name="image" value="{{ $product->image }}">
<button type="button" onclick="openMediaPicker('image-input')">Chọn ảnh</button>

{{-- SEO checklist --}}
@include('admin.components.seo-checklist', ['model' => $post])
```

**Khi nào tạo component mới:**
- UI đó xuất hiện ở **3+ nơi khác nhau** trong admin
- Logic đủ phức tạp để tách ra (ví dụ: variant editor, price range input)
- Đặt tại `resources/views/admin/components/` và dùng `@include`

---

## 4. Sections trong layout

```blade
@extends('admin.layouts.app')

@section('title', 'Tiêu đề tab')
@section('page-title', 'Tiêu đề topbar')
@section('page-subtitle', 'Mô tả nhỏ bên dưới tiêu đề')  {{-- tùy chọn --}}

@section('page-actions')
    {{-- Buttons góc phải topbar --}}
    <a href="{{ route('admin.xxx.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> Thêm mới
    </a>
@endsection

@section('content')
    {{-- Nội dung chính --}}
@endsection

@push('scripts')
    {{-- JS riêng của trang --}}
@endpush
```

---

## 5. Không tạo thêm database khi không cần thiết

Trước khi viết migration, hỏi:
- Có thể lưu vào `settings` table không? → Dùng `SettingService::updateSettings()`
- Có thể lưu vào JSON column của model hiện có không?
- Có thể dùng `config()` hoặc `.env` không?

Chỉ tạo migration khi dữ liệu cần: nhiều bản ghi, quan hệ, tìm kiếm/lọc theo cột, hoặc hiển thị dạng danh sách có phân trang.
