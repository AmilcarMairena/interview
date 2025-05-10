<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DateValidatorRequest extends FormRequest
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
            'fechainit' => 'required|date_format:d/m/Y',
            'fechafin' => 'required|date_format:d/m/Y|after:initial_date',
        ];
    }
}
