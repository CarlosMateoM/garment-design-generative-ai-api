<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQualityIndicatorsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'garmentDesignId' => 'required|integer|exists:garment_designs,id',
            'creativity' => 'required|integer|min:1|max:5',
            'originality' => 'required|integer|min:1|max:5',
            'texture' => 'required|integer|min:1|max:5',
            'stylistics' => 'required|integer|min:1|max:5',
            'functionality' => 'required|integer|min:1|max:5',
            'feasibility' => 'required|integer|min:1|max:5',
            'feedback' => 'required|string'
        ];
    }
}
