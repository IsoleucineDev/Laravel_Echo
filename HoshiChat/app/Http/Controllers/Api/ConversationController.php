<?php

namespace App\Http\Controllers\Api;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConversationController extends Controller
{
    public function index()
    {
        $conversations = Conversation::with(['users', 'messages'])->get();
        return response()->json(['data' => $conversations]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $conversation = Conversation::create($validated);
        $conversation->users()->attach($request->user()->id);
        
        return response()->json(['data' => $conversation], 201);
    }

    public function show(Conversation $conversation)
    {
        $conversation->load(['users', 'messages.user']);
        return response()->json(['data' => $conversation]);
    }

    public function update(Request $request, Conversation $conversation)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string'
        ]);

        $conversation->update($validated);
        
        return response()->json(['data' => $conversation]);
    }

    public function destroy(Conversation $conversation)
    {
        $conversation->delete();
        return response()->json(null, 204);
    }
}
