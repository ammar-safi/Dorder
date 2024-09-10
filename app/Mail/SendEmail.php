<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $subjectTitle;
    public $imageUrl;
    public $description;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subjectTitle, $imageUrl, $description)
    {
        $this->subjectTitle = $subjectTitle;
        $this->imageUrl = $imageUrl;
        $this->description = $description;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectTitle,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.hello', 
        );
    }
}
