<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cập nhật trạng thái đơn hàng #{{ $order->order_number }}</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .content { background: #f8fafc; padding: 25px; border-radius: 12px; border: 1px solid #e2e8f0; }
        .status-badge { display: inline-block; padding: 6px 12px; background: #3b82f6; color: white; border-radius: 20px; font-weight: 600; text-transform: uppercase; font-size: 14px; }
        .footer { font-size: 12px; color: #64748b; text-align: center; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="color: #1e293b;">VietTinMart</h2>
    </div>

    <div class="content">
        <p>Xin chào <strong>{{ $order->customer_name }}</strong>,</p>
        <p>Trạng thái đơn hàng <strong>#{{ $order->order_number }}</strong> của bạn đã được cập nhật:</p>
        
        <div style="text-align: center; margin: 25px 0;">
            <span class="status-badge">{{ $statusLabel }}</span>
        </div>

        @if($note)
            <div style="background: white; padding: 15px; border-radius: 8px; border-left: 4px solid #3b82f6; font-style: italic;">
                "{{ $note }}"
            </div>
        @endif

        <p style="margin-top: 25px;">Bạn có thể theo dõi đơn hàng của mình bất cứ lúc nào thông qua hệ thống của chúng tôi.</p>
        <p>Nếu bạn có bất kỳ thắc mắc nào, vui lòng liên hệ lại với chúng tôi.</p>
    </div>

    <div class="footer">
        Cảm ơn bạn đã tin tưởng và mua sắm tại VietTinMart!
    </div>
</body>
</html>
