<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderPaymentConfirmed extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public bool $test = false,
    ) {
    }

    public function envelope(): Envelope
    {
        $subjectPrefix = $this->test ? '[Test] ' : '';

        return new Envelope(
            subject: "{$subjectPrefix}Order Payment Confirmed - Order #{$this->order->id}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.payment-confirmed',
            with: [
                'order' => $this->order,
                'amountPaid' => $this->order->amount_paid ?? $this->order->total_amount,
                'appName' => config('app.name', 'Bronx'),
                'test' => $this->test,
            ],
        );
    }
}
