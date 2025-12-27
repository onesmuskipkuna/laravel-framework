<?php

namespace App\Mail;

use App\Models\Container;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContainerDamageNotification extends Mailable
{
    use Queueable, SerializesModels;

    public Container $container;
    public $damages;
    public $photos;

    /**
     * Create a new message instance.
     */
    public function __construct(Container $container, $damages, $photos)
    {
        $this->container = $container;
        $this->damages = $damages;
        $this->photos = $photos;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Container Damage Report - ' . $this->container->container_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.container-damage',
            with: [
                'container' => $this->container,
                'damages' => $this->damages,
                'photos' => $this->photos,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
