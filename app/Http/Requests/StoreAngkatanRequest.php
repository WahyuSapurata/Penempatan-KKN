<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAngkatanRequest extends FormRequest
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
            'angkatan' => 'required',
            'status' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'angkatan.required' => 'Kolom angkatan harus di isi.',
            'status.required' => 'Kolom status harus di isi.',
        ];
    }
}
