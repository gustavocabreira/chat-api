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

dataset('invalid_payload', [
    'empty name' => [
        ['name' => ''],
        ['name' => 'The name field is required.'],
    ],
    'empty email' => [
        ['email' => ''],
        ['email' => 'The email field is required.'],
    ],
    'invalid email' => [
        ['email' => 'not an email'],
        ['email' => 'The email field must be a valid email address.'],
    ],
    'email already exists' => function () {
        User::factory()->create(['email' => 'test@test.com']);

        return [
            ['email' => 'test@test.com'],
            ['email' => 'The email has already been taken.'],
        ];
    },
]);

test('it should return unprocessable entity when trying to update the user profile with an invalid payload', function ($payload, $expectedErrors) {
    $user = User::factory()->create();
    $count = User::query()->count();

    $response = $this->actingAs($user)->putJson(route('api.users.update'), $payload);

    $response
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors($key = array_keys($expectedErrors));

    $response->assertJsonPath("errors.$key[0].0", $expectedErrors[$key[0]]);

    $this->assertDatabaseHas('users', $user->toArray());
    $this->assertDatabaseCount('users', $count);
})->with('invalid_payload');
