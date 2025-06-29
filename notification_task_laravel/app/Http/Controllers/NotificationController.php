<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Notification;
use App\Services\NotificationService;
use App\Repositories\Notification\NotificationRepository;
use App\Http\Requests\NotificationRequest;
use App\DTO\NotificationDTO;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class NotificationController extends Controller
{
    use ResponseHelper;

    public function __construct(
        protected NotificationService $service,
        protected NotificationRepository $repo
    ) {}

    public function store(NotificationRequest $request): JsonResponse
    {
        try {
            $dto = NotificationDTO::fromRequest($request->validated());
            return $this->responseCreated($this->service->send($dto));
        }catch (ValidationException $e) {
            return response()->json([
                'errors' => $e->errors(),
                'message' => 'Notification rate limit exceeded'
            ], 429);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
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
