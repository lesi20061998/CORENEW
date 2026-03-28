<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>In đơn hàng #{{ $order->order_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #000;
            background: #fff;
            font-size: 13px;
            line-height: 1.5;
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            border: 1px solid #eee;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #000;
        }
        .logo {
            max-width: 150px;
            filter: grayscale(100%) contrast(150%);
        }
        .company-info {
            text-align: right;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .invoice-title {
            text-align: center;
            margin-bottom: 30px;
        }
        .invoice-title h1 {
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
        }
        .invoice-title p {
            margin: 5px 0 0;
            font-style: italic;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        .info-section h3 {
            margin-top: 0;
            font-size: 14px;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
            text-transform: uppercase;
        }
        .info-row {
            display: flex;
            margin-bottom: 5px;
        }
        .info-label {
            width: 120px;
            font-weight: bold;
            flex-shrink: 0;
        }
        .table-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table-items th {
            background: #f5f5f5;
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
        }
        .table-items td {
            border: 1px solid #000;
            padding: 8px;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .totals {
            margin-left: auto;
            width: 300px;
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .totals-label { font-weight: bold; }
        .grand-total {
            border-top: 1px solid #000;
            margin-top: 10px;
            padding-top: 10px;
            font-weight: bold;
            font-size: 16px;
        }
        .footer {
            margin-top: 50px;
            display: flex;
            justify-content: space-around;
            text-align: center;
        }
        .signature-box {
            height: 100px;
        }
        .note {
            margin-top: 30px;
            font-style: italic;
            font-size: 12px;
        }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
            .invoice-box { border: none; }
        }
        @page {
            size: A4;
            margin: 1cm;
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #000; color: #fff; border: none; cursor: pointer; border-radius: 4px;">In ngay (Ctrl + P)</button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #eee; border: none; cursor: pointer; border-radius: 4px; margin-left: 10px;">Đóng</button>
    </div>

    <div class="invoice-box">
        <div class="header">
            <div>
                @php $logo = setting('site_logo'); @endphp
                @if($logo)
                    <img src="{{ asset($logo) }}" alt="Logo" class="logo">
                @else
                    <h2 style="margin:0">{{ setting('site_name', 'VIET TIN MART') }}</h2>
                @endif
            </div>
            <div class="company-info">
                <div class="company-name">{{ setting('site_name', 'VIET TIN MART') }}</div>
                <div>Địa chỉ: {{ setting('contact_address', 'HCMC, Viet Nam') }}</div>
                <div>Điện thoại: {{ setting('contact_phone', '090xxxxxxx') }}</div>
                <div>Website: {{ url('/') }}</div>
            </div>
        </div>

        <div class="invoice-title">
            <h1>HÓA ĐƠN BÁN HÀNG</h1>
            <p>Ngày {{ $order->created_at->format('d') }} tháng {{ $order->created_at->format('m') }} năm {{ $order->created_at->format('Y') }}</p>
        </div>

        <div class="info-grid">
            <div class="info-section">
                <h3>Người mua hàng (Customer)</h3>
                <div class="info-row">
                    <span class="info-label">Họ tên:</span>
                    <span>{{ $order->customer_name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Điện thoại:</span>
                    <span>{{ $order->customer_phone }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Địa chỉ:</span>
                    <span>{{ $order->shipping_address }}</span>
                </div>
            </div>
            <div class="info-section">
                <h3>Thông tin đơn hàng</h3>
                <div class="info-row">
                    <span class="info-label">Mã đơn hàng:</span>
                    <span style="font-weight: bold;">#{{ $order->order_number }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Thanh toán:</span>
                    <span>{{ strtoupper($order->payment_method) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Trạng thái:</span>
                    <span>{{ App\Models\Order::$statuses[$order->status]['label'] ?? $order->status }}</span>
                </div>
            </div>
        </div>

        <table class="table-items">
            <thead>
                <tr>
                    <th class="text-center" width="40">STT</th>
                    <th>Tên sản phẩm</th>
                    <th class="text-right" width="100">Đơn giá</th>
                    <th class="text-center" width="50">SL</th>
                    <th class="text-right" width="120">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <div><strong>{{ $item->product_name }}</strong></div>
                        @if($item->variant_label)
                        <div style="font-size: 11px; color: #555;">{{ $item->variant_label }}</div>
                        @endif
                        @if($item->sku)
                        <div style="font-size: 11px; color: #555;">SKU: {{ $item->sku }}</div>
                        @endif
                    </td>
                    <td class="text-right">{{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <div class="totals-row">
                <span class="totals-label">Tạm tính:</span>
                <span>{{ number_format($order->subtotal, 0, ',', '.') }} VNĐ</span>
            </div>
            @if($order->discount > 0)
            <div class="totals-row">
                <span class="totals-label">Giảm giá:</span>
                <span>-{{ number_format($order->discount, 0, ',', '.') }} VNĐ</span>
            </div>
            @endif
            <div class="totals-row">
                <span class="totals-label">Phí vận chuyển:</span>
                <span>+{{ number_format($order->shipping_fee, 0, ',', '.') }} VNĐ</span>
            </div>
            <div class="totals-row grand-total">
                <span class="totals-label">TỔNG CỘNG:</span>
                <span>{{ number_format($order->total, 0, ',', '.') }} VNĐ</span>
            </div>
        </div>

        @if($order->customer_note)
        <div class="note">
            <strong>Ghi chú từ khách hàng:</strong> {{ $order->customer_note }}
        </div>
        @endif

        <div class="footer">
            <div class="signature">
                <p><strong>Người mua hàng</strong></p>
                <p style="font-size: 11px;">(Ký, ghi rõ họ tên)</p>
                <div class="signature-box"></div>
            </div>
            <div class="signature">
                <p><strong>Người lập hóa đơn</strong></p>
                <p style="font-size: 11px;">(Ký, ghi rõ họ tên)</p>
                <div class="signature-box"></div>
            </div>
        </div>

        <div style="text-align: center; margin-top: 50px; font-size: 11px; border-top: 1px dashed #ccc; padding-top: 10px;">
            Cảm ơn quý khách đã mua sắm tại {{ setting('site_name', 'VietTinMart') }}!
        </div>
    </div>

    <script>
        // window.print(); // Tự động mở hộp thoại in nếu muốn
    </script>
</body>
</html>
