<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

function admin(): User {
    return User::factory()->create([
        'rol' => 'Admin',
        'password' => Hash::make('secret1234'),
    ]);
}

function regular(): User {
    return User::factory()->create([
        'rol' => 'User',
        'password' => Hash::make('secret1234'),
    ]);
}

it('requires auth to list users', function () {
    $this->getJson('/api/usuarios')->assertUnauthorized();
});

it('admin can list users with filters and pagination', function () {
    Sanctum::actingAs(admin());

    $this->getJson('/api/usuarios?role=User&q=a&page=1&per_page=10')
        ->assertOk()
        ->assertJsonStructure(['data', 'meta']);
});

it('admin can create user', function () {
    Sanctum::actingAs(admin());

    $payload = [
        'nombre'   => 'Juana',
        'apellido' => 'García',
        'email'    => 'juana@example.com',
        'rol'      => 'User',
        'password' => 'secret1234',
    ];

    $this->postJson('/api/usuarios', $payload)
        ->assertCreated()
        ->assertJsonPath('data.email', 'juana@example.com');
});

it('user cannot create user (403)', function () {
    Sanctum::actingAs(regular());

    $this->postJson('/api/usuarios', [
        'nombre' => 'Test', 'apellido' => 'User',
        'email' => 't@t.com', 'rol' => 'User', 'password' => 'secret1234'
    ])->assertForbidden();
});

it('admin can update user', function () {
    Sanctum::actingAs(admin());
    $u = User::factory()->create(['rol' => 'User']);

    $this->putJson("/api/usuarios/{$u->id}", ['apellido' => 'García López'])
        ->assertOk()
        ->assertJsonPath('data.apellido', 'García López');
});

it('admin can delete user', function () {
    Sanctum::actingAs(admin());
    $u = User::factory()->create(['rol' => 'User']);

    $this->deleteJson("/api/usuarios/{$u->id}")
        ->assertNoContent();

    $this->assertDatabaseMissing('users', ['id' => $u->id]);
});
