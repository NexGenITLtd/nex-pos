<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StockIn;

class CheckStockAlerts extends Command
{
    protected $signature = 'stock:check-alerts';
    protected $description = 'Check for stock alerts and notify admins.';

    public function handle()
    {
        StockIn::notifyForTodayAlerts();
        $this->info('Stock alerts checked and notifications sent.');
    }
}

