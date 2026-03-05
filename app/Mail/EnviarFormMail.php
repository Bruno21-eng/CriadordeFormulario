<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EnviarFormMail extends Mailable
{
    use Queueable, SerializesModels;

    // Adicione a variável $url aqui
    public function __construct(
        public $titulo,
        public $url
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Novo Formulário Disponível: ' . $this->titulo,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.enviar-form',
        );
    }
}
