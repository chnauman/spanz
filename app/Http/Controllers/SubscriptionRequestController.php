<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\SubscriptionRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Jobs\SendSubscriptionRequestJob;

class SubscriptionRequestController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::where('is_active', true)
            ->where('name', '!=', 'Basic')
            ->get();

        return view('subscriptions.index', compact('subscriptions'));
    }

    public function request(Subscription $subscription)
    {
        $user = auth()->user();

        // Check if user already has a pending request for this subscription
        $existingRequest = SubscriptionRequest::where('user_id', $user->id)
            ->where('subscription_id', $subscription->id)
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You already have a pending request for this subscription.'
                ]);
            }
            return redirect()->back()
                ->with('info', 'You already have a pending request for this subscription.');
        }

        // Check if user already has this subscription
        if ($user->getActiveSubscription() && $user->getActiveSubscription()->subscription_id === $subscription->id) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You already have this subscription.'
                ]);
            }
            return redirect()->back()
                ->with('info', 'You already have this subscription.');
        }

        // Create subscription request
        $request = SubscriptionRequest::create([
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'requested_at' => now(),
        ]);

        // Dispatch job to send email to admin
        SendSubscriptionRequestJob::dispatch($request, 'new_request');

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Subscription request submitted successfully! Admin has been notified.'
            ]);
        }

        return redirect()->back()
            ->with('success', 'Subscription request submitted successfully! You will be notified once it\'s processed.');
    }

    public function myRequests()
    {
        $requests = auth()->user()->subscriptionRequests()
            ->with('subscription')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('subscriptions.my-requests', compact('requests'));
    }

    public function cancelRequest(SubscriptionRequest $request)
    {
        // Only allow cancellation of pending requests
        if ($request->user_id !== auth()->id() || $request->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Cannot cancel this request.');
        }

        $request->delete();

        return redirect()->back()
            ->with('success', 'Subscription request cancelled successfully!');
    }

    public function checkStatus()
    {
        $user = auth()->user();
        $requests = $user->subscriptionRequests()
            ->with('subscription')
            ->get();

        $statuses = [];
        foreach ($requests as $request) {
            $statuses[$request->subscription_id] = $request->status;
        }

        return response()->json($statuses);
    }
}
