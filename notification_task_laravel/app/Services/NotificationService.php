<?php

namespace App\Services;

use App\DTO\NotificationDTO;
use App\Models\Notification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use App\Repositories\Notification\NotificationRepositoryInterface;

class NotificationService extends BaseService
{
    public function __construct(
        protected NotificationRepositoryInterface $repo
    ) {}

    public function send(NotificationDTO $dto): Notification
    {
        $rateKey = "user:{$dto->userId}:notifications";

        $count = Cache::increment($rateKey);
        Cache::put($rateKey, $count, now()->addHour());

        if ($count > 10) {
            throw new \Exception('Rate limit exceeded');
        }

        $notification = $this->repo->create($dto->toArray());
        Http::post(config('services.notification_microservice.url'), [
            'notification_id' => $notification->id,
            'user_id' => $dto->userId,
            'message' => $dto->message,
        ]);

        return $notification;
    }
}
