<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMahasiswaRequest extends FormRequest
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
            'nim' => 'required',
            'nama' => 'required',
            'semester' => 'required',
            'sks' => 'required|integer|min:110',
        ];
    }

    public function messages(): array
    {
        return [
            'nim.required' => 'Kolom NIM harus diisi.',
            'nama.required' => 'Kolom nama harus diisi.',
            'semester.required' => 'Kolom semester harus diisi.',
            'sks.required' => 'Kolom SKS harus diisi.',
            'sks.integer' => 'Kolom SKS harus berupa angka.',
            'sks.min' => 'SKS minimal harus 110.',
        ];
    }
}
