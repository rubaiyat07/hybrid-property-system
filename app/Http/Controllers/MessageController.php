<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        // Basic messages index
        return view('messages.index');
    }

    public function store(Request $request)
    {
        // Store message
        return response()->json(['success' => true]);
    }

    public function conversation($userId)
    {
        // Conversation with user
        return view('messages.conversation', compact('userId'));
    }
}
