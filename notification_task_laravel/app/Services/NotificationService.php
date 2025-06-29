<?php

namespace App\Services;

use App\DTO\NotificationDTO;
use App\Events\NotificationCreated;
use App\Models\Notification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use App\Repositories\Notification\NotificationRepositoryInterface;
use Illuminate\Validation\ValidationException;

class NotificationService extends BaseService
{
    public function __construct(
        protected NotificationRepositoryInterface $repo
    ) {}

    public function send(NotificationDTO $dto): Notification
    {
        $rateKey = "user:{$dto->userId}:notifications";

        $count = Cache::increment($rateKey);

        if ($count === false) {
            $count = (int) Cache::get($rateKey, 0) + 1;
            Cache::put($rateKey, $count, now()->addHour());
        }

        if ($count > 10) {
            throw ValidationException::withMessages([
                'notifications' => ['You have exceeded the limit of 10 notifications per hour. Please try again later.']
            ]);
        }

        $notification = $this->repo->create($dto->toArray());

        NotificationCreated::dispatch($notification);

        return $notification;
    }

}
