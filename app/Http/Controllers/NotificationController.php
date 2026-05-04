<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markAsRead(Request $request, Notification $notification)
    {
        if ($notification->user_id !== $request->user()->id) {
            abort(403);
        }

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function redirectToReference(Request $request, Notification $notification)
    {
        if ($notification->user_id !== $request->user()->id) {
            abort(403);
        }

        $notification->markAsRead();

        if ($notification->reference_type === 'gadai' && $notification->reference_id) {
            $route = route($request->user()->role . '.approval.gadai.show', $notification->reference_id);
        } else {
            $route = route($request->user()->role . '.approval.gadai');
        }

        return redirect()->to($route);
    }
}
