<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $token,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = url(route('password.reset', ['token' => $this->token], false));
        $ttl = config('uam.forgot_password_token_ttl', 15);

        return (new MailMessage)
            ->subject('Reset Password - SIPADU')
            ->greeting('Halo ' . $notifiable->name)
            ->line('Anda menerima email ini karena ada permintaan reset password untuk akun SIPADU Anda.')
            ->action('Reset Password', $url)
            ->line("Tautan ini akan kedaluwarsa dalam {$ttl} menit.")
            ->line('Jika Anda tidak meminta reset password, abaikan email ini.')
            ->salutation('Salam, Tim SIPADU');
    }
}
