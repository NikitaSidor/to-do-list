<?php
namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class AuthUserNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Добро пожаловать!')
            ->greeting('Привет, ' . $this->user->name)
            ->line('Вы успешно зарегистрировались на нашем сайте.')
            ->action('Перейти на дашборд', url('/dashboard'))
            ->line('Спасибо за регистрацию!');
    }
}
