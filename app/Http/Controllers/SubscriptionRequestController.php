<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\SubscriptionRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\UpgradeRequestAdminMail;

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
        try {
            \Log::info('Subscription request started', [
                'subscription_id' => $subscription->id,
                'subscription_name' => $subscription->name,
                'user_id' => auth()->id()
            ]);

            $user = auth()->user();

            if (!$user) {
                if (request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You must be logged in to request a subscription.'
                    ], 401);
                }
                return redirect()->route('login');
            }

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

            // Check if user has any pending request (across all subscriptions)
            $anyPendingRequest = SubscriptionRequest::where('user_id', $user->id)
                ->where('status', 'pending')
                ->first();

            if ($anyPendingRequest) {
                if (request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You already have a pending subscription request. Please wait for it to be processed before making another request.'
                    ]);
                }
                return redirect()->back()
                    ->with('info', 'You already have a pending subscription request. Please wait for it to be processed before making another request.');
            }

            // Check if user already has this subscription
            $activeSubscription = $user->getActiveSubscription();
            if ($activeSubscription && $activeSubscription->subscription_id === $subscription->id) {
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

            // Log the subscription request
            \Log::info('Subscription request created for user: ' . $user->email . ', subscription: ' . $subscription->name);

            // Notify admin about the upgrade request
            try {
                $adminEmail = env('ADMIN_EMAIL');
                $request->load(['user','subscription']);
                
                if ($adminEmail) {
                    // Use ADMIN_EMAIL from env if set
                    Mail::to($adminEmail)->send(new UpgradeRequestAdminMail($request));
                } else {
                    // Fallback: send to all admin users from database
                    $adminUsers = User::where('role', 'admin')->get();
                    foreach ($adminUsers as $admin) {
                        Mail::to($admin->email)->send(new UpgradeRequestAdminMail($request));
                    }
                }
            } catch (\Throwable $e) {
                \Log::error('Failed to send upgrade request admin email: ' . $e->getMessage());
            }

            if (request()->ajax()) {
                \Log::info('Returning JSON response for subscription request');
                return response()->json([
                    'success' => true,
                    'message' => 'Subscription request submitted successfully!'
                ]);
            }

            return redirect()->back()
                ->with('success', 'Subscription request submitted successfully!');

        } catch (\Exception $e) {
            \Log::error('Subscription request error: ' . $e->getMessage());

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while processing your request. Please try again.'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'An error occurred while processing your request. Please try again.');
        }
    }

    public function requestById($id)
    {
        try {
            \Log::info('Subscription request by ID started', [
                'subscription_id' => $id,
                'user_id' => auth()->id()
            ]);

            $subscription = Subscription::find($id);
            if (!$subscription) {
                \Log::error('Subscription not found', ['id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Subscription not found.'
                ], 404);
            }

            $user = auth()->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be logged in to request a subscription.'
                ], 401);
            }

            // Check if user already has a pending request for this subscription
            $existingRequest = SubscriptionRequest::where('user_id', $user->id)
                ->where('subscription_id', $subscription->id)
                ->where('status', 'pending')
                ->first();

            if ($existingRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'You already have a pending request for this subscription.'
                ]);
            }

            // Check if user has any pending request (across all subscriptions)
            $anyPendingRequest = SubscriptionRequest::where('user_id', $user->id)
                ->where('status', 'pending')
                ->first();

            if ($anyPendingRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'You already have a pending subscription request. Please wait for it to be processed before making another request.'
                ]);
            }

            // Check if user already has this subscription
            $activeSubscription = $user->getActiveSubscription();
            if ($activeSubscription && $activeSubscription->subscription_id === $subscription->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You already have this subscription.'
                ]);
            }

            // Create subscription request
            $request = SubscriptionRequest::create([
                'user_id' => $user->id,
                'subscription_id' => $subscription->id,
                'requested_at' => now(),
            ]);

            \Log::info('Subscription request created successfully', [
                'user_id' => $user->id,
                'subscription_id' => $subscription->id
            ]);

            // Notify admin about the upgrade request
            try {
                $adminEmail = env('ADMIN_EMAIL');
                $request->load(['user','subscription']);
                
                if ($adminEmail) {
                    // Use ADMIN_EMAIL from env if set
                    Mail::to($adminEmail)->send(new UpgradeRequestAdminMail($request));
                } else {
                    // Fallback: send to all admin users from database
                    $adminUsers = User::where('role', 'admin')->get();
                    foreach ($adminUsers as $admin) {
                        Mail::to($admin->email)->send(new UpgradeRequestAdminMail($request));
                    }
                }
            } catch (\Throwable $e) {
                \Log::error('Failed to send upgrade request admin email: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Subscription request submitted successfully!'
            ]);

        } catch (\Exception $e) {
            \Log::error('Subscription request error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your request. Please try again.'
            ], 500);
        }
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

    public function getSubscriptionSummary()
    {
        $user = auth()->user();
        $status = $user->getSubscriptionAndCreditStatus();

        return response()->json($status);
    }

}
