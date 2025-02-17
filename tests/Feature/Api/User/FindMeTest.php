<?php

use App\Models\User;
use Illuminate\Http\Response;

test('it should be able to find the current user', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->getJson(route('api.users.me'));

    $response
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure(['id', 'name', 'email']);

    $this->assertDatabaseHas('users', $user->toArray());
});
