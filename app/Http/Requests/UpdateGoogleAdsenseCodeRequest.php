<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGoogleAdsenseCodeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->type == 'admin' && $this->user()->id == $this->code?->user_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['sometimes', 'string', 'min:1'],
            'code' => ['sometimes', 'string', 'min:1'],
            'status' => ['sometimes', 'in:activated,deactivated']
        ];
    }
}
