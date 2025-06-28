<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Services\NotificationService;
use App\Repositories\Notification\NotificationRepository;
use App\Http\Requests\NotificationRequest;
use App\DTO\NotificationDTO;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    use ResponseHelper;

    public function __construct(
        protected NotificationService $service,
        protected NotificationRepository $repo
    ) {}

    public function store(NotificationRequest $request): JsonResponse
    {
        $dto = NotificationDTO::fromRequest($request->validated());
        return $this->responseCreated($this->service->send($dto));
    }

    public function recent(): JsonResponse
    {
        return $this->responseOk($this->repo->getRecent());
    }

    public function summary(): JsonResponse
    {
        return $this->responseOk($this->repo->getSummary());
    }
}
