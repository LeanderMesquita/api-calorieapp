<?php

namespace App\Http\Requests;

use App\Rules\EntriesLimit;
use Illuminate\Foundation\Http\FormRequest;

class EntryStoreRequest extends FormRequest
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
            'food_name' => 'required|string|max:255',
            'calories' => 'required|integer|min:0',
            'meal_id' => ['required', 'exists:meals,id', new EntriesLimit()]
        ];
    }
}
