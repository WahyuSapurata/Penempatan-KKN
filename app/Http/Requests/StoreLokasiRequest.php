<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLokasiRequest extends FormRequest
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
            'lokasi' => 'required',
            'kuota' => 'required',
            'jarak' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'lokasi.required' => 'Kolom lokasi harus di isi.',
            'kuota.required' => 'Kolom kuota harus di isi.',
            'jarak.required' => 'Kolom jarak harus di isi.',
        ];
    }
}
