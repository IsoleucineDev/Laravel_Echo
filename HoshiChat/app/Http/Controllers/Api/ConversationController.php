<?php

namespace App\Http\Controllers\Api;

use App\Events\ConversationUpdated;
use App\Events\UserJoinedConversation;
use App\Events\UserLeftConversation;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $conversations = Conversation::with('users', 'messages')
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return response()->json($conversations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $conversation = Conversation::create($validated);

        return response()->json($conversation, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Conversation $conversation)
    {
        $conversation->load('users', 'messages.user');

        return response()->json($conversation);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Conversation $conversation)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string|max:1000',
        ]);

        $conversation->update($validated);

        ConversationUpdated::dispatch($conversation);

        return response()->json($conversation);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Conversation $conversation)
    {
        $conversation->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Add a user to a conversation.
     */
    public function addUser(Request $request, Conversation $conversation)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::find($validated['user_id']);

        if ($conversation->users()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'User already in conversation'], Response::HTTP_CONFLICT);
        }

        $conversation->users()->attach($user);

        UserJoinedConversation::dispatch($user, $conversation);

        return response()->json(['message' => 'User added successfully']);
    }

    /**
     * Remove a user from a conversation.
     */
    public function removeUser(Request $request, Conversation $conversation)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::find($validated['user_id']);

        if (!$conversation->users()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'User not in conversation'], Response::HTTP_NOT_FOUND);
        }

        $conversation->users()->detach($user);

        UserLeftConversation::dispatch($user, $conversation);

        return response()->json(['message' => 'User removed successfully']);
    }
}
