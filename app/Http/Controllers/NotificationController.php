<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifs = Notification::where('tipe_penerima', 'user')
            ->where('penerima_id', Auth::id())
            ->latest()
            ->paginate(20);

        return view('shared.notifikasi.index', compact('notifs'));
    }

    public function markRead(int $id)
    {
        Notification::where('id', $id)
            ->where('tipe_penerima', 'user')
            ->where('penerima_id', Auth::id())
            ->update(['is_read' => true]);

        return back();
    }

    public function markAllRead()
    {
        Notification::where('tipe_penerima', 'user')
            ->where('penerima_id', Auth::id())
            ->update(['is_read' => true]);

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}