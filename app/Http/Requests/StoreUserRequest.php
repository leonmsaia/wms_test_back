<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates the payload for creating a User.
 *
 * @author  Leon. M. Saia
 * @since   2025-08-12
 */
class StoreUserRequest extends FormRequest
{
    /**
     * Authorization is handled at controller/policy level.
     *
     * @return bool
     */
    public function authorize(): bool { return true; }

    /**
     * Validation rules for user creation.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'nombre'   => ['required','string','min:3'],
            'apellido' => ['required','string','min:3'],
            'email'    => ['required','email','unique:users,email'],
            'rol'      => ['required','in:Admin,User'],
            'password' => ['required','min:8'],
        ];
    }
}
