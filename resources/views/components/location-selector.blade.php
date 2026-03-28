@props([
    'provinceName' => 'province_name',
    'provinceCode' => 'province_code',
    'districtName' => 'district_name',
    'districtCode' => 'district_code',
    'wardName' => 'ward_name',
    'wardCode' => 'ward_code',
    'oldProvince' => null,
    'oldDistrict' => null,
    'oldWard' => null,
    'required' => true
])

@php $uniqueId = $attributes->get('id', 'loc-' . Str::random(8)); @endphp

<div class="location-selector-wrapper space-y-4" id="{{ $uniqueId }}">
    <div class="single-input">
        <label class="form-label">Tỉnh / Thành phố {{ $required ? '*' : '' }}</label>
        <select name="{{ $provinceCode }}" class="form-select checkout-select province-select" {{ $required ? 'required' : '' }}>
            <option value="">-- Chọn tỉnh / thành phố --</option>
        </select>
        <input type="hidden" name="{{ $provinceName }}" class="province-name-input">
    </div>

    <div class="single-input">
        <label class="form-label">Quận / Huyện {{ $required ? '*' : '' }}</label>
        <select name="{{ $districtCode }}" class="form-select checkout-select district-select" {{ $required ? 'required' : '' }} disabled>
            <option value="">-- Chọn tỉnh trước --</option>
        </select>
        <input type="hidden" name="{{ $districtName }}" class="district-name-input">
    </div>

    <div class="single-input">
        <label class="form-label">Phường / Xã {{ $required ? '*' : '' }}</label>
        <select name="{{ $wardCode }}" class="form-select checkout-select ward-select" {{ $required ? 'required' : '' }} disabled>
            <option value="">-- Chọn quận / huyện trước --</option>
        </select>
        <input type="hidden" name="{{ $wardName }}" class="ward-name-input">
    </div>
</div>

@once
@push('scripts')
<script>
    class VietnamLocationSelector {
        constructor(wrapperId, options) {
            this.API = 'https://provinces.open-api.vn/api';
            this.container = document.getElementById(wrapperId);
            this.options = options;

            this.selProv = this.container.querySelector('.province-select');
            this.selDist = this.container.querySelector('.district-select');
            this.selWard = this.container.querySelector('.ward-select');

            this.inpProvName = this.container.querySelector('.province-name-input');
            this.inpDistName = this.container.querySelector('.district-name-input');
            this.inpWardName = this.container.querySelector('.ward-name-input');

            this.init();
        }

        async init() {
            // Load provinces
            try {
                const response = await fetch(`${this.API}/p/`);
                const data = await response.json();
                data.forEach(p => {
                    const opt = new Option(p.name, p.code);
                    if (String(p.code) === String(this.options.oldProvince)) opt.selected = true;
                    this.selProv.appendChild(opt);
                });

                if (this.options.oldProvince) {
                    this.updateName('province');
                    await this.loadDistricts(this.options.oldProvince);
                }
            } catch (e) {
                console.error('Error loading provinces', e);
            }

            this.selProv.addEventListener('change', (e) => this.onProvinceChange(e.target.value));
            this.selDist.addEventListener('change', (e) => this.onDistrictChange(e.target.value));
            this.selWard.addEventListener('change', () => this.updateName('ward'));
        }

        async onProvinceChange(code) {
            this.updateName('province');
            this.selDist.innerHTML = '<option value="">-- Chọn quận / huyện --</option>';
            this.selDist.disabled = !code;
            this.selWard.innerHTML = '<option value="">-- Chọn quận / huyện trước --</option>';
            this.selWard.disabled = true;
            this.inpDistName.value = '';
            this.inpWardName.value = '';

            if (code) await this.loadDistricts(code);
        }

        async loadDistricts(provinceCode) {
            try {
                const response = await fetch(`${this.API}/p/${provinceCode}?depth=2`);
                const data = await response.json();
                (data.districts || []).forEach(d => {
                    const opt = new Option(d.name, d.code);
                    if (String(d.code) === String(this.options.oldDistrict)) opt.selected = true;
                    this.selDist.appendChild(opt);
                });
                this.selDist.disabled = false;

                if (this.options.oldDistrict) {
                    this.updateName('district');
                    await this.loadWards(this.options.oldDistrict);
                }
            } catch (e) {
                console.error('Error loading districts', e);
            }
        }

        async onDistrictChange(code) {
            this.updateName('district');
            this.selWard.innerHTML = '<option value="">-- Chọn phường / xã --</option>';
            this.selWard.disabled = !code;
            this.inpWardName.value = '';

            if (code) await this.loadWards(code);
        }

        async loadWards(districtCode) {
            try {
                const response = await fetch(`${this.API}/d/${districtCode}?depth=2`);
                const data = await response.json();
                (data.wards || []).forEach(w => {
                    const opt = new Option(w.name, w.code);
                    if (String(w.code) === String(this.options.oldWard)) opt.selected = true;
                    this.selWard.appendChild(opt);
                });
                this.selWard.disabled = false;

                if (this.options.oldWard) {
                    this.updateName('ward');
                }
            } catch (e) {
                console.error('Error loading wards', e);
            }
        }

        updateName(level) {
            if (level === 'province') {
                this.inpProvName.value = this.selProv.options[this.selProv.selectedIndex]?.text || '';
            } else if (level === 'district') {
                this.inpDistName.value = this.selDist.options[this.selDist.selectedIndex]?.text || '';
            } else if (level === 'ward') {
                this.inpWardName.value = this.selWard.options[this.selWard.selectedIndex]?.text || '';
            }
        }
    }
</script>
@endpush
@endonce

@push('scripts')
<script>
    new VietnamLocationSelector('{{ $uniqueId }}', {
        oldProvince: '{{ $oldProvince }}',
        oldDistrict: '{{ $oldDistrict }}',
        oldWard: '{{ $oldWard }}'
    });
</script>
@endpush
