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
            $selected = $request->input('category');
            $selectedIds = is_array($selected) ? $selected : [$selected];
            $selectedIds = collect($selectedIds)
                ->filter(fn ($v) => $v !== null && $v !== '')
                ->map(fn ($v) => (int) $v)
                ->filter(fn ($v) => $v > 0)
                ->values();

            if ($selectedIds->isNotEmpty()) {
                // If a main category is selected, include its active children too.
                $selectedCategories = Category::whereIn('id', $selectedIds->all())
                    ->with(['children' => function ($q) {
                        $q->select(['id', 'parent_category_id'])
                            ->where('is_active', true);
                    }])
                    ->get(['id']);

                $childIds = $selectedCategories
                    ->flatMap(fn ($c) => $c->children->pluck('id'))
                    ->unique()
                    ->values();

                $filterIds = $selectedIds->merge($childIds)->unique()->values();

                $query->whereIn('category_id', $filterIds->all());
            }
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

        $tenders = $query->orderBy('created_at', 'desc')
            ->paginate(12)
            ->appends($request->query());

        // Get all main categories (with children) for display
        $allCategories = Category::where('is_active', true)
            ->whereNull('parent_category_id')
            ->with(['children' => function ($q) {
                $q->where('is_active', true)->orderBy('name');
            }])
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

        $categories = $allCategories;

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

        if ($request->ajax() || $request->wantsJson()) {
            $html = view('tenders.partials.search-results', compact('tenders'))->render();
            return response()->json(['html' => $html]);
        }

        return view('tenders.search', compact('tenders', 'categories', 'locations', 'hasMoreLocations', 'allCategories', 'allLocations', 'subscriptions'));
    }

    public function create()
    {
        // Prevent admin users from creating tenders
        if (auth()->user()->isAdmin()) {
            abort(403, 'Admin users cannot create tenders.');
        }

        // Check if user has company details registered
        $user = auth()->user();
        $hasCompany = $user->companyDetail ? true : false;

        $categories = Category::where('is_active', true)->get();
        return view('tenders.create', compact('categories', 'hasCompany'));
    }

    public function store(Request $request)
    {
        // Prevent admin users from creating tenders
        if (auth()->user()->isAdmin()) {
            abort(403, 'Admin users cannot create tenders.');
        }

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

        // Initialize buyer details variables
        $buyerDetails = null;
        $buyerDetailsError = null;
        $buyerDetailsAction = null;

        // If user is authenticated and has active subscription, check if they should see buyer details
        if (Auth::check()) {
            $user = Auth::user();

            // Check if user has active subscription
            if ($user->hasActiveSubscription()) {
                try {
                    $isOwner = $user->id === $tender->user_id;
                    $hasViewed = $user->hasViewedTender($tender->id);

                    // Only auto-load buyer details if:
                    // 1. User is the owner (free access)
                    // 2. User has already viewed this tender before (already paid)
                    // 3. Team member has already viewed (team access)
                    if ($isOwner || $hasViewed) {
                        $accessType = 'individual';
                        $creditsDeducted = 0;
                        $accessMessage = '';

                        // If user is the owner, allow free access without credit deduction
                        if ($isOwner) {
                            $accessType = 'owner';
                            $accessMessage = 'You are viewing your own tender. No credits deducted.';
                        } else {
                            // Check for team access first
                            $status = $user->getSubscriptionAndCreditStatus($tender->id);

                            if (isset($status['team_access']) && $status['team_access']) {
                                $accessType = 'team';
                                $accessMessage = 'A team member has already viewed this tender. You can view it for free.';
                            } else {
                                // User has already paid for this tender, allow access
                                $accessType = 'previously_viewed';
                                $accessMessage = 'You have previously viewed this tender. No additional credits deducted.';
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

                        // Prepare buyer details
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
                            'already_viewed' => $hasViewed,
                            'is_owner' => $isOwner,
                            'access_type' => $accessType,
                            'access_message' => $accessMessage,
                            'credits_deducted' => $creditsDeducted
                        ];
                    }
                    // If user hasn't viewed before and is not owner, don't auto-load details
                    // They will see the eye icon button to click and use their credit
                } catch (\Exception $e) {
                    $buyerDetailsError = 'An error occurred while loading buyer details.';
                }
            }
        }

        return view('tenders.detail', compact('tender', 'subscriptions', 'buyerDetails', 'buyerDetailsError', 'buyerDetailsAction'));
    }

    public function myTenders()
    {
        // Prevent admin users from viewing their tenders
        if (auth()->user()->isAdmin()) {
            abort(403, 'Admin users cannot view their tenders.');
        }

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

        // Check if user is the owner of this tender
        $isOwner = $user->id === $tender->user_id;
        $hasViewed = false; // Initialize variable

        $accessType = 'individual'; // Default access type
        $creditsDeducted = 0;
        $accessMessage = '';

        // If user is the owner, allow free access without credit deduction
        if ($isOwner) {
            // Owner can view their own tender for free
            $hasViewed = true; // Owner has "viewed" their own tender
            $accessType = 'owner';
            $accessMessage = 'You are viewing your own tender. No credits deducted.';
        } else {
            // Check if user has already viewed this tender
            $hasViewed = $user->hasViewedTender($tender->id);

            // If user has already viewed this tender, allow access regardless of current credit status
            if ($hasViewed) {
                // User has already paid for this tender, allow access
                $accessType = 'previously_viewed';
                $accessMessage = 'You have previously viewed this tender. No additional credits deducted.';
            } else {
                // User hasn't viewed this tender before, check subscription and credit status with team access
                $status = $user->getSubscriptionAndCreditStatus($tender->id);

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

                // Check if this is team access
                if (isset($status['team_access']) && $status['team_access']) {
                    $accessType = 'team';
                    $accessMessage = 'A team member has already viewed this tender. You can view it for free.';
                } else {
                    $accessType = 'individual';
                    $accessMessage = 'Credits have been deducted for viewing this tender.';
                }

                // Deduct credits for viewing (only if not already viewed and not team access)
                if (!$user->deductCreditsForTenderView($tender->id)) {
                    return response()->json(['error' => 'Failed to deduct credits'], 500);
                }
            }
        }

        // Load necessary relationships
        $tender->load(['user', 'category']);

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
            'already_viewed' => $hasViewed,
            'is_owner' => $isOwner,
            'access_type' => $accessType,
            'access_message' => $accessMessage,
            'credits_deducted' => $creditsDeducted
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

        // Attachments are part of the paid/unlocked tender details.
        // Ensure the user/team has unlocked (or unlock now by deducting credits once).
        if (!$user->deductCreditsForTenderView($tender->id)) {
            $status = $user->getSubscriptionAndCreditStatus($tender->id);
            return redirect()->back()->with('error', $status['message'] ?? 'You cannot access this attachment.');
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
