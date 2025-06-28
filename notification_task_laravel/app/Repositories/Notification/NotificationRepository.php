<?php

namespace App\Repositories\Notification;

use App\Models\Notification;
use Illuminate\Support\Collection;
use App\Repositories\BaseRepository;
use App\Repositories\Notification\NotificationRepositoryInterface;

class NotificationRepository extends BaseRepository implements NotificationRepositoryInterface
{
    public function __construct(Notification $model)
    {
        parent::__construct($model);
    }

    public function create(array $data): Notification
    {
        return parent::create($data);
    }

    public function markProcessed(int $id): void
    {
        $this->update($id, ['status' => 'processed']);
    }

    public function getRecent(): Collection
    {
        return $this->model->latest()->limit(10)->get();
    }

    public function getSummary(): array
    {
        return [
            'total' => $this->model->count(),
            'processed' => $this->model->where('status', 'processed')->count(),
        ];
    }
}
