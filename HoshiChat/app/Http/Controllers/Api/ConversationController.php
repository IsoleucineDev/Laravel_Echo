<?php

namespace App\Http\Controllers\Api;

use App\Events\ConversationUpdated;
use App\Events\UserJoinedConversation;
use App\Events\UserLeftConversation;
use App\Http\Controllers\Controller;
use App\Http\Resources\ConversationResource;
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

        return ConversationResource::collection($conversations);
    }

    /**
     *Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $validated['created_by'] = auth()->id();

        $conversation = Conversation::create($validated);

		return response()->json([
    		'data' => new ConversationResource($conversation->load('users', 'messages')),
		], Response::HTTP_CREATED);

    }

    /**
     * Display the specified resource.
     */
    public function show(Conversation $conversation)
    {
        $conversation->load('users', 'messages.user');

        return new ConversationResource($conversation);
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

        return new ConversationResource($conversation);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Conversation $conversation)
    {
        $conversation->delete();

        return response()->json(['message' => 'Conversation deleted successfully']);
    }

    /**
     * Add a user to a conversation.
     */
    public function addUser(Request $request, Conversation $conversation)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        foreach ($validated['user_ids'] as $userId) {
            if (!$conversation->users()->where('user_id', $userId)->exists()) {
                $conversation->users()->attach($userId);
                $user = User::find($userId);
                UserJoinedConversation::dispatch($user, $conversation);
            }
        }

        return response()->json(['message' => 'Users added successfully']);
    }

    /**
     * Remove a user from a conversation.
     */
    public function removeUser(Request $request, Conversation $conversation)
    {
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        foreach ($validated['user_ids'] as $userId) {
            if ($conversation->users()->where('user_id', $userId)->exists()) {
                $conversation->users()->detach($userId);
                $user = User::find($userId);
                UserLeftConversation::dispatch($user, $conversation);
            }
        }

        return response()->json(['message' => 'Users removed successfully']);
    }
}
