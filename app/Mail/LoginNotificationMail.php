<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoginNotificationMail extends Mailable {
    use Queueable, SerializesModels;
    public $userName, $userEmail, $time, $ipAddress;
    public function __construct($userName, $userEmail, $time, $ipAddress) {
        $this->userName = $userName; $this->userEmail = $userEmail;
        $this->time = $time; $this->ipAddress = $ipAddress;
    }
    public function envelope(): Envelope { return new Envelope(subject: 'Login Activity Notification'); }
    public function content(): Content { return new Content(view: 'mail.login_notification'); }
    public function attachments(): array { return []; }
}
