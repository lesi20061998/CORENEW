@extends('admin.layouts.app')
@section('title', 'Phương thức thanh toán')
@section('page-title', 'Phương thức thanh toán')
@section('page-subtitle', 'Quản lý và cấu hình các cổng thanh toán')

@section('page-actions')
<a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">
    <i class="fa-solid fa-arrow-left text-xs"></i> Quay lại
</a>
@endsection

@section('content')
@php
$s = $settingsMap; // shorthand

$gateways = [
    [
        'id'          => 'cod',
        'name'        => 'Thanh toán khi nhận hàng (COD)',
        'description' => 'Khách hàng thanh toán bằng tiền mặt khi nhận hàng. Không cần cấu hình thêm.',
        'icon'        => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="1.8"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/><line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/></svg>',
        'icon_bg'     => '#f0fdf4',
        'enabled_key' => 'cod_enabled',
        'fields'      => [],
    ],
    [
        'id'          => 'vietqr',
        'name'        => 'Chuyển khoản ngân hàng (VietQR)',
        'description' => 'Thanh toán bằng cách quét mã QR qua app ngân hàng. Hỗ trợ tất cả ngân hàng Việt Nam.',
        'icon'        => '<svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#0ea5e9" stroke-width="1.8"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="5" y="5" width="3" height="3" fill="#0ea5e9"/><rect x="16" y="5" width="3" height="3" fill="#0ea5e9"/><rect x="5" y="16" width="3" height="3" fill="#0ea5e9"/><path d="M14 14h3v3h-3zM17 17h3v3h-3zM14 20h3"/></svg>',
        'icon_bg'     => '#f0f9ff',
        'enabled_key' => 'bank_transfer_enabled',
        'fields'      => [
            ['key'=>'vietqr_bank_id',      'label'=>'Ngân hàng',                  'type'=>'text',   'placeholder'=>'Chọn ngân hàng', 'desc'=>'Mã BIN hoặc tên ngân hàng'],
            ['key'=>'vietqr_bank_name',    'label'=>'Tên ngân hàng (Lưu ẩn)',     'type'=>'hidden'],
            ['key'=>'vietqr_account_no',   'label'=>'Số tài khoản',               'type'=>'text',   'placeholder'=>'Số tài khoản nhận tiền'],
            ['key'=>'vietqr_account_name', 'label'=>'Tên người thụ hưởng',        'type'=>'text',   'placeholder'=>'Tên hiển thị trên QR'],
            ['key'=>'vietqr_template',     'label'=>'Template QR',                'type'=>'select', 'options'=>['compact2'=>'compact2 — 540×640 (khuyên dùng)','compact'=>'compact — 540×540','qr_only'=>'qr_only — 480×480 (chỉ QR)','print'=>'print — 600×776 (đầy đủ)']],
        ],
    ],
    [
        'id'          => 'momo',
        'name'        => 'Momo',
        'description' => 'Cổng thanh toán Momo — Quét mã QR hoặc ví điện tử Momo.',
        'icon'        => '<svg width="28" height="28" viewBox="0 0 100 100" style="fill:#a50064;"><rect width="100" height="100" rx="20"/><path d="M50 20c-16.5 0-30 13.5-30 30s13.5 30 30 30 30-13.5 30-30-13.5-30-30-30zm0 48c-9.9 0-18-8.1-18-18s8.1-18 18-18 18 8.1 18 18-8.1 18-18 18z" fill="#fff"/><circle cx="50" cy="50" r="10" fill="#fff"/></svg>',
        'icon_bg'     => '#fdf2f8',
        'enabled_key' => 'momo_enabled',
        'fields'      => [
            ['key'=>'momo_partner_code', 'label'=>'Partner Code', 'type'=>'text', 'placeholder'=>'Momo Partner Code'],
            ['key'=>'momo_access_key',   'label'=>'Access Key',   'type'=>'text', 'placeholder'=>'Momo Access Key'],
            ['key'=>'momo_secret_key',   'label'=>'Secret Key',   'type'=>'text', 'placeholder'=>'Momo Secret Key'],
        ],
    ],
];
@endphp

