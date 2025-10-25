<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DowngradeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DowngradeRequestController extends Controller
{
    /**
     * Display a listing of downgrade requests
     */
    public function index(Request $request)
    {
        $query = DowngradeRequest::with(['user', 'currentSubscription', 'processedBy']);

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.downgrade-requests.index', compact('requests'));
    }

    /**
     * Display the specified downgrade request
     */
    public function show(DowngradeRequest $downgradeRequest)
    {
        $downgradeRequest->load(['user', 'currentSubscription', 'processedBy']);

        return view('admin.downgrade-requests.show', compact('downgradeRequest'));
    }

    /**
     * Approve a downgrade request
     */
    public function approve(Request $request, DowngradeRequest $downgradeRequest)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        if ($downgradeRequest->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'This request has already been processed.');
        }

        $downgradeRequest->approve(Auth::id(), $request->admin_notes);

        return redirect()->route('admin.downgrade-requests.index')
            ->with('success', 'Downgrade request approved successfully! The user will become free after their subscription expires.');
    }

    /**
     * Decline a downgrade request
     */
    public function decline(Request $request, DowngradeRequest $downgradeRequest)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);

        if ($downgradeRequest->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'This request has already been processed.');
        }

        $downgradeRequest->decline(Auth::id(), $request->admin_notes);

        return redirect()->route('admin.downgrade-requests.index')
            ->with('success', 'Downgrade request declined successfully! The user has been notified.');
    }
}
