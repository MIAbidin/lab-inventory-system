<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInventoryItemRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'serial_number' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('inventory_items')->ignore($this->inventory)
            ],
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'condition' => 'required|in:good,need_repair,broken',
            'status' => 'required|in:available,in_use,maintenance,disposed',
            'location' => 'nullable|string|max:255',
            'specifications' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'notes' => 'nullable|string'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama item harus diisi.',
            'category_id.required' => 'Kategori harus dipilih.',
            'category_id.exists' => 'Kategori tidak valid.',
            'brand.required' => 'Merk harus diisi.',
            'model.required' => 'Model harus diisi.',
            'serial_number.unique' => 'Serial number sudah digunakan.',
            'condition.required' => 'Kondisi harus dipilih.',
            'status.required' => 'Status harus dipilih.',
            'purchase_price.numeric' => 'Harga harus berupa angka.',
            'purchase_price.min' => 'Harga tidak boleh kurang dari 0.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpeg, png, atau jpg.',
            'image.max' => 'Ukuran gambar maksimal 2MB.'
        ];
    }
}