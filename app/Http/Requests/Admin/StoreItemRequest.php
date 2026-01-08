<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:1',
            'image' => 'nullable|image|max:2048',
            'is_featured' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'لازم تختار قسم للطبق يا الغالي.',
            'category_id.exists' => 'القسم اللي اخترته مو موجود.',
            'name.required' => 'اسم الطبق ضروري، لا تنساه.',
            'name.max' => 'اسم الطبق طويل مررره، لا يزيد عن 255 حرف.',
            'price.required' => 'السعر لازم تحطه.',
            'price.numeric' => 'السعر لازم يكون رقم.',
            'price.min' => 'السعر لازم يكون فوق الصفر.',
            'image.image' => 'الملف لازم يكون صورة (jpg, png, ...).',
            'image.max' => 'حجم الصورة كبير، لازم يكون أقل من 2 ميجا.',
        ];
    }
}
