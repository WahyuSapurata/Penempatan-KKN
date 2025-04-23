<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKriteriaRequest extends FormRequest
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
            'nama_kriteria' => 'required',
            'bobot' => 'required',
            'jenis' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'nama_kriteria.required' => 'Kolom nama kriteria harus di isi.',
            'bobot.required' => 'Kolom bobot harus di isi.',
            'jenis.required' => 'Kolom jenis harus di isi.',
        ];
    }
}
