<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRolesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('admin') === true;
    }

    public function rules(): array
    {
        return [
            'roles'   => ['array'],
            'roles.*' => ['string','in:admin,user'], // erweitern, falls mehr Rollen
        ];
    }
}
