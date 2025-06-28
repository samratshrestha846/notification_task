<?php

namespace App\Http\Requests;

use App\Models\ColumnConstants\NotificationColumnConstant;
use Illuminate\Foundation\Http\FormRequest;

class NotificationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    public function rules(): array
    {
        return [
            NotificationColumnConstant::USER_ID => ['required', 'exists:users,id'],
            NotificationColumnConstant::MESSAGE => ['required', 'string'],
        ];
    }
}
