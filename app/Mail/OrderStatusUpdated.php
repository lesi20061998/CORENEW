<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order, public string $statusLabel, public ?string $note = null)
    {
    }

    public function build()
    {
        return $this->subject('Cập nhật trạng thái đơn hàng #' . $this->order->order_number)
            ->view('emails.order_status_updated');
    }
}
