<?php

namespace App\Repositories\Notification;

use App\Models\Notification;
use Illuminate\Support\Collection;

interface NotificationRepositoryInterface
{
    public function create(array $data): Notification;
    public function markProcessed(int $id): void;
    public function getRecent(): Collection;
    public function getSummary(): array;
}
