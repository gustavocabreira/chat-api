<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserContactController extends Controller
{
    public function sendFriendRequest(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $request->user()->contacts()->attach($validated['user_id'], ['status' => 'pending']);

        return response()->json(null, Response::HTTP_CREATED);
    }
}
