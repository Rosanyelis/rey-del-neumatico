<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendWorkorder extends Mailable
{
    use Queueable, SerializesModels;

    public $workOrder;
    public $urlpdf;
    public $namepdf;
    /**
     * Create a new message instance.
     */
    public function __construct($workOrder, $urlpdf, $namepdf)
    {
        $this->workOrder = $workOrder;
        $this->urlpdf = $urlpdf;
        $this->namepdf = $namepdf;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('ventas@reydelneumatico.cl', 'Rey del Neumatico'),
            replyTo: [
                new Address('ventas@reydelneumatico.cl', 'Ventas'),
            ],
            subject: 'Rey del Neumático - Orden de Trabajo',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'layout-email.email-workorder',
            with: [
                'workorder' => $this->workOrder
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->urlpdf)
                ->as($this->namepdf)
                ->withMime('application/pdf'),
        ];
    }
}
