<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::where('is_active', true)
            ->orderBy('price', 'asc')
            ->get();

        $user = auth()->user();

        return view('pricing', compact('subscriptions', 'user'));
    }
}