@if(session('success'))
<div class="mb-4 px-4 py-3 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm flex items-center gap-2">
    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
</div>
@endif

{{-- Gateway list --}}
<div class="space-y-3 max-w-4xl">
    @foreach($gateways as $gw)
    @php $isEnabled = !empty($s[$gw['enabled_key']]); @endphp

    <div class="card" style="border-radius:14px;overflow:hidden;">
        <div style="display:flex;align-items:center;gap:16px;padding:18px 20px;">

            {{-- Drag handle --}}
            <span style="color:#cbd5e1;font-size:18px;cursor:grab;flex-shrink:0;">⠿</span>

            {{-- Icon --}}
            <div style="width:48px;height:48px;border-radius:12px;background:{{ $gw['icon_bg'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                {!! $gw['icon'] !!}
            </div>

            {{-- Info --}}
            <div style="flex:1;min-width:0;">
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                    <span style="font-size:14px;font-weight:700;color:#1e293b;">{{ $gw['name'] }}</span>
                    @if($isEnabled)
                    <span style="display:inline-flex;align-items:center;gap:4px;padding:2px 10px;border-radius:20px;background:#dcfce7;color:#16a34a;font-size:11px;font-weight:700;">
                        <span style="width:6px;height:6px;border-radius:50%;background:#16a34a;display:inline-block;"></span>
                        Kích hoạt
                    </span>
                    @else
                    <span style="display:inline-flex;align-items:center;gap:4px;padding:2px 10px;border-radius:20px;background:#f1f5f9;color:#94a3b8;font-size:11px;font-weight:600;">
                        Tắt
                    </span>
                    @endif
                </div>
                <p style="font-size:12px;color:#64748b;margin:3px 0 0;line-height:1.4;">{{ $gw['description'] }}</p>
            </div>

            {{-- Actions --}}
            <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">
                {{-- Toggle enable --}}
                <form method="POST" action="{{ route('admin.settings.group.update', 'payment') }}" style="display:inline;">
                    @csrf @method('PUT')
                    <input type="hidden" name="settings[{{ $gw['enabled_key'] }}]" value="{{ $isEnabled ? '0' : '1' }}">
                    <button type="submit"
                            style="padding:6px 14px;border-radius:8px;border:1px solid {{ $isEnabled ? '#fca5a5' : '#bbf7d0' }};background:{{ $isEnabled ? '#fff5f5' : '#f0fdf4' }};color:{{ $isEnabled ? '#ef4444' : '#16a34a' }};font-size:12px;font-weight:600;cursor:pointer;">
                        {{ $isEnabled ? 'Tắt' : 'Bật' }}
                    </button>
                </form>

                @if(count($gw['fields']) > 0)
                <button type="button"
                        onclick="openGatewayModal('{{ $gw['id'] }}')"
                        style="padding:6px 16px;border-radius:8px;border:1px solid #e2e8f0;background:#fff;color:#374151;font-size:12px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px;">
                    <i class="fa-solid fa-gear" style="font-size:11px;color:#94a3b8;"></i> Cài đặt
                </button>
                @endif
            </div>
        </div>

        {{-- VietQR inline preview (only when enabled) --}}
        @if($gw['id'] === 'vietqr' && $isEnabled && !empty($s['vietqr_bank_id']) && !empty($s['vietqr_account_no']))
        @php
            $qrUrl = 'https://img.vietqr.io/image/'
                . urlencode($s['vietqr_bank_id']) . '-'
                . urlencode($s['vietqr_account_no']) . '-'
                . ($s['vietqr_template'] ?? 'compact2') . '.png'
                . '?amount=0&addInfo=' . urlencode($s['vietqr_description'] ?? 'Thanh toan')
                . '&accountName=' . urlencode($s['vietqr_account_name'] ?? '');
        @endphp
        <div style="border-top:1px solid #f1f5f9;padding:14px 20px 14px 84px;display:flex;align-items:center;gap:16px;background:#fafbfc;">
            <img src="{{ $qrUrl }}" alt="QR Preview" style="width:72px;height:72px;border-radius:8px;border:1px solid #e2e8f0;object-fit:contain;">
            <div style="font-size:12px;color:#64748b;line-height:1.6;">
                <strong style="color:#1e293b;">{{ $s['vietqr_account_name'] ?? '' }}</strong><br>
                {{ $s['vietqr_account_no'] ?? '' }} — {{ !empty($s['vietqr_bank_name']) ? $s['vietqr_bank_name'] : strtoupper($s['vietqr_bank_id']) }}
            </div>
        </div>
        @endif
    </div>
    @endforeach

    {{-- Add more placeholder --}}
    <div class="card" style="border-radius:14px;border:2px dashed #e2e8f0;background:#fafbfc;">
        <div style="padding:18px 20px;display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:12px;">
                <span style="font-size:18px;">🇻🇳</span>
                <span style="font-size:14px;font-weight:600;color:#64748b;">Thêm cổng thanh toán</span>
            </div>
            <span style="color:#94a3b8;font-size:18px;">∨</span>
        </div>
    </div>
