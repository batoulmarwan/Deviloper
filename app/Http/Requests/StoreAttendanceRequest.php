<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceRequest extends FormRequest
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
            'gps_lat' => 'required|numeric',
            'gps_lng' => 'required|numeric',
            'check_type' => 'required|in:IN,OUT',
            'photo_url' => 'nullable|image|max:2048',
        ];
    }
}
