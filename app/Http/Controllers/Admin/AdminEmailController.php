<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminUserBroadcastMail;
use App\Models\AdminUserMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AdminEmailController extends Controller
{
    public function create()
    {
        $users = User::query()
            ->orderByRaw("CASE WHEN role = 'admin' THEN 0 ELSE 1 END")
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'role']);

        $usersForPicker = $users->map(fn (User $user) => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email ?? '',
            'role' => $user->role,
            'is_admin' => $user->role === 'admin',
        ])->values();

        $allRecipientCount = $users->filter(
            fn (User $user) => filled($user->email)
        )->count();

        $selectedUserIds = collect(old('user_ids', []))->map(fn ($id) => (int) $id)->all();

        return view('admin.email-users', compact('usersForPicker', 'allRecipientCount', 'selectedUserIds'));
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:50000'],
            'recipient_mode' => ['required', 'in:all,selected'],
            'user_ids' => ['required_if:recipient_mode,selected', 'array', 'min:1'],
            'user_ids.*' => ['integer', 'exists:users,id'],
        ]);

        if ($validated['recipient_mode'] === 'all') {
            $recipients = User::query()
                ->whereNotNull('email')
                ->where('email', '!=', '')
                ->get();
        } else {
            $recipients = User::query()
                ->whereIn('id', $validated['user_ids'])
                ->whereNotNull('email')
                ->where('email', '!=', '')
                ->get();
        }

        if ($recipients->isEmpty()) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'No recipients with a valid email address were found.');
        }

        $sent = 0;
        $failed = 0;

        foreach ($recipients as $user) {
            AdminUserMessage::create([
                'user_id' => $user->id,
                'sent_by' => Auth::id(),
                'subject' => $validated['subject'],
                'body' => $validated['body'],
            ]);

            try {
                Mail::to($user->email)->send(
                    new AdminUserBroadcastMail(
                        $validated['subject'],
                        $validated['body'],
                        (string) $user->name
                    )
                );
                $sent++;
            } catch (\Throwable $e) {
                $failed++;
                Log::error('Admin broadcast email failed', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        $message = "Email sent successfully to {$sent} recipient(s).";
        if ($failed > 0) {
            $message .= " {$failed} message(s) could not be sent; check the application log.";
        }

        return redirect()
            ->route('admin.email-users.create')
            ->with($failed > 0 ? 'info' : 'success', $message);
    }
}
