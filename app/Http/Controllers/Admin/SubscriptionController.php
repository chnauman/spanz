<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionRequest;
use App\Models\User;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::all();
        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    public function create()
    {
        return view('admin.subscriptions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'credits_per_month' => 'required|integer',
            'credit_cost_per_view' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        Subscription::create($request->all());

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription plan created successfully!');
    }

    public function edit(Subscription $subscription)
    {
        return view('admin.subscriptions.edit', compact('subscription'));
    }

    public function update(Request $request, Subscription $subscription)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'credits_per_month' => 'required|integer',
            'credit_cost_per_view' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $subscription->update($request->all());

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription plan updated successfully!');
    }

    public function show(Subscription $subscription)
    {
        $subscription->load(['userSubscriptions.user']);
        return view('admin.subscriptions.show', compact('subscription'));
    }

    public function destroy(Subscription $subscription)
    {
        // Check if subscription is being used
        if ($subscription->userSubscriptions()->exists()) {
            return redirect()->back()
                ->with('error', 'Cannot delete subscription plan that is currently in use.');
        }

        $subscription->delete();

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription plan deleted successfully!');
    }

    // Subscription Requests Management
    public function requests(Request $request)
    {
        $query = SubscriptionRequest::with(['user', 'subscription', 'processedBy']);
        
        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        $requests = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.subscriptions.requests', compact('requests'));
    }

    public function approveRequest(SubscriptionRequest $request)
    {
        $request->approve(auth()->id());

        \Log::info('Subscription request approved: ' . $request->id);

        return redirect()->back()
            ->with('success', 'Subscription request approved successfully!');
    }

    public function declineRequest(Request $httpRequest, SubscriptionRequest $request)
    {
        $request->decline(auth()->id(), $httpRequest->input('admin_notes'));

        \Log::info('Subscription request declined: ' . $request->id);

        return redirect()->back()
            ->with('success', 'Subscription request declined successfully!');
    }

    public function showRequest(SubscriptionRequest $request)
    {
        $request->load(['user', 'subscription', 'processedBy']);
        return view('admin.subscriptions.show-request', compact('request'));
    }
}
