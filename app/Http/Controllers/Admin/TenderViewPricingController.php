<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TenderViewPricingRule;
use Illuminate\Http\Request;

class TenderViewPricingController extends Controller
{
    public function index()
    {
        $rules = TenderViewPricingRule::query()
            ->orderBy('budget_min')
            ->get();

        return view('admin.tender-view-pricing.index', compact('rules'));
    }

    public function create()
    {
        return view('admin.tender-view-pricing.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if (($data['is_active'] ?? false) && $this->hasOverlap($data['budget_min'], $data['budget_max'] ?? null)) {
            return back()
                ->withInput()
                ->withErrors(['budget_min' => 'This range overlaps with an existing active pricing rule.']);
        }

        TenderViewPricingRule::create($data);

        return redirect()
            ->route('admin.tender-view-pricing.index')
            ->with('success', 'Tender view pricing rule created successfully.');
    }

    public function edit(TenderViewPricingRule $rule)
    {
        return view('admin.tender-view-pricing.edit', compact('rule'));
    }

    public function update(Request $request, TenderViewPricingRule $rule)
    {
        $data = $this->validated($request);

        if (($data['is_active'] ?? false) && $this->hasOverlap($data['budget_min'], $data['budget_max'] ?? null, $rule->id)) {
            return back()
                ->withInput()
                ->withErrors(['budget_min' => 'This range overlaps with an existing active pricing rule.']);
        }

        $rule->update($data);

        return redirect()
            ->route('admin.tender-view-pricing.index')
            ->with('success', 'Tender view pricing rule updated successfully.');
    }

    public function destroy(TenderViewPricingRule $rule)
    {
        $rule->delete();

        return redirect()
            ->route('admin.tender-view-pricing.index')
            ->with('success', 'Tender view pricing rule deleted successfully.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'budget_min' => ['required', 'numeric', 'min:0'],
            'budget_max' => ['nullable', 'numeric', 'min:0', 'gte:budget_min'],
            'credits_cost' => ['required', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        return $data;
    }

    private function hasOverlap(float $min, ?float $max, ?int $ignoreId = null): bool
    {
        $query = TenderViewPricingRule::query()->active();

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        $query->where(function ($q) use ($min, $max) {
            // Overlap condition between [min, max] (max optional) and existing [budget_min, budget_max] (budget_max optional)
            // existing_min <= new_max (or new_max is infinity) AND existing_max >= new_min (or existing_max is infinity)
            if ($max !== null) {
                $q->where('budget_min', '<=', $max);
            }

            $q->where(function ($qq) use ($min) {
                $qq->whereNull('budget_max')->orWhere('budget_max', '>=', $min);
            });
        });

        return $query->exists();
    }
}

