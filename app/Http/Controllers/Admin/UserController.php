<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        
        // Filter by role if specified
        if ($request->has('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }
        
        // Filter by approval status if specified
        if ($request->has('status') && $request->status !== 'all') {
            if ($request->status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_approved', false);
            }
        }
        
        // Search by name or email
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        $users = $query->with(['companyDetail', 'subscriptions.subscription'])
                      ->withCount('tenders')
                      ->withSum('credits', 'amount')
                      ->orderBy('created_at', 'desc')
                      ->paginate(15);
        
        // Get statistics
        $stats = [
            'total' => User::count(),
            'buyers' => User::where('role', 'buyer')->count(),
            'suppliers' => User::where('role', 'supplier')->count(),
            'sub_suppliers' => User::where('role', 'sub_supplier')->count(),
            'pending_approval' => User::where('is_approved', false)
                                   ->whereIn('role', ['supplier', 'sub_supplier'])
                                   ->count(),
        ];
        
        return view('admin.users.index', compact('users', 'stats'));
    }
    
    public function show(User $user)
    {
        $user->load(['companyDetail', 'subscriptions.subscription', 'tenders', 'interests']);
        
        return view('admin.users.show', compact('user'));
    }
    
    public function approve(User $user)
    {
        $user->update(['is_approved' => true]);
        
        return redirect()->back()->with('success', 'User approved successfully.');
    }
    
    public function reject(User $user)
    {
        $user->update(['is_approved' => false]);
        
        return redirect()->back()->with('success', 'User rejected.');
    }
    
    public function destroy(User $user)
    {
        // Prevent admin from deleting themselves
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
