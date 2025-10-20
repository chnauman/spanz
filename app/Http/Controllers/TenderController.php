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
                      $userQuery->where('name', 'like', '%' . $searchTerm . '%');
                  })
                  ->orWhereHas('user.companyDetail', function($companyQuery) use ($searchTerm) {
                      $companyQuery->where('company_name', 'like', '%' . $searchTerm . '%');
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

        // Get active subscriptions for the modal (excluding Basic plan)
        $subscriptions = Subscription::where('is_active', true)
            ->where('name', '!=', 'Basic')
            ->orderBy('price', 'asc')
            ->get();

        return view('tenders.search', compact('tenders', 'categories', 'locations', 'hasMoreCategories', 'hasMoreLocations', 'allCategories', 'allLocations', 'subscriptions'));
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
                'budget' => 'required|string|in:1000,5000,10000,30000,50000,100000,500000,1000000,1000001',
                'location' => 'required|string',
                'currency' => 'required|string|in:AUD,USD,EUR,GBP,SGD,NZD',
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

        // try {
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

            // Convert budget string to decimal value
            $budgetValue = $this->convertBudgetToDecimal($request->budget);

            // Create the tender
            $tender = Tender::create([
                'user_id' => $user->id,
                'category_id' => $request->categories[0]['main_category'], // Use first category as primary
                'title' => $request->title,
                'description' => $request->description,
                'budget' => $budgetValue,
                'currency' => $request->currency,
                'deadline' => $request->deadline,
                'requirements' => $request->requirements,
                'location' => $request->location,
                'contact_email' => $request->contact_email,
                'contact_phone' => $request->contact_phone,
                'request_type' => $request->request_type,
                'categories' => json_encode($request->categories), // Manually convert to JSON string
                'attachments' => json_encode($attachments), // Manually convert to JSON string
                'status' => 'active',
            ]);

            // Mark user as buyer
            $user->update(['is_buyer' => true]);

            // Send invitations to users with matching interests
            $this->sendTenderInvitations($tender);

            return redirect()->route('tenders.my-tenders')
                ->with('success', 'Tender posted successfully! Invitations have been sent to interested users.');

        // } catch (\Exception $e) {
        //     // Log the error for debugging
        //     \Log::error('Tender creation failed: ' . $e->getMessage());

        //     // Return back with error message and old input
        //     return redirect()->back()
        //         ->withInput()
        //         ->with('error', 'An error occurred while creating the tender. Please try again.');
        // }
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

    public function viewedTenders()
    {
        $user = Auth::user();

        // Get tenders that the user has viewed
        $viewedTenders = $user->tenderViews()
            ->with(['tender.user', 'tender.category'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('tenders.viewed', compact('viewedTenders'));
    }

    public function viewInvitation(TenderInvitation $invitation)
    {
        // Mark invitation as viewed
        $invitation->markAsViewed();

        return redirect()->route('tenders.detail', $invitation->tender_id);
    }

    /**
     * Convert budget string value to decimal for database storage
     */
    private function convertBudgetToDecimal($budgetString)
    {
        switch ($budgetString) {
            case '1000':
                return 1000.00;
            case '5000':
                return 5000.00;
            case '10000':
                return 10000.00;
            case '30000':
                return 30000.00;
            case '50000':
                return 50000.00;
            case '100000':
                return 100000.00;
            case '500000':
                return 500000.00;
            case '1000000':
                return 1000000.00;
            case '1000001':
                return 1000001.00; // For "over 1 million"
            default:
                return 0.00;
        }
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

    public function viewBuyerDetails(Tender $tender)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'You must be logged in to view buyer details'], 401);
        }

        $user = Auth::user();

        // Check if user has already viewed this tender
        $hasViewed = $user->hasViewedTender($tender->id);

        // If user has already viewed this tender, allow access regardless of current credit status
        if ($hasViewed) {
            // User has already paid for this tender, allow access
        } else {
            // User hasn't viewed this tender before, check subscription and credit status
            $status = $user->getSubscriptionAndCreditStatus();

            // If user can't view, return appropriate error with action
            if (!$status['can_view']) {
                return response()->json([
                    'error' => $status['message'],
                    'action' => $status['action'],
                    'total_credits' => $status['total_credits'],
                    'credit_cost_per_view' => $status['credit_cost_per_view'],
                    'subscription_expired' => $status['subscription_expired'] ?? false
                ], 403);
            }

            // Deduct credits for viewing (only if not already viewed)
            if (!$user->deductCreditsForTenderView($tender->id)) {
                return response()->json(['error' => 'Failed to deduct credits'], 500);
            }
        }

        // Get attachments if they exist
        $attachments = $tender->attachments ?? [];

        // Get remaining credits - check if user has unlimited credits
        $activeSubscription = $user->getActiveSubscription();
        $remainingCredits = $user->getTotalCredits();

        // If user has unlimited credits (credits_per_month < 0), show as unlimited
        if ($activeSubscription && $activeSubscription->subscription->credits_per_month < 0) {
            $remainingCredits = -1; // Use -1 to indicate unlimited
        }

        // Return buyer details
        $buyerDetails = [
            'tender_id' => $tender->id,
            'name' => $tender->user->name,
            'email' => $tender->contact_email ?? $tender->user->email,
            'phone' => $tender->contact_phone,
            'location' => $tender->location,
            'category' => $tender->category->name,
            'posted_at' => $tender->created_at->diffForHumans(),
            'deadline' => $tender->getFormattedDeadline('M d, Y'),
            'remaining_credits' => $remainingCredits,
            'attachments' => $attachments,
            'already_viewed' => $hasViewed
        ];

        return response()->json([
            'success' => true,
            'buyer_details' => $buyerDetails
        ]);
    }

    public function downloadAttachment(Tender $tender, $filename)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to download attachments');
        }

        $user = Auth::user();

        // Check if user has already viewed this tender
        $hasViewed = $user->hasViewedTender($tender->id);

        // If user has already viewed this tender, allow access regardless of current credit status
        if (!$hasViewed) {
            // User hasn't viewed this tender before, check subscription and credit status
            $status = $user->getSubscriptionAndCreditStatus();

            // If user can't view, redirect with error message
            if (!$status['can_view']) {
                return redirect()->back()->with('error', $status['message']);
            }
        }

        // Get attachments
        $attachments = $tender->attachments ?? [];

        // Find the requested attachment
        $attachment = collect($attachments)->firstWhere('filename', $filename);

        if (!$attachment) {
            return redirect()->back()->with('error', 'Attachment not found');
        }

        // Check if file exists
        $filePath = storage_path('app/public/' . $attachment['path']);
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found on server');
        }

        // Return file download
        return response()->download($filePath, $attachment['filename']);
    }
}
