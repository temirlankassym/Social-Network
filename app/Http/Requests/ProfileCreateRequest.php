<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileCreateRequest extends FormRequest
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
//        return [
//            'bio' => 'required|max:255|unique:users,username',
//            'email' => 'required|max:255|unique:users,email',
//            "password" => "required|string|max:255",
//            "password_repeat" => "required|string|max:255|same:password"
//        ];
        return [];
    }
}
