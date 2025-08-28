<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryItemRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Pastikan ini return true
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255|unique:inventory_items,serial_number',
            'location' => 'nullable|string|max:255',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'condition' => 'required|in:good,need_repair,broken',
            'status' => 'required|in:available,in_use,maintenance,disposed',
            'specifications' => 'nullable|string',
            'notes' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // max 2MB
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama item wajib diisi.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'brand.required' => 'Brand/merk wajib diisi.',
            'model.required' => 'Model/tipe wajib diisi.',
            'serial_number.unique' => 'Serial number sudah digunakan.',
            'condition.required' => 'Kondisi wajib dipilih.',
            'status.required' => 'Status wajib dipilih.',
            'image.image' => 'File harus berupa gambar.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}