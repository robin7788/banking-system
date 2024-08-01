<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FundTransferNotification extends Notification
{
    use Queueable;

    protected $amount;
    protected $currency;
    protected $deposit;

    /**
     * Create a new notification instance.
     */
    public function __construct($amount, $currency, $deposit = true)
    {
        $this->amount = $amount;
        $this->currency = $currency;
        $this->deposit = $deposit;
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
        return (new MailMessage)
                    ->line('Dear sir / madam,')
                    ->line("A total of " . $this->amount . " " . strtoupper($this->currency) . " amount has been " . ($this->deposit ? "deposited to" : "withdrew from") . " your account.")
                    ->action('Please find more detail about transaction in banking system.', url('/'))
                    ->line('Thank you!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
