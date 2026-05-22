<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoginOTPMail extends Mailable {
    use Queueable, SerializesModels;
    public $otp;
    public function __construct($otp) { $this->otp = $otp; }
    public function envelope(): Envelope { return new Envelope(subject: 'Your Login OTP Code'); }
    public function content(): Content { return new Content(view: 'mail.login_otp'); }
    public function attachments(): array { return []; }
}