</div>

@endsection

@push('modals')
@foreach($gateways as $gw)
@if(count($gw['fields']) > 0)
<div id="modal-{{ $gw['id'] }}"
     style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.45);align-items:center;justify-content:center;padding:20px;">
    <div style="background:#fff;border-radius:16px;width:100%;max-width:560px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.2);">

        {{-- Modal header --}}
        <div style="display:flex;align-items:center;justify-content:space-between;padding:20px 24px;border-bottom:1px solid #f1f5f9;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:40px;height:40px;border-radius:10px;background:{{ $gw['icon_bg'] }};display:flex;align-items:center;justify-content:center;">
                    {!! $gw['icon'] !!}
                </div>
                <div>
                    <p style="font-size:15px;font-weight:700;color:#1e293b;margin:0;">{{ $gw['name'] }}</p>
                    <p style="font-size:11px;color:#94a3b8;margin:0;">Cấu hình cổng thanh toán</p>
                </div>
            </div>
            <button onclick="closeGatewayModal('{{ $gw['id'] }}')"
                    style="width:32px;height:32px;border-radius:8px;border:1px solid #e2e8f0;background:#f8fafc;color:#64748b;font-size:18px;cursor:pointer;display:flex;align-items:center;justify-content:center;">×</button>
        </div>

        {{-- Modal body --}}
        <form method="POST" action="{{ route('admin.settings.group.update', 'payment') }}">
            @csrf @method('PUT')
            <div style="padding:24px;display:flex;flex-direction:column;gap:16px;">

                @foreach($gw['fields'] as $field)
                <div style="{{ ($field['type'] ?? '') === 'hidden' ? 'display:none;' : '' }}">
                    @if(($field['type'] ?? '') !== 'hidden')
                    <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">
                        {{ $field['label'] }}
                    </label>
                    @endif
                    
                    @if($gw['id'] === 'vietqr' && $field['key'] === 'vietqr_bank_id')
                        <select name="settings[{{ $field['key'] }}]" id="vtm-input-field-{{ $gw['id'] }}-{{ $field['key'] }}" class="vtm-gateway-select vietqr-bank-select" style="border-radius:10px; width: 100%;">
                            <option value="{{ $s[$field['key']] ?? '' }}">{{ $s['vietqr_bank_name'] ?? ($s[$field['key']] ?? 'Chọn ngân hàng') }}</option>
                        </select>
                        <p style="font-size:11px;color:#94a3b8;margin:6px 0 0;">{{ $field['desc'] ?? '' }}</p>
                    @elseif($field['type'] === 'select')
                        <select name="settings[{{ $field['key'] }}]" class="vtm-gateway-select" style="border-radius:10px; display: block; height: 42px;">
                            @foreach($field['options'] as $val => $lbl)
                            <option value="{{ $val }}" {{ ($s[$field['key']] ?? '') == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    @else
                        @if(!empty($field['desc']) && !($gw['id'] === 'vietqr' && $field['key'] === 'vietqr_bank_id'))
                        <p style="font-size:11px;color:#94a3b8;margin:0 0 6px;">{{ $field['desc'] }}</p>
                        @endif
                        <input type="{{ $field['type'] ?? 'text' }}" name="settings[{{ $field['key'] }}]"
                            value="{{ $s[$field['key']] ?? '' }}"
                            placeholder="{{ $field['placeholder'] ?? '' }}"
                            class="vtm-gateway-input" style="border-radius:10px; border: 1.5px solid #f1f5f9; padding: 10px 16px; width: 100%; font-size: 14px;"
                            id="vtm-input-field-{{ $gw['id'] }}-{{ $field['key'] }}">
                    @endif
                </div>
                @endforeach



            </div>

            {{-- Modal footer --}}
            <div style="padding:16px 24px;border-top:1px solid #f1f5f9;display:flex;justify-content:flex-end;gap:10px;">
                <button type="button" onclick="closeGatewayModal('{{ $gw['id'] }}')"
                        style="padding:8px 20px;border-radius:10px;border:1px solid #e2e8f0;background:#f8fafc;color:#374151;font-size:13px;font-weight:600;cursor:pointer;">
                    Hủy
                </button>
                <button type="submit"
                        style="padding:8px 20px;border-radius:10px;border:none;background:#1e293b;color:#fff;font-size:13px;font-weight:600;cursor:pointer;">
                    <i class="fa-solid fa-check" style="font-size:11px;margin-right:4px;"></i> Lưu cài đặt
                </button>
            </div>
        </form>
    </div>
</div>
@endif
@endforeach
@endpush

@push('styles')
<style>
    .vtm-gateway-input, .vtm-gateway-select {
        transition: all 0.2s ease-in-out;
    }
    .vtm-gateway-input:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1) !important;
        outline: none;
    }
    /* Fix Select2 conflict with NiceSelect */
    .select2-container--default .select2-selection--single {
        border-radius: 10px !important;
        border: 1.5px solid #f1f5f9 !important;
        height: 42px !important;
        display: flex !important;
        align-items: center !important;
        padding-left: 8px !important;
    }
