<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseRequestController extends Controller
{
    public function store(Request $request)
    {
        $validationRules = [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500'
        ];

        // If user is not authenticated, require contact information
        if (!Auth::check()) {
            $validationRules['name'] = 'required|string|max:255';
            $validationRules['email'] = 'required|email|max:255';
            $validationRules['phone'] = 'nullable|string|max:20';
        }

        $request->validate($validationRules);

        $product = Product::findOrFail($request->product_id);

        // For authenticated users, check if they already have a pending request
        if (Auth::check()) {
            $existingRequest = PurchaseRequest::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->where('status', 'pending')
                ->first();

            if ($existingRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'You already have a pending purchase request for this product.'
                ], 422);
            }
        }

        // Create the purchase request
        $purchaseRequestData = [
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'notes' => $request->notes,
            'status' => 'pending'
        ];

        if (Auth::check()) {
            $purchaseRequestData['user_id'] = Auth::id();
        } else {
            // For non-authenticated users, store contact info in notes
            $contactInfo = "Name: {$request->name}\nEmail: {$request->email}";
            if ($request->phone) {
                $contactInfo .= "\nPhone: {$request->phone}";
            }
            $purchaseRequestData['notes'] = $contactInfo . ($request->notes ? "\n\nAdditional Notes: {$request->notes}" : '');
            $purchaseRequestData['user_id'] = null; // No user ID for non-authenticated users
        }

        $purchaseRequest = PurchaseRequest::create($purchaseRequestData);

        return response()->json([
            'success' => true,
            'message' => 'Purchase request submitted successfully! We will contact you soon.',
            'request_id' => $purchaseRequest->id
        ]);
    }

    public function index()
    {
        // Only admin can view all purchase requests
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $purchaseRequests = PurchaseRequest::with(['user', 'product'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.purchase-requests.index', compact('purchaseRequests'));
    }

    public function update(Request $request, PurchaseRequest $purchaseRequest)
    {
        // Only admin can update purchase requests
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'status' => 'required|in:pending,approved,rejected'
        ]);

        $purchaseRequest->update([
            'status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Purchase request status updated successfully!'
        ]);
    }

    public function destroy(PurchaseRequest $purchaseRequest)
    {
        // Only admin can delete purchase requests
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $purchaseRequest->delete();

        return response()->json([
            'success' => true,
            'message' => 'Purchase request deleted successfully!'
        ]);
    }
}
