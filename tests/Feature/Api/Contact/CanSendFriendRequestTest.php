<?php

use App\Models\User;
use Illuminate\Http\Response;

test('it should be able to send a friend request', function () {
    $users = User::factory()->count(2)->create();

    $response = $this->actingAs($users->first())->postJson(route('api.contacts.store'), [
        'user_id' => $users->last()->id,
    ]);

    $response
        ->assertStatus(Response::HTTP_CREATED);

    $this->assertDatabaseHas('contacts', [
        'user_id' => $users->first()->id,
        'contact_id' => $users->last()->id,
        'status' => 'pending',
    ]);

    $this->assertDatabaseCount('contacts', 1);
});
