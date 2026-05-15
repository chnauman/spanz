<?php

namespace App\Http\Controllers;

use App\Models\AdminUserMessage;
use App\Models\UserTenderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserNotificationController extends Controller
{
    public function rfxReceived(Request $request)
    {
        $user = Auth::user();

        $query = UserTenderNotification::query()
            ->where('user_id', $user->id)
            ->with(['tender.category', 'tender.user'])
            ->orderByDesc('created_at');

        if ($request->get('filter') === 'unread') {
            $query->whereNull('read_at');
        }

        $notifications = $query->paginate(15)->withQueryString();

        return view('user.rfx-received', compact('notifications'));
    }

    public function markRfxRead(UserTenderNotification $notification)
    {
        $this->authorizeNotification($notification);

        if ($notification->read_at === null) {
            $notification->update(['read_at' => now()]);
        }

        return redirect()->back();
    }

    public function markAllRfxRead()
    {
        UserTenderNotification::query()
            ->where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return redirect()->back()->with('success', 'All RFX notifications marked as read.');
    }

    public function messages(Request $request)
    {
        $user = Auth::user();

        $query = AdminUserMessage::query()
            ->where('user_id', $user->id)
            ->orderByDesc('created_at');

        if ($request->get('filter') === 'unread') {
            $query->whereNull('read_at');
        }

        $messages = $query->paginate(15)->withQueryString();

        return view('user.admin-messages', compact('messages'));
    }

    public function showMessage(AdminUserMessage $message)
    {
        if ($message->user_id !== Auth::id()) {
            abort(403);
        }

        if ($message->read_at === null) {
            $message->update(['read_at' => now()]);
        }

        return view('user.admin-message-show', compact('message'));
    }

    public function markAllMessagesRead()
    {
        AdminUserMessage::query()
            ->where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return redirect()->back()->with('success', 'All messages marked as read.');
    }

    private function authorizeNotification(UserTenderNotification $notification): void
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }
    }
}