</style>
@endpush

@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
let bankListData = [];

async function fetchBanks() {
    try {
        const response = await fetch('https://api.vietqr.io/v2/banks');
        const data = await response.json();
        if (data.code === '00') {
            bankListData = data.data;
            initBankSelect();
        }
    } catch (error) {
        console.error('Failed to fetch banks:', error);
    }
}

function initBankSelect() {
    $('.vietqr-bank-select').select2({
        data: bankListData.map(b => ({
            id: b.bin || b.shortName,
            text: `[${b.shortName}] ${b.name}`,
            bankName: b.name,
            shortName: b.shortName,
            logo: b.logo
        })),
        templateResult: formatBankOption,
        templateSelection: formatBankOption,
        dropdownParent: $('#modal-vietqr')
    }).on('change', function() {
        // Set bank name hidden field
        const data = $(this).select2('data')[0];
        if (data && data.bankName) {
            const nameInput = document.getElementById('vtm-input-field-vietqr-vietqr_bank_name');
            if (nameInput) nameInput.value = data.bankName;
        }
    });
}

function formatBankOption(bank) {
    if (!bank.id || !bank.logo) return bank.text;
    return $(`<div style="display:flex;align-items:center;gap:10px;">
        <img src="${bank.logo}" style="width:32px;height:20px;object-fit:contain;background:#fff;border-radius:2px;padding:1px;">
        <span style="font-size:12px;">${bank.text}</span>
    </div>`);
}

function openGatewayModal(id) {
    const modal = document.getElementById('modal-' + id);
    if (modal) { modal.style.display = 'flex'; document.body.style.overflow = 'hidden'; }
    if (id === 'vietqr' && bankListData.length === 0) {
        fetchBanks();
    }
}

function closeGatewayModal(id) {
    const modal = document.getElementById('modal-' + id);
    if (modal) { modal.style.display = 'none'; document.body.style.overflow = ''; }
}

// Close on backdrop click
document.querySelectorAll('[id^="modal-"]').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) closeGatewayModal(this.id.replace('modal-',''));
    });
});
</script>
@endpush
