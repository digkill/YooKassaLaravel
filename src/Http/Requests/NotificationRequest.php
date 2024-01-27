<?php

namespace Digkill\YooKassaLaravel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class NotificationRequest extends FormRequest
{
    /**
     * Authorize
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Rules
     */
    public function rules(): array
    {
        return [
            'type' => ['string', 'required'],
            'event' => ['string', 'required'],
            'object' => ['array', 'required'],
        ];
    }
}
