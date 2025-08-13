<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Transforms a single User model into API-friendly shape.
 * 
 * @mixin \App\Models\User
 * @author  Leon. M. Saia
 * @since   2025-08-12
 */
class UserResource extends JsonResource
{
    /**
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'nombre'     => $this->nombre,
            'apellido'   => $this->apellido,
            'email'      => $this->email,
            'rol'        => $this->rol,
            'created_at' => $this->created_at,
        ];
    }
}
