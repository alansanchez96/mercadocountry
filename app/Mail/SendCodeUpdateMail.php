<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCodeUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly User $user) { }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('mercadocountry@noreply.com', 'MercadoCountry E-Commerce'),
            subject: 'Confirma tu nuevo Email',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.send-code-update'
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
