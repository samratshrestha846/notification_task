<?php

namespace App\Listeners;

use App\Events\NotificationCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DispatchNotificationToQueue implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NotificationCreated $event): void
    {
        $notification = $event->notification;

        try {
            Http::post(config('services.notification_microservice.url'), [
                'notification_id' => $notification->id,
                'user_id' => $notification->user_id,
                'message' => $notification->message,
            ]);

            $notification->update(['status' => 'sent']);

        } catch (\Throwable $e) {
            Log::error("Notification queue failed: " . $e->getMessage());
            throw $e;
        }
    }
}
