<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        // Basic notifications index
        return view('notifications.index');
    }

    public function markAsRead($id)
    {
        // Mark notification as read
        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        // Mark all as read
        return response()->json(['success' => true]);
    }
}
