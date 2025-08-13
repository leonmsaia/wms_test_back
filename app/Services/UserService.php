<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Encapsulates User listing logic: filters and pagination.
 *
 * Filters:
 *  - role: exact match on 'rol'
 *  - q: partial search across 'nombre' and 'apellido'
 *
 * @author  Leon. M. Saia <leonmsaia@gmail.com>
 * @since   2025-08-12
 */
class UserService
{
    /**
     * Returns a paginated and filtered list of users.
     *
     * @param  string|null  $role  Exact role filter (Admin|User)
     * @param  string|null  $q     Partial search on nombre/apellido
     * @param  int          $perPage Items per page (default 10)
     * @return LengthAwarePaginator
     */
    public function list(?string $role, ?string $q, int $perPage = 10): LengthAwarePaginator
    {
        return User::query()
            ->when($role, fn($qb) => $qb->where('rol', $role))
            ->when($q, fn($qb) => $qb->where(function ($qq) use ($q) {
                $qq->where('nombre', 'like', "%{$q}%")
                   ->orWhere('apellido', 'like', "%{$q}%");
            }))
            ->orderByDesc('id')
            ->paginate($perPage);
    }
}
