<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Notifications\StockInAlertNotification;

class StockIn extends Model
{
    use HasFactory;
	protected $fillable = [
        'store_id',
        'batch_id',
        'product_id',
        'supplier_id',
        'purchase_price',
        'sell_price',
        'qty',
        'rack_id',
        'expiration_date',
        'alert_date',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id'); // foreign key: 'product_id'
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id'); // foreign key: 'supplier_id'
    }
    public function rack()
    {
        return $this->belongsTo(Rack::class, 'rack_id'); // foreign key: 'supplier_id'
    }

    // StockIn Model
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public static function notifyForTodayAlerts()
    {
        // Get today's date
        $today = now()->startOfDay();

        // Check if notifications for today already exist
        $existingNotifications = auth()->user()
            ->notifications()
            ->where('created_at', '>=', $today)
            ->exists();

        // If notifications already exist for today, don't send them again
        if ($existingNotifications) {
            return;
        }

        // Get stock-ins with active products and today's alert date
        $stockIns = self::whereNotNull('alert_date')
                        ->whereDate('alert_date', '=', now()->toDateString())
                        ->whereHas('product', function ($query) {
                            $query->where('status', 'active'); // Filter active products
                        })
                        ->get();

        foreach ($stockIns as $stockIn) {
            $user = auth()->user(); // Assuming you want to notify the authenticated user
            $user->notify(new StockInAlertNotification($stockIn));
        }
    }


}
