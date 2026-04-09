<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MessageController extends Controller
{
    /**
     * Display a listing of messages for a conversation.
     */
    public function index(Conversation $conversation)
    {
        $messages = $conversation->messages()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return response()->json($messages);
    }

    /**
     * Store a newly created message in storage.
     */
    public function store(Request $request, Conversation $conversation)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:5000',
        ]);

        $message = $conversation->messages()->create([
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        $message->load('user');

        MessageSent::dispatch($message);

        return response()->json($message, Response::HTTP_CREATED);
    }

    /**
     * Display the specified message.
     */
    public function show(Conversation $conversation, Message $message)
    {
        if ($message->conversation_id !== $conversation->id) {
            return response()->json(['message' => 'Message not found in this conversation'], Response::HTTP_NOT_FOUND);
        }

        $message->load('user');

        return response()->json($message);
    }

    /**
     * Update the specified message in storage.
     */
    public function update(Request $request, Conversation $conversation, Message $message)
    {
        if ($message->conversation_id !== $conversation->id) {
            return response()->json(['message' => 'Message not found in this conversation'], Response::HTTP_NOT_FOUND);
        }

        if ($message->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], Response::HTTP_FORBIDDEN);
        }

        $validated = $request->validate([
            'content' => 'required|string|max:5000',
        ]);

        $message->update($validated);

        return response()->json($message);
    }

    /**
     * Remove the specified message from storage.
     */
    public function destroy(Conversation $conversation, Message $message)
    {
        if ($message->conversation_id !== $conversation->id) {
            return response()->json(['message' => 'Message not found in this conversation'], Response::HTTP_NOT_FOUND);
        }

        if ($message->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], Response::HTTP_FORBIDDEN);
        }

        $message->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
