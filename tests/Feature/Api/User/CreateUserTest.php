<?php

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

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

dataset('invalid_payload', [
    'empty name' => [
        ['name' => ''],
        ['name' => 'The name field is required.'],
    ],
    'empty email' => [
        ['email' => ''],
        ['email' => 'The email field is required.'],
    ],
    'empty password' => [
        ['password' => ''],
        ['password' => 'The password field is required.'],
    ],
    'name with more than 255 characters' => [
        ['name' => Str::repeat('*', 256)],
        ['name' => 'The name field must not be greater than 255 characters.'],
    ],
    'invalid email' => [
        ['email' => Str::repeat('*', 255)],
        ['email' => 'The email field must be a valid email address.'],
    ],
    'email already exists' => function() {
        User::factory()->create(['email' => 'test@test.com']);

        return [
            ['email' => 'test@test.com'],
            ['email' => 'The email has already been taken.'],
        ];
    },
    'password with less than or equal to 8' => [
        ['password' => '1234567', 'password_confirmation' => '1234567'], ['password' => 'The password field must be at least 8 characters.'],
    ],
    'password with no letters' => [
        ['password' => '12345678', 'password_confirmation' => '12345678'], ['password' => 'The password field must contain at least one uppercase and one lowercase letter.'],
    ],
    'password with no mixed case' => [
        ['password' => '12a45678', 'password_confirmation' => '12a45678'], ['password' => 'The password field must contain at least one uppercase and one lowercase letter.'],
    ],
    'password with no symbols' => [
        ['password' => 'aA12345678', 'password_confirmation' => 'aA12345678'], ['password' => 'The password field must contain at least one symbol.'],
    ],
    'password confirmation does not match' => [
        ['name' => fake()->name, 'email' => fake()->email, 'password' => 'aA@1234567', 'password_confirmation' => 'aA@123456'], ['password' => 'The password field confirmation does not match.'],
    ],
]);

test('it should return unprocessable entity when trying to create a user with an invalid payload', function ($payload, $expectedErrors) {
    $count = User::query()->count();

    $response = $this->postJson(route('api.users.store'), $payload);

    $response
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors($key = array_keys($expectedErrors));

    $response->assertJsonPath("errors.$key[0].0", $expectedErrors[$key[0]]);

    $this->assertDatabaseCount('users', $count);
})->with('invalid_payload');
