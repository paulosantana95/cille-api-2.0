<?php

namespace App\Notifications;

use App\Models\User\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetNotification extends Notification
{
  use Queueable;

  private User $user;
  private string $token;

  /**
   * Create a new notification instance.
   */
  public function __construct(User $user, string $token)
  {
    $this->user = $user;
    $this->token = $token;
  }

  /**
   * Get the notification's delivery channels.
   *
   * @return array<int, string>
   */
  public function via(object $notifiable): array
  {
    return ['mail'];
  }

  /**
   * Get the mail representation of the notification.
   */
  public function toMail(object $notifiable): MailMessage
  {
    $path = env('FRONTEND_URL');
    return (new MailMessage)
      ->subject('Your Reset Password Subject Here')
      ->line('You are receiving this email because we received a password reset request for your account.')
      ->action(
        'Reset Password',
        url('password/reset', sprintf("%s?email=%s&token=%s", $path, $this->user->email, $this->token))
      )
      ->line('If you did not request a password reset, no further action is required.');
  }
}
