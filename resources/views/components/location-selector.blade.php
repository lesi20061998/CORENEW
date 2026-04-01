@props(['oldProvince' => null, 'oldDistrict' => null, 'oldWard' => null])

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>
        <label class="form-label">Tỉnh / Thành phố</label>
        <select name="province_code" class="form-select">
            <option value="">Chọn tỉnh thành</option>
        </select>
    </div>
    <div>
        <label class="form-label">Quận / Huyện</label>
        <select name="district_code" class="form-select">
            <option value="">Chọn quận huyện</option>
        </select>
    </div>
    <div>
        <label class="form-label">Phường / Xã</label>
        <select name="ward_code" class="form-select">
            <option value="">Chọn phường xã</option>
        </select>
    </div>
</div>
