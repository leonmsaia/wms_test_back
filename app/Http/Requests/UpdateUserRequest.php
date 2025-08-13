<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates the payload for updating a User.
 *
 * @author  Leon. M. Saia
 * @since   2025-08-12
 */
class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    /**
     * Validation rules for user updates.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $id = (int) $this->route('id');

        return [
            'nombre'   => ['sometimes','required','string','min:3'],
            'apellido' => ['sometimes','required','string','min:3'],
            'email'    => ['sometimes','required','email',"unique:users,email,{$id}"],
            'rol'      => ['sometimes','required','in:Admin,User'],
            'password' => ['sometimes','required','min:8'],
        ];
    }
}
