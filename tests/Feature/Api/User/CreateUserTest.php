<?php

use App\Models\User;
use Illuminate\Http\Response;

test('it should be able to create a user', function () {
    $payload = User::factory()->make()->only(['name', 'email']);
    $payload['password'] = 'L@rav3l1!';
    $payload['password_confirmation'] = 'L@rav3l1!';

    $response = $this->postJson(route('api.users.store'), $payload);

    $response
        ->assertStatus(Response::HTTP_CREATED)
        ->assertJsonStructure(['id', 'name', 'email']);

    unset($payload['password']);
    unset($payload['password_confirmation']);
    $this->assertDatabaseHas('users', $payload);
    $this->assertDatabaseCount('users', 1);
});
