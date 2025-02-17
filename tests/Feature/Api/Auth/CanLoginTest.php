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

dataset('invalid_payload', [
    'empty email' => [
        ['email' => ''],
        ['email' => 'The email field is required.'],
    ],
    'empty password' => [
        ['password' => ''],
        ['password' => 'The password field is required.'],
    ],
    'invalid email' => [
        ['email' => 'not an email', 'password' => 'L@rav3l1!'],
        ['email' => 'The email field must be a valid email address.'],
    ],
]);

test('it should return unprocessable entity when trying to login with an invalid payload', function ($payload, $expectedErrors) {
    $response = $this->postJson(route('api.auth.login'), $payload);

    $response
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors($key = array_keys($expectedErrors));

    $response->assertJsonPath("errors.$key[0].0", $expectedErrors[$key[0]]);
})->with('invalid_payload');
