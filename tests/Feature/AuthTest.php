<?php

/** @noinspection PhpUndefinedFunctionInspection */
/** @noinspection PhpUndefinedMethodInspection */

/**
 * @see https://pestphp.com/docs/quick-start
 */

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('registers a new user', function () {
    $payload = [
        'nombre'   => 'Admin',
        'apellido' => 'Demo',
        'email'    => 'admin@demo.com',
        'password' => 'secret1234',
        'rol'      => 'Admin',
    ];

    $res = $this->postJson('/api/auth/register', $payload);
    $res->assertCreated()->assertJsonPath('user.email', 'admin@demo.com');
});

it('logs in and returns a token', function () {
    User::factory()->create([
        'email'    => 'admin@demo.com',
        'password' => Hash::make('secret1234'),
        'rol'      => 'Admin',
    ]);

    $res = $this->postJson('/api/auth/login', [
        'email'    => 'admin@demo.com',
        'password' => 'secret1234',
    ]);

    $res->assertOk()->assertJsonStructure(['access_token', 'token_type']);
});

it('returns current user with sanctum token', function () {
    $user = User::factory()->create(['rol' => 'Admin']);
    Sanctum::actingAs($user);

    $this->getJson('/api/auth/me')
        ->assertOk()
        ->assertJsonPath('id', $user->id);
});

it('logs out and revokes the token', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $this->postJson('/api/auth/logout')
        ->assertOk()
        ->assertJson(['message' => 'logged_out']);
});
