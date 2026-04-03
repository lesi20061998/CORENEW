@props([
    'provinceName' => 'province_code',
    'wardName' => 'ward_code',
    'provinceLabelName' => 'province_name',
    'wardLabelName' => 'ward_name',
    'districtLabelName' => 'district_name',
    'selectedProvince' => null,
    'selectedWard' => null,
    'required' => true,
    'id' => null,
    'containerClass' => 'row g-3',
    'colClass' => 'col-md-6 mb-3',
    'selectClass' => 'form-select'
])

@php
    $id = $id ?? 'address-selector-' . Str::random(8);
    $selectedProvince = old($provinceName, $selectedProvince);
    $selectedWard = old($wardName, $selectedWard);
@endphp

<div class="address-selector-container {{ $containerClass }}" id="{{ $id }}">
    <div class="{{ $colClass }}">
        <label class="form-label" for="province-{{ $id }}">{{ __('Tỉnh / Thành phố') }}{{ $required ? '*' : '' }}</label>
        <select id="province-{{ $id }}" 
                name="{{ $provinceName }}" 
                class="{{ $selectClass }} address-province-select" 
                {{ $required ? 'required' : '' }}>
            <option value="">{{ __('Chọn Tỉnh / Thành phố') }}</option>
        </select>
        <input type="hidden" name="{{ $provinceLabelName }}" class="address-province-name">
    </div>
    
    <div class="{{ $colClass }}">
        <label class="form-label" for="ward-{{ $id }}">{{ __('Phường / Xã (Quận / Huyện)') }}{{ $required ? '*' : '' }}</label>
        <select id="ward-{{ $id }}" 
                name="{{ $wardName }}" 
                class="{{ $selectClass }} address-ward-select" 
                {{ $required ? 'required' : '' }} 
                disabled>
            <option value="">{{ __('Chọn địa chỉ') }}</option>
        </select>
        <input type="hidden" name="{{ $wardLabelName }}" class="address-ward-name">
        <input type="hidden" name="{{ $districtLabelName }}" class="address-district-name">
    </div>
</div>

@once
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <style>
            .select2-container--default .select2-selection--single {
                height: 45px !important; 
                border: 1px solid #eeeeee !important; 
                border-radius: 6px !important;
                display: flex !important;
                align-items: center !important;
            }
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: normal !important; 
                padding-left: 15px !important;
                color: #444 !important;
            }
            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 43px !important;
            }
            /* Dark mode / Admin support */
            .card .select2-container--default .select2-selection--single {
                background-color: #fff !important;
                border: 1px solid #e2e8f0 !important;
            }
        </style>
    @endpush
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            (function() {
                let provincesDataCache = null;

                async function getProvincesData() {
                    if (provincesDataCache) return provincesDataCache;
                    try {
                        const response = await fetch('{{ asset("data/provinces.json") }}');
                        provincesDataCache = await response.json();
                        return provincesDataCache;
                    } catch (e) {
                        console.error("AddressSelector: Error loading data", e);
                        return [];
                    }
                }

                function initAddressSwitcher(container) {
                    const $container = $(container);
                    const $pSelect = $container.find('.address-province-select');
                    const $wSelect = $container.find('.address-ward-select');
                    const $pNameInput = $container.find('.address-province-name');
                    const $wNameInput = $container.find('.address-ward-name');
                    const $dNameInput = $container.find('.address-district-name');

                    const initialP = "{{ $selectedProvince }}";
                    const initialW = "{{ $selectedWard }}";

                    function initSelect2(el, placeholder) {
                        if ($.fn.niceSelect) $(el).niceSelect('destroy');
                        $(el).select2({ 
                            placeholder: placeholder, 
                            width: '100%',
                            dropdownParent: $container.parent() // Ensure visibility in modals if needed
                        });
                    }

                    initSelect2($pSelect, "Chọn Tỉnh / Thành phố");
                    initSelect2($wSelect, "Chọn địa chỉ");

                    getProvincesData().then(data => {
                        let options = '<option value=""></option>';
                        data.forEach(p => {
                            options += `<option value="${p.code}" ${p.code == initialP ? 'selected' : ''}>${p.name}</option>`;
                        });
                        $pSelect.html(options).trigger('change');
                    });

                    $pSelect.on('change', function() {
                        const pCode = $(this).val();
                        const pName = $(this).find('option:selected').text();
                        $pNameInput.val(pName);

                        if (!pCode) {
                            $wSelect.html('<option value=""></option>').prop('disabled', true).trigger('change');
                            return;
                        }

                        getProvincesData().then(data => {
                            const province = data.find(p => p.code == pCode);
                            let options = '<option value=""></option>';
                            if (province && province.districts) {
                                province.districts.forEach(d => {
                                    if (d.wards) {
                                        d.wards.forEach(w => {
                                            options += `<option value="${w.code}" data-district="${d.name}" ${w.code == initialW ? 'selected' : ''}>${w.name} (${d.name})</option>`;
                                        });
                                    }
                                });
                            }
                            $wSelect.prop('disabled', false).html(options).trigger('change');
                        });
                    });

                    $wSelect.on('change', function() {
                        const $opt = $(this).find('option:selected');
                        if (!$opt.val()) return;
                        const wName = $opt.text().split(' (')[0];
                        const dName = $opt.data('district');
                        $wNameInput.val(wName);
                        $dNameInput.val(dName);
                        
                        // Trigger custom event if needed
                        $container.trigger('address:changed', {
                            province: $pNameInput.val(),
                            ward: wName,
                            district: dName
                        });
                    });
                }

                $(document).ready(function() {
                    $('.address-selector-container').each(function() {
                        initAddressSwitcher(this);
                    });
                });
            })();
        </script>
    @endpush
@endonce
