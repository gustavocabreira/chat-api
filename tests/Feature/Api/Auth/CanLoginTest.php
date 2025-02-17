<?php

use App\Models\User;
use Illuminate\Http\Response;

test('it should be able to login', function () {
    $user = User::factory()->create(['password' => 'L@rav3l1!']);

    $response = $this->postJson(route('api.auth.login'), [
        'email' => $user->email,
        'password' => 'L@rav3l1!',
    ]);

    $response
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure(['access_token', 'token_type']);

    $this->assertDatabaseHas('users', $user->toArray());
});
