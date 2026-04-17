<?php

namespace App\Mail;

use App\Models\Note;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NoteCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(protected Note $note) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouvelle note créée',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.notes.created',
            with: [
                'title' => $this->note->title,
                'content' => $this->note->content,
                'createdAt' => $this->note->created_at,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
