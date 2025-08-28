<?php
// app/Http/Requests/StoreCategoryRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
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
        $categoryId = $this->route('category') ? $this->route('category')->id : null;

        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',
                Rule::unique('categories', 'name')->ignore($categoryId),
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    /**
     * Get custom validation error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori harus diisi.',
            'name.string' => 'Nama kategori harus berupa teks.',
            'name.min' => 'Nama kategori minimal :min karakter.',
            'name.max' => 'Nama kategori maksimal :max karakter.',
            'name.unique' => 'Nama kategori sudah digunakan. Silakan pilih nama lain.',
            'description.string' => 'Deskripsi harus berupa teks.',
            'description.max' => 'Deskripsi maksimal :max karakter.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nama kategori',
            'description' => 'deskripsi',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        // Add custom logic here if needed
        parent::failedValidation($validator);
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator(\Illuminate\Validation\Validator $validator): void
    {
        $validator->after(function ($validator) {
            // Additional custom validation logic
            if ($this->filled('name')) {
                // Check if name contains only allowed characters
                if (!preg_match('/^[a-zA-Z0-9\s\-\_\.\,\(\)\&]+$/u', $this->name)) {
                    $validator->errors()->add('name', 'Nama kategori hanya boleh mengandung huruf, angka, spasi, dan karakter khusus tertentu (- _ . , ( ) &).');
                }

                // Check for reserved keywords
                $reservedKeywords = ['admin', 'system', 'root', 'null', 'undefined'];
                if (in_array(strtolower($this->name), $reservedKeywords)) {
                    $validator->errors()->add('name', 'Nama kategori tidak boleh menggunakan kata yang sudah direservasi sistem.');
                }
            }

            // Validate description content
            if ($this->filled('description')) {
                // Check for potentially harmful content (basic check)
                $suspiciousPatterns = ['<script', 'javascript:', 'onload=', 'onclick='];
                foreach ($suspiciousPatterns as $pattern) {
                    if (stripos($this->description, $pattern) !== false) {
                        $validator->errors()->add('description', 'Deskripsi mengandung konten yang tidak diizinkan.');
                        break;
                    }
                }
            }
        });
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        // Clean and prepare data before validation
        $this->merge([
            'name' => $this->name ? trim($this->name) : null,
            'description' => $this->description ? trim($this->description) : null,
        ]);

        // Remove empty description
        if ($this->description === '') {
            $this->merge([
                'description' => null
            ]);
        }
    }

    /**
     * Get validated data with additional processing.
     *
     * @return array
     */
    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);

        // Additional processing of validated data
        if (isset($validated['name'])) {
            // Standardize name format
            $validated['name'] = ucwords(strtolower($validated['name']));
        }

        if (isset($validated['description']) && $validated['description']) {
            // Clean description
            $validated['description'] = strip_tags($validated['description']);
        }

        return $validated;
    }
}