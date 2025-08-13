<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * RESTful controller for User CRUD with filters and pagination.
 *
 * Endpoint summary:
 *  - GET    /api/usuarios        (filters: role, q; pagination: ?page=1)
 *  - POST   /api/usuarios
 *  - PUT    /api/usuarios/{id}
 *  - DELETE /api/usuarios/{id}
 *
 * @author  Leon. M. Saia
 * @since   2025-08-12
 */
class UserController extends Controller
{
    public function __construct(private UserService $service) {}

    /**
     * Returns a paginated list of users with optional filters.
     *
     * @param  Request  $request
     * @return UserCollection
     */
    public function index(Request $request): UserCollection
    {
        $role = $request->query('role');
        $q    = $request->query('q');

        $pageSize = (int) ($request->query('per_page', 10));
        $data = $this->service->list($role, $q, $pageSize);

        return new UserCollection($data);
    }

    /**
     * Creates a new user.
     *
     * @param  StoreUserRequest  $request
     * @return JsonResponse
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $payload = $request->validated();

        if (!empty($payload['password'])) {
            $payload['password'] = Hash::make($payload['password']);
        }

        $user = User::create($payload);

        return (new UserResource($user))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Updates an existing user.
     *
     * @param  UpdateUserRequest  $request
     * @param  int                $id
     * @return UserResource
     */
    public function update(UpdateUserRequest $request, int $id): UserResource
    {
        $user = User::findOrFail($id);
        $payload = $request->validated();

        if (!empty($payload['password'])) {
            $payload['password'] = Hash::make($payload['password']);
        }

        $user->update($payload);

        return new UserResource($user);
    }

    /**
     * Deletes a user by id.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json()->setStatusCode(204);
    }
}
