<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageDeleted;
use App\Events\MessageSent;
use App\Events\MessageUpdated;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MessageController extends Controller
{
    public function index(Conversation $conversation)
    {
        $messages = $conversation->messages()->with('user')->paginate(10);
        return response()->json($messages);
    }

    public function store(Request $request, Conversation $conversation)
    {
        $validated = $request->validate([
            'content' => 'required|string'
        ]);

        $message = $conversation->messages()->create([
            'user_id' => $request->user()->id,
            'content' => $validated['content']
        ]);

        $message->load('user');

        MessageSent::dispatch($message);

        return response()->json(['data' => $message], 201);
    }

    public function update(Request $request, Conversation $conversation, Message $message)
    {
        $validated = $request->validate([
            'content' => 'required|string'
        ]);

        $message->update($validated);
        $message->load('user');

        MessageUpdated::dispatch($message);

        return response()->json(['data' => $message]);
    }

    public function destroy(Conversation $conversation, Message $message)
    {
        $messageId = $message->id;
        $conversationId = $message->conversation_id;

        $message->delete();

        MessageDeleted::dispatch($messageId, $conversationId);

        return response()->json(null, 204);
    }
}
