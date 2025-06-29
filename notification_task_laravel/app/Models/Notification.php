<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\ColumnConstants\NotificationColumnConstant;
use Illuminate\Database\Eloquent\Model;
use App\Enums\NotificationStatusEnum;

class Notification extends Model implements NotificationColumnConstant
{
    protected $table = 'notifications';

    protected $fillable = [
        self::USER_ID,
        self::MESSAGE,
        self::STATUS,
    ];

    protected $casts = [
        self::USER_ID => 'integer',
     ];

    public function user()
    {
        return $this->belongsTo(User::class, self::USER_ID);
    }

    public function scopeSent($query)
    {
        return $query->where(self::STATUS, NotificationStatusEnum::SENT);
    }

    public function scopePending($query)
    {
        return $query->where(self::STATUS, NotificationStatusEnum::PENDING);
    }

    public function scopeFailed($query)
    {
        return $query->where(self::STATUS, NotificationStatusEnum::FAILED);
    }
}
