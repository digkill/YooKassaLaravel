<?php

namespace Digkill\YooKassaLaravel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 *
 */
final class IndexRequest extends FormRequest
{
    /**
     * authorize
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * rules
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
