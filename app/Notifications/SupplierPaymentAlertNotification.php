<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SupplierPaymentAlertNotification extends Notification
{
    use Queueable;

    private $data;

    /**
     * Create a new notification instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['database']; // Store in the database
    }

    /**
     * Get the array representation of the notification for database storage.
     */
    public function toDatabase($notifiable)
    {
        return [
            'supplier_name' => $this->data['supplier_name'],
            'amount' => $this->data['amount'],
            'pay_date' => $this->data['pay_date'],
            'notice_date' => $this->data['notice_date'],
            'message' => 'A supplier payment alert is due today.',
        ];
    }
}