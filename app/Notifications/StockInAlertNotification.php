<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StockInAlertNotification extends Notification
{
    public $stockIn;

    public function __construct($stockIn)
    {
        $this->stockIn = $stockIn;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "Stock alert for product: {$this->stockIn->product->name}.",
            'alert_date' => $this->stockIn->alert_date,
        ];
    }
}


