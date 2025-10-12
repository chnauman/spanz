<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tender;
use App\Models\Category;
use App\Models\UserInterest;
use App\Models\TenderInvitation;
use App\Models\SavedTender;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\TenderInvitationMail;
use App\Jobs\SendTenderInvitationJob;

class TenderController extends Controller
{
    public function index()
    {
        $tenders = Tender::with(['user', 'category'])
            ->where('status', 'active')
            ->where('deadline', '>', now())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('tenders.index', compact('tenders'));
    }

    public function search(Request $request)
    {
        $query = Tender::with(['user', 'category'])
            ->where('status', 'active')
            ->where('deadline', '>', now());

        // Apply category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Apply location filter
        if ($request->filled('location')) {
            $locations = is_array($request->location) ? $request->location : [$request->location];
            $query->where(function($q) use ($locations) {
                foreach ($locations as $location) {
                    $q->orWhere('location', 'like', '%' . $location . '%');
                }
            });
        }

        // Apply company type filter (using role field)
        if ($request->filled('company_type')) {
            $companyTypes = is_array($request->company_type) ? $request->company_type : [$request->company_type];
            $query->whereHas('user', function($userQuery) use ($companyTypes) {
                $userQuery->whereIn('role', $companyTypes);
            });
        }

        // Apply search filter
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhere('location', 'like', '%' . $searchTerm . '%')
                  ->orWhere('requirements', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('category', function($categoryQuery) use ($searchTerm) {
                      $categoryQuery->where('name', 'like', '%' . $searchTerm . '%');
                  })
                  ->orWhereHas('user', function($userQuery) use ($searchTerm) {
                      $userQuery->where('name', 'like', '%' . $searchTerm . '%')
                               ->orWhere('company_name', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        $tenders = $query->orderBy('created_at', 'desc')->paginate(12);
        
        // Get all categories for display (we'll handle pagination in JavaScript)
        $allCategories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();
        
        // Get dynamic locations from actual tender data
        $allLocations = Tender::where('status', 'active')
            ->where('deadline', '>', now())
            ->whereNotNull('location')
            ->where('location', '!=', '')
            ->distinct()
            ->pluck('location')
            ->filter()
            ->sort()
            ->values()
            ->toArray();
        
        // Handle pagination for categories (10 per page)
        $categoryPage = $request->get('category_page', 1);
        $categoriesPerPage = 10;
        $startIndex = ($categoryPage - 1) * $categoriesPerPage;
        $categories = $allCategories->slice($startIndex, $categoriesPerPage);
        $hasMoreCategories = $allCategories->count() > ($categoryPage * $categoriesPerPage);
        
        // Handle pagination for locations (5 per page)
        $locationPage = $request->get('location_page', 1);
        $locationsPerPage = 5;
        $startLocationIndex = ($locationPage - 1) * $locationsPerPage;
        $locations = collect($allLocations)->slice($startLocationIndex, $locationsPerPage)->values()->toArray();
        $hasMoreLocations = count($allLocations) > ($locationPage * $locationsPerPage);
            
        return view('tenders.search', compact('tenders', 'categories', 'locations', 'hasMoreCategories', 'hasMoreLocations', 'allCategories', 'allLocations'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('tenders.create', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'request_type' => 'required|string|in:rfq,rft,rfp,eoi',
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'budget' => 'required|string',
                'location' => 'required|string',
                'currency' => 'required|string|size:3',
                'deadline' => 'required|date|after:today',
                'requirements' => 'nullable|string',
                'contact_email' => 'nullable|email',
                'contact_phone' => 'nullable|string|max:20',
                'categories' => 'required|array|min:1',
                'categories.*.main_category' => 'required|exists:categories,id',
                'categories.*.sub_category' => 'required|string',
                'categories.*.product_type' => 'required|string',
                'files.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,gif,webp|max:10240', // 10MB max
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Return back with errors and old input to keep form filled
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        }

        try {
            $user = Auth::user();
            
            // Handle file uploads
            $attachments = [];
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('tender-attachments', $filename, 'public');
                    $attachments[] = [
                        'filename' => $file->getClientOriginalName(),
                        'path' => $path,
                        'size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                    ];
                }
            }
            
            // Create the tender
            $tender = Tender::create([
                'user_id' => $user->id,
                'category_id' => $request->categories[0]['main_category'], // Use first category as primary
                'title' => $request->title,
                'description' => $request->description,
                'budget' => $request->budget,
                'currency' => $request->currency,
                'deadline' => $request->deadline,
                'requirements' => $request->requirements,
                'location' => $request->location,
                'contact_email' => $request->contact_email,
                'contact_phone' => $request->contact_phone,
                'request_type' => $request->request_type,
                'categories' => $request->categories,
                'attachments' => $attachments,
                'status' => 'active',
            ]);

            // Mark user as buyer
            $user->update(['is_buyer' => true]);

            // Send invitations to users with matching interests
            $this->sendTenderInvitations($tender);

            return redirect()->route('tenders.index')
                ->with('success', 'Tender posted successfully! Invitations have been sent to interested users.');
                
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Tender creation failed: ' . $e->getMessage());
            
            // Return back with error message and old input
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while creating the tender. Please try again.');
        }
    }

    public function show(Tender $tender)
    {
        $tender->load(['user', 'category']);
        return view('tenders.show', compact('tender'));
    }

    public function detail(Tender $tender)
    {
        $tender->load(['user', 'category']);
        $subscriptions = Subscription::where('is_active', true)
            ->where('name', '!=', 'Basic')
            ->get();
        return view('tenders.detail', compact('tender', 'subscriptions'));
    }

    public function myTenders()
    {
        $tenders = Auth::user()->tenders()
            ->with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('tenders.my-tenders', compact('tenders'));
    }

    public function savedTenders()
    {
        $savedTenders = Auth::user()->savedTenders()
            ->with(['tender.user', 'tender.category'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);
            
        return view('tenders.saved', compact('savedTenders'));
    }

    public function invitations()
    {
        $invitations = Auth::user()->tenderInvitations()
            ->with(['tender.user', 'tender.category'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('tenders.invitations', compact('invitations'));
    }

    public function viewInvitation(TenderInvitation $invitation)
    {
        // Mark invitation as viewed
        $invitation->markAsViewed();
        
        return redirect()->route('tenders.show', $invitation->tender_id);
    }

    /**
     * Send tender invitations to users with matching interests.
     * Emails are queued for background processing to improve performance.
     */
    private function sendTenderInvitations(Tender $tender)
    {
        // Get users with matching interests
        $interestedUsers = UserInterest::where('category_id', $tender->category_id)
            ->with('user')
            ->get()
            ->pluck('user')
            ->unique('id');

        foreach ($interestedUsers as $user) {
            // Skip the tender creator
            if ($user->id === $tender->user_id) {
                continue;
            }

            // Create invitation
            TenderInvitation::create([
                'tender_id' => $tender->id,
                'user_id' => $user->id,
            ]);

            // Queue email notification for background processing
            SendTenderInvitationJob::dispatch($tender, $user);
        }
    }

    public function saveTender(Request $request, Tender $tender)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'You must be logged in to save tenders'], 401);
        }

        $user = Auth::user();
        
        // Check if already saved
        $existingSave = SavedTender::where('user_id', $user->id)
            ->where('tender_id', $tender->id)
            ->first();

        if ($existingSave) {
            return response()->json(['error' => 'Tender already saved'], 400);
        }

        SavedTender::create([
            'user_id' => $user->id,
            'tender_id' => $tender->id,
        ]);

        return response()->json(['message' => 'Tender saved successfully']);
    }

    public function unsaveTender(Request $request, Tender $tender)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'You must be logged in'], 401);
        }

        $user = Auth::user();
        
        SavedTender::where('user_id', $user->id)
            ->where('tender_id', $tender->id)
            ->delete();

        return response()->json(['message' => 'Tender removed from saved list']);
    }

    public function isTenderSaved(Tender $tender)
    {
        if (!Auth::check()) {
            return response()->json(['saved' => false]);
        }

        $user = Auth::user();
        $saved = SavedTender::where('user_id', $user->id)
            ->where('tender_id', $tender->id)
            ->exists();

        return response()->json(['saved' => $saved]);
    }
}
