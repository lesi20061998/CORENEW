<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Xác nhận đơn hàng #{{ $order->order_number }}</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #efefef; padding-bottom: 10px; }
        .order-info { margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f9f9f9; font-weight: 600; }
        .text-right { text-align: right; }
        .footer { font-size: 13px; color: #888; text-align: center; margin-top: 40px; border-top: 1px solid #efefef; padding-top: 20px; }
        .btn { display: inline-block; padding: 10px 20px; background: #3b82f6; color: white; text-decoration: none; border-radius: 5px; margin-top: 15px; }
        .status-badge { display: inline-block; padding: 4px 10px; border-radius: 15px; font-size: 12px; font-weight: 600; }
        .status-pending { background: #fff7ed; color: #ea580c; }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="color: #2563eb; margin: 0;">VietTinMart</h1>
        <p style="margin: 5px 0; color: #64748b;">Cảm ơn bạn đã đặt hàng!</p>
    </div>

    <div class="order-info">
        <p>Xin chào <strong>{{ $order->customer_name }}</strong>,</p>
        <p>Đơn hàng <strong>#{{ $order->order_number }}</strong> của bạn đã được tiếp nhận và đang được xử lý.</p>
        
        <h3 style="border-left: 4px solid #3b82f6; padding-left: 10px; margin-top: 30px;">Thông tin đơn hàng</h3>
        <p><strong>Ngày đặt hàng:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Trạng thái:</strong> <span class="status-badge status-pending">Chờ xử lý</span></p>
        <p><strong>Phương thức thanh toán:</strong> {{ strtoupper($order->payment_method) }}</p>
        <p><strong>Địa chỉ nhận hàng:</strong> {{ $order->shipping_address }}</p>

        <h3 style="border-left: 4px solid #3b82f6; padding-left: 10px; margin-top: 30px;">Chi tiết sản phẩm</h3>
        <table>
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>SL</th>
                    <th class="text-right">Đơn giá</th>
                    <th class="text-right">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        {{ $item->product_name }}
                        @if($item->variant_label)
                            <div style="font-size: 12px; color: #888;">{{ $item->variant_label }}</div>
                        @endif
                    </td>
                    <td>{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->price, 0, ',', '.') }}₫</td>
                    <td class="text-right">{{ number_format($item->total, 0, ',', '.') }}₫</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right" style="border:none; padding-top: 20px;">Tạm tính:</td>
                    <td class="text-right" style="border:none; padding-top: 20px;">{{ number_format($order->subtotal, 0, ',', '.') }}₫</td>
                </tr>
                <tr>
                    <td colspan="3" class="text-right" style="border:none;">Phí vận chuyển:</td>
                    <td class="text-right" style="border:none;">{{ number_format($order->shipping_fee, 0, ',', '.') }}₫</td>
                </tr>
                <tr>
                    <td colspan="3" class="text-right" style="font-weight: 700; border:none; padding-top: 10px; font-size: 18px;">Tổng cộng:</td>
                    <td class="text-right" style="font-weight: 700; color: #ef4444; border:none; padding-top: 10px; font-size: 18px;">{{ number_format($order->total, 0, ',', '.') }}₫</td>
                </tr>
            </tfoot>
        </table>

        @if($order->customer_note)
            <div style="background: #f8fafc; padding: 15px; border-radius: 8px; font-size: 14px;">
                <strong>Ghi chú:</strong> {{ $order->customer_note }}
            </div>
        @endif
    </div>

    <div class="footer">
        <p>Mọi thắc mắc vui lòng liên hệ hotline: <strong>0345.xxx.xxx</strong> hoặc email <strong>hotro@viettinmart.vn</strong></p>
        <p>&copy; {{ date('Y') }} VietTinMart. All rights reserved.</p>
    </div>
</body>
</html>
