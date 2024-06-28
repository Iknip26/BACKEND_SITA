<?php namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\VerifyEmail;

class CustomVerifyEmail extends VerifyEmail
{
public function toMail($notifiable)
{
return (new MailMessage)
->subject('Verify Your Email Address')
->line('Click the button below to verify your email address.')
->action('Verify Email', url(config('app.url').route('verification.verify', ['id' => $notifiable->getKey(), 'hash' =>
sha1($notifiable->getEmailForVerification())])))
->line('If you did not create an account, no further action is required.');
}
}
?>