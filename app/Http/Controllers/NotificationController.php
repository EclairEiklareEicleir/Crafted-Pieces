<?php

namespace App\Http\Controllers;

use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->take(20)
            ->get();

        return view('user.notifications.index', compact('notifications'));
    }
    public function show(Notification $notification)
    {
        abort_unless($notification->user_id === auth()->id(), 403);

        $notification->markAsRead();

        return redirect($notification->link ?? route('home'));
    }
}