<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderPlaced extends Mailable
{
    use Queueable, SerializesModels;


    public function __construct(public Order $order, public string $target = 'customer')
    {
    }

    public function setTarget(string $target): self
    {
        $this->target = $target;
        return $this;
    }

    public function build()
    {
        $order = $this->order->load('items');

        $subject = $this->target === 'admin'
            ? "[ĐƠN HÀNG MỚI] #" . $order->order_number . " từ " . $order->customer_name
            : "Xác nhận đặt hàng thành công - #" . $order->order_number;

        $fromAddr = config('mail.from.address') ?: config('mail.mailers.smtp.username');
        $fromName = setting('site_name', 'VietTinMart');

        return $this->from($fromAddr, $fromName)
            ->subject($subject)
            ->view('emails.order_placed');
    }
}
