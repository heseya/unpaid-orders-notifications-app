<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Api;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

final class UnpaidOrder extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public readonly Api $api,
        public readonly array $order,
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(Config::get('mail.from.address'), $this->api->name),
            subject: 'Twoje zamówienie nadal czeka na płatność',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'unpaid-order',
            with: [
                'storeName' => $this->api->name,
                'orderNumber' => $this->order['code'],
                'summary' => $this->order['summary'],
                'order' => $this->order,
                'url' => Str::of($this->api->payment_url)
                    ->finish('/')
                    ->append($this->order['code'])
                    ->toString(),
            ],
        );
    }
}
