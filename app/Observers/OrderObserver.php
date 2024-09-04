<?php

namespace App\Observers;

use App\Models\AdminNotification;
use App\Models\Monitor;
use App\Models\Order;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        $area_id = $order->client->area_id;
        $monitors = Monitor::where('area_id', $area_id)->get();
        foreach ($monitors as $monitor) {
            AdminNotification::create([
                'title' => 'New Order',
                'body' => 'لديك طلب قيد الانتظار',
                "order_id" => $order->id,
                "admin_id" => $monitor->monitor_id,
                "read" => 0 ,
            ]);
        }
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
