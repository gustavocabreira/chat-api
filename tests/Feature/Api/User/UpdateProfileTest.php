<?php

use App\Models\User;
use Illuminate\Http\Response;

test('it should be able to update the user profile', function () {
    $user = User::factory()->create();

    $payload = [
        'name' => 'Test User',
        'email' => 'test@test.com',
    ];

    $response = $this->actingAs($user)->putJson(route('api.users.update'), $payload);

    $response
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure(['id', 'name', 'email']);

    $this->assertDatabaseHas('users', $payload);
    $this->assertDatabaseCount('users', 1);
});
