<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('admin') === true;
    }

    public function rules(): array
    {
        $userId = $this->route('user')->id ?? null;

        return [
            'name'       => ['required','string','max:255'],
            'email'      => ['required','email','max:255', Rule::unique('users','email')->ignore($userId)],
            'public_id'  => ['required','string','max:20', Rule::unique('users','public_id')->ignore($userId), 'regex:/^SVX-U\d{7}$/'],
            'password'   => ['nullable','string','min:8'], // nur wenn setzen
        ];
    }
}
