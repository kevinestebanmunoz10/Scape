<?php

// Declaración del espacio de nombres donde vive esta clase

namespace App\Mail;

// Import de traits para encolar correos
use Illuminate\Bus\Queueable;
// Import de la clase base de mailables
use Illuminate\Mail\Mailable;
// Import para definir adjuntos del correo
use Illuminate\Mail\Mailables\Attachment;
// Import para definir el contenido del correo
use Illuminate\Mail\Mailables\Content;
// Import para definir el sobre (asunto y destinatarios)
use Illuminate\Mail\Mailables\Envelope;
// Import de traits para serializar modelos en colas
use Illuminate\Queue\SerializesModels;

// Clase que envía el correo con el código de verificación para restablecer la contraseña
class PasswordResetCode extends Mailable
{
    use Queueable, SerializesModels; // Habilita el encolado y la serialización de modelos

    public function __construct(public string $code) {} // Constructor que recibe el código de verificación

    // Define el sobre del correo
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Código de verificación SCAPE', // Asunto del correo
        );
    }

    // Define el contenido y la vista del correo
    public function content(): Content
    {
        return new Content(
            view: 'emails.password-reset-code', // Vista que renderiza el cuerpo del correo
        );
    }

    /** @return array<int, Attachment> */
    // Define los adjuntos del correo, en este caso ninguno
    public function attachments(): array
    {
        return []; // Retorna un arreglo vacío de adjuntos
    }
}
