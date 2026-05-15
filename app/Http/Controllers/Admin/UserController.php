<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

    public function create()
    {
        $suppliers = User::query()
            ->where('role', 'supplier')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('admin.users.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'string', Rule::in(['admin', 'buyer', 'supplier', 'sub_supplier', 'guest'])],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'is_approved' => ['nullable', 'boolean'],
            'email_verified' => ['nullable', 'boolean'],
            'parent_supplier_id' => [
                'nullable',
                Rule::requiredIf(fn () => $request->input('role') === 'sub_supplier'),
                Rule::exists('users', 'id')->where(fn ($q) => $q->where('role', 'supplier')),
            ],
        ]);

        $role = $validated['role'];

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => $role,
            'is_approved' => (bool) ($validated['is_approved'] ?? ($role === 'supplier' || $role === 'sub_supplier' ? false : true)),
            'email_verified_at' => !empty($validated['email_verified']) ? now() : null,
            'parent_supplier_id' => $role === 'sub_supplier' ? ($validated['parent_supplier_id'] ?? null) : null,
            'is_buyer' => $role === 'buyer',
            'is_supplier' => in_array($role, ['supplier', 'sub_supplier'], true),
        ]);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $suppliers = User::query()
            ->where('role', 'supplier')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('admin.users.edit', compact('user', 'suppliers'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', 'string', Rule::in(['admin', 'buyer', 'supplier', 'sub_supplier', 'guest'])],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'is_approved' => ['nullable', 'boolean'],
            'email_verified' => ['nullable', 'boolean'],
            'parent_supplier_id' => [
                'nullable',
                Rule::requiredIf(fn () => $request->input('role') === 'sub_supplier'),
                Rule::exists('users', 'id')->where(fn ($q) => $q->where('role', 'supplier')),
            ],
        ]);

        // Prevent admin from locking themselves out of the admin role.
        if ($user->id === auth()->id() && $validated['role'] !== 'admin') {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'You cannot change your own role away from admin.');
        }

        $role = $validated['role'];

        $update = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $role,
            'is_approved' => (bool) ($validated['is_approved'] ?? ($role === 'supplier' || $role === 'sub_supplier' ? $user->is_approved : true)),
            'email_verified_at' => !empty($validated['email_verified']) ? ($user->email_verified_at ?? now()) : null,
            'parent_supplier_id' => $role === 'sub_supplier' ? ($validated['parent_supplier_id'] ?? null) : null,
            'is_buyer' => $role === 'buyer',
            'is_supplier' => in_array($role, ['supplier', 'sub_supplier'], true),
        ];

        if (!empty($validated['password'])) {
            $update['password'] = $validated['password'];
        }

        $user->update($update);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'User updated successfully.');
    }
    
    public function show(User $user)
    {
        $user->load(['companyDetail', 'subscriptions.subscription', 'tenders']);
        
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
