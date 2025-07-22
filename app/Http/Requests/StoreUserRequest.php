<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'name'=> "required|string|max:30",
            'email'=> "required|email|unique:users,email",
            'grade_level_id'=> "required|exists:gradelevels,id",
            'gender' => "required|in:male,female ",
            'password'=> "required|min:6|string",
        ];
    }
}
