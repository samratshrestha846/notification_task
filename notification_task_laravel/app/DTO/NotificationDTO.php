<?php

namespace App\DTO;

use App\Models\ColumnConstants\NotificationColumnConstant;

class NotificationDTO
{
    public function __construct(
        public int $userId,
        public string $message
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self($data[NotificationColumnConstant::USER_ID], $data[NotificationColumnConstant::MESSAGE]);
    }

    public function toArray(): array
    {
        return [
            NotificationColumnConstant::USER_ID => $this->userId,
            NotificationColumnConstant::MESSAGE => $this->message,
        ];
    }
}
