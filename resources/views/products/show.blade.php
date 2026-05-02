<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $product->title }} - Product Details</title>
    <link rel="stylesheet" href="{{ asset('css/output.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --thomas-navy: #032747;
            --thomas-blue: #0d6efd;
            --thomas-bg: #f3f6fa;
            --thomas-border: #d8e2ee;
            --thomas-text: #15314c;
        }

        body.thomas-product-show {
            background: var(--thomas-bg);
            color: var(--thomas-text);
        }

        .thomas-topbar {
            background: linear-gradient(180deg, #032747 0%, #0a3255 100%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .thomas-content-wrap {
            max-width: 1200px;
            margin: 0 auto;
        }

        .thomas-panel {
            background: #fff;
            border: 1px solid var(--thomas-border);
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(3, 39, 71, 0.06);
        }

        .thomas-title-strip {
            background: linear-gradient(180deg, #052d50 0%, #0d3a63 100%);
            border-radius: 6px;
        }

        .thomas-cta {
            background: var(--thomas-blue);
        }

        .thomas-cta:hover {
            background: #0b5fd7;
        }

        /* Fix dropdown hover behavior */
        .dropdown-group {
            position: relative;
        }

        .dropdown-menu {
            position: absolute;
            left: 0;
            top: 100%;
            margin-top: 0.5rem;
            width: auto;
            background: white;
            border-radius: 0.375rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transform: translateY(-10px);
            transition: all 0.3s ease-in-out;
            z-index: 50;
            min-width: 16rem;
        }

        /* Show dropdown on hover with delay */
        .dropdown-group:hover .dropdown-menu {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transform: translateY(0);
            transition-delay: 0.1s;
        }

        /* Keep dropdown open when hovering over it */
        .dropdown-menu:hover {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transform: translateY(0);
        }

        /* Add a small gap to prevent flickering when moving from button to dropdown */
        .dropdown-group::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            height: 0.5rem;
            background: transparent;
            z-index: 49;
        }

        /* Ensure dropdowns are hidden by default */
        .dropdown-menu {
            display: block;
        }

        /* Prevent any CSS conflicts */
        .dropdown-group .dropdown-menu {
            opacity: 0 !important;
            visibility: hidden !important;
            pointer-events: none !important;
            transform: translateY(-10px) !important;
        }

        .dropdown-group:hover .dropdown-menu {
            opacity: 1 !important;
            visibility: visible !important;
            pointer-events: auto !important;
            transform: translateY(0) !important;
        }

        .dropdown-menu a {
            display: block;
            padding: 0.65rem 1rem !important;
            font-size: 0.95rem !important;
            font-weight: 700 !important;
            color: #0f3556 !important;
        }

        .dropdown-menu a:hover {
            background: #eaf2ff !important;
            color: #0d6aed !important;
        }
    </style>
</head>

<body class="thomas-product-show">
    <div class="thomas-topbar py-2">
        <nav>
            <div class="max-w-9xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex-shrink-0">
                        <a href="{{ route('home') }}" class="block text-white text-3xl font-extrabold italic tracking-widest leading-none" style="font-family: 'Eurostile', 'Orbitron', 'Arial Black', sans-serif;">
                            SPANZ
                        </a>
                    </div>

                    <div class="hidden md:flex space-x-6">
                        <div class="dropdown-group">
                            <button class="text-white hover:text-blue-400 flex items-center">For Buyers ▾</button>
                            <div class="dropdown-menu">
                                <div class="py-1 whitespace-nowrap">
                                    @auth
                                        @if(!auth()->user()->isAdmin())
                                            <a href="{{ route('tenders.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Post a RFX</a>
                                            <a href="{{ route('tenders.saved') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Saved RFXs</a>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}?redirect={{ urlencode(route('tenders.create')) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Post a RFX</a>
                                        <a href="{{ route('login') }}?redirect={{ urlencode(route('tenders.saved')) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Saved RFXs</a>
                                    @endauth
                                </div>
                            </div>
                        </div>

                        <div class="dropdown-group">
                            <button class="text-white hover:text-blue-400 flex items-center">For Suppliers ▾</button>
                            <div class="dropdown-menu">
                                <div class="py-1 whitespace-nowrap">
                                    @auth
                                        @if(auth()->user()->isSupplier() || auth()->user()->isSubSupplier())
                                            <a href="{{ route('tenders.saved') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Saved Tenders</a>
                                            <a href="{{ route('user.interests') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">My Interests</a>
                                            <a href="{{ route('tenders.viewed') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Viewed Tenders</a>
                                            @if(auth()->user()->isSupplier())
                                            <a href="{{ route('invite.sub-suppliers') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Invite Sub Supplier</a>
                                            @endif
                                            <a href="#" onclick="openSubscriptionModal(); return false;" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Subscription Plans</a>
                                        @else
                                            <a href="#" onclick="openSubscriptionModal(); return false;" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 whitespace-nowrap">Become a Supplier</a>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}?redirect={{ urlencode(request()->url()) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Become a Supplier</a>
                                    @endauth
                                </div>
                            </div>
                        </div>

                        <a href="#" class="text-white hover:text-blue-400">About</a>
                    </div>

                    <div class="hidden md:flex items-center space-x-4">
                        <a href="{{ route('tenders.search') }}" class="text-white hover:text-blue-400">Tenders</a>
                        <a href="{{ route('products.search') }}" class="text-white hover:text-blue-400">Products</a>
                        @auth
                            <a href="{{ route('dashboard') }}" class="border border-white text-white px-3 py-1 rounded hover:bg-white hover:text-black">Dashboard</a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="bg-blue-700 text-white px-3 py-1 rounded hover:bg-blue-800">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="border border-white text-white px-3 py-1 rounded hover:bg-white hover:text-black">Login</a>
                            <a href="{{ route('register') }}" class="bg-blue-700 text-white px-3 py-1 rounded hover:bg-blue-800">Register</a>
                        @endauth
                    </div>

                    <div class="md:hidden">
                        <button id="menu-btn" class="text-white focus:outline-none">
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div id="mobile-menu" class="hidden md:hidden bg-[#092c47] text-white px-4 py-4 space-y-3">
                <a href="#" class="block hover:text-blue-300">For Buyers ▾</a>
                <a href="#" class="block hover:text-blue-300">For Suppliers ▾</a>
                <a href="#" class="block hover:text-blue-300">About</a>
                <a href="{{ route('tenders.search') }}" class="block hover:text-blue-300">Tenders</a>
                <a href="{{ route('products.search') }}" class="block hover:text-blue-300">Products</a>
                <a href="{{ route('company.register') }}" class="block hover:text-blue-300">Claim Your Company</a>
                <a href="#" class="block hover:text-blue-300">Start Advertising</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="w-full border border-white text-white px-3 py-2 rounded hover:bg-white hover:text-black text-center block">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full bg-blue-700 text-white px-3 py-2 rounded hover:bg-blue-800 text-center block">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="w-full border border-white text-white px-3 py-2 rounded hover:bg-white hover:text-black text-center block">Login</a>
                    <a href="{{ route('register') }}" class="w-full bg-blue-700 text-white px-3 py-2 rounded hover:bg-blue-800 text-center block">Register</a>
                @endauth
            </div>
        </nav>
    </div>

    <div class="thomas-content-wrap max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
        <div class="thomas-panel p-3 sm:p-4 lg:p-6">
            <div>
                <h3 class="text-lg sm:text-xl font-semibold">Product Overview</h3>
            </div>
            <div class="thomas-title-strip flex flex-col sm:flex-row sm:items-center sm:justify-between text-white p-4 mt-6 sm:mt-8 lg:mt-10 space-y-2 sm:space-y-0">
                <h1 class="text-xl sm:text-2xl font-bold">{{ $product->title }}</h1>
                <span class="text-sm sm:text-base">Category: {{ optional($product->category)->name ?? 'Uncategorized' }}</span>
            </div>

            <!-- Main Content Layout: Left Details, Right Image -->
            <div class="flex flex-col lg:flex-row lg:gap-8 mt-6 sm:mt-8">
                <!-- Left side: Product Details -->
                <div class="flex-1 lg:w-2/3">
                    <h3 class="text-base sm:text-lg font-semibold mb-4">Product Details</h3>
                    <div class="mb-6 space-y-3">
                        @if(!is_null($product->price))
                        <div class="flex items-center text-[#6C6C6C] space-x-2">
                            <span class="text-sm sm:text-base">Price:</span>
                            <span class="text-sm sm:text-base font-semibold">{{ $product->currency }} {{ number_format($product->price, 2) }}</span>
                        </div>
                        @endif
                        @if($product->featured)
                        <div class="flex items-center text-green-700 space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Featured</span>
                        </div>
                        @endif
                    </div>

                    <div class="mt-4">
                        <h3 class="font-semibold mb-3">Description:</h3>
                        <div class="text-sm sm:text-base leading-relaxed">{!! nl2br(e($product->description)) !!}</div>
                    </div>

                    @if(!empty($product->specs))
                    <div class="mt-6 sm:mt-8">
                        <h3 class="font-semibold mb-3 text-base sm:text-lg">Specifications:</h3>
                        <div class="text-sm sm:text-base leading-relaxed">
                            <pre class="whitespace-pre-wrap">{{ json_encode($product->specs, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Right side: Product Image -->
                <div class="lg:w-1/3 flex-shrink-0 mt-6 lg:mt-0">
                    <div class="sticky top-4">
                        <div class="w-full max-w-xs mx-auto lg:mx-0">
                            @php($gallery = is_array($product->images) ? $product->images : [])
                            @php($allImages = array_values(array_filter(array_merge([$product->image], $gallery))))

                            <img id="main-product-image"
                                 src="{{ $product->image ? asset('storage/' . $product->image) : 'data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22400%22 height=%22300%22%3E%3Crect fill=%22%23ddd%22 width=%22400%22 height=%22300%22/%3E%3Ctext fill=%22%23999%22 font-family=%22sans-serif%22 font-size=%2218%22 dy=%2210.5%22 font-weight=%22bold%22 x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22%3ENo Image%3C/text%3E%3C/svg%3E' }}" 
                                 alt="{{ $product->title }}" 
                                class="w-full h-auto max-h-80 object-contain rounded-lg border border-gray-200 shadow-sm bg-white" 
                                 onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22400%22 height=%22300%22%3E%3Crect fill=%22%23ddd%22 width=%22400%22 height=%22300%22/%3E%3Ctext fill=%22%23999%22 font-family=%22sans-serif%22 font-size=%2218%22 dy=%2210.5%22 font-weight=%22bold%22 x=%2250%25%22 y=%2250%25%22 text-anchor=%22middle%22%3ENo Image%3C/text%3E%3C/svg%3E';" />

                            @if(count($allImages) > 1)
                                <div class="mt-4 grid grid-cols-4 gap-2" id="product-gallery">
                                    @foreach($allImages as $path)
                                        <button type="button"
                                                class="border border-gray-200 rounded hover:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 p-1 bg-white"
                                                data-full="{{ asset('storage/' . $path) }}">
                                            <img src="{{ asset('storage/' . $path) }}"
                                                 alt="Gallery image"
                                                 class="w-full h-14 object-cover rounded"
                                                 onerror="this.src='{{ $product->image_url_with_fallback }}';" />
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Purchase Button Section -->
            <div class="mt-8 flex justify-end">
            <a onclick="openPurchaseModal({{ $product->id }}, '{{ $product->title }}')"
                    class="thomas-cta px-6 py-3 text-white rounded-sm text-base font-medium">
                        Purchase
    </a>

            </div>
        </div>
    </div>

    @include('components.mainfooter')

    <!-- Purchase Request Modal -->
    <div id="purchase-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-md w-full p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-[#092C48]">Purchase Request</h3>
                    <button id="close-purchase-modal" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">Product:</p>
                    <p class="font-medium text-[#092C48]" id="purchase-product-title"></p>
                </div>

                <form id="purchase-form">
                    <input type="hidden" id="purchase-product-id" name="product_id">

                    <!-- Contact Information for Non-Authenticated Users -->
                    <div id="contact-info-section" class="mb-4" style="display: none;">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Contact Information</h4>
                        <div class="grid grid-cols-1 gap-3">
                            <div>
                                <label for="purchase-name" class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                                <input type="text" id="purchase-name" name="name"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="purchase-email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                                <input type="email" id="purchase-email" name="email"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label for="purchase-phone" class="block text-sm font-medium text-gray-700 mb-1">Phone (Optional)</label>
                                <input type="tel" id="purchase-phone" name="phone"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="purchase-quantity" class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                        <input type="number" id="purchase-quantity" name="quantity" min="1" value="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>

                    <div class="mb-6">
                        <label for="purchase-notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                        <textarea id="purchase-notes" name="notes" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Any additional information about your purchase request..."></textarea>
                    </div>

                <div class="flex gap-3">
                        <button type="button" id="cancel-purchase"
                                class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" id="submit-purchase"
                                class="thomas-cta px-6 py-3 text-white rounded-sm text-base font-medium">
                            Submit Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const menuBtn = document.getElementById("menu-btn");
        const mobileMenu = document.getElementById("mobile-menu");
        if (menuBtn && mobileMenu) {
            menuBtn.addEventListener("click", () => {
                mobileMenu.classList.toggle("hidden");
            });
        }

        // Purchase modal functionality
        const purchaseModal = document.getElementById('purchase-modal');
        const closePurchaseModal = document.getElementById('close-purchase-modal');
        const cancelPurchase = document.getElementById('cancel-purchase');
        const purchaseForm = document.getElementById('purchase-form');

        // Close modal functions
        function closePurchaseModalFunc() {
            purchaseModal.classList.add('hidden');
            document.body.style.overflow = '';
        }

        closePurchaseModal?.addEventListener('click', closePurchaseModalFunc);
        cancelPurchase?.addEventListener('click', closePurchaseModalFunc);

        // Close modal when clicking outside
        purchaseModal?.addEventListener('click', (e) => {
            if (e.target === purchaseModal) {
                closePurchaseModalFunc();
            }
        });

        // Form submission
        purchaseForm?.addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submit-purchase');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Submitting...';
            submitBtn.disabled = true;

            const formData = new FormData(this);

            fetch('{{ route("purchase-requests.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message,
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#0D6AED'
                        }).then(() => {
                            closePurchaseModalFunc();
                            location.reload(); // Reload to show "Requested" status
                        });
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Notice',
                            text: data.message || 'An error occurred. Please try again.',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#0D6AED'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred. Please try again.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#0D6AED'
                    });
                })
            .finally(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });

        // Global function to open purchase modal
        function openPurchaseModal(productId, productTitle) {
            console.log('Purchase button clicked for product:', productId, productTitle);


            document.getElementById('purchase-product-id').value = productId;
            document.getElementById('purchase-product-title').textContent = productTitle;
            document.getElementById('purchase-quantity').value = 1;
            document.getElementById('purchase-notes').value = '';

            // Check if user is authenticated
            const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
            const contactSection = document.getElementById('contact-info-section');

            if (isAuthenticated) {
                contactSection.style.display = 'none';
            } else {
                contactSection.style.display = 'block';
                // Clear contact fields
                document.getElementById('purchase-name').value = '';
                document.getElementById('purchase-email').value = '';
                document.getElementById('purchase-phone').value = '';
            }

            const purchaseModal = document.getElementById('purchase-modal');
            purchaseModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        // Gallery click-to-swap main image
        document.addEventListener('DOMContentLoaded', function () {
            const gallery = document.getElementById('product-gallery');
            const mainImg = document.getElementById('main-product-image');
            if (!gallery || !mainImg) return;

            gallery.addEventListener('click', function (e) {
                const btn = e.target.closest('button[data-full]');
                if (!btn) return;
                const src = btn.getAttribute('data-full');
                if (!src) return;
                mainImg.src = src;
            });
        });
    </script>

</body>

</html>
