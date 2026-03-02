@php
    /** @var \App\Models\TenderViewPricingRule|null $rule */
    $isEdit = isset($rule) && $rule;
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label for="budget_min" class="block text-sm font-medium text-gray-700 mb-2">Budget Min (USD)</label>
        <input
            type="number"
            step="0.01"
            min="0"
            id="budget_min"
            name="budget_min"
            value="{{ old('budget_min', $isEdit ? $rule->budget_min : '') }}"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="e.g., 1000"
            required
        >
    </div>

    <div>
        <label for="budget_max" class="block text-sm font-medium text-gray-700 mb-2">Budget Max (USD) (optional)</label>
        <input
            type="number"
            step="0.01"
            min="0"
            id="budget_max"
            name="budget_max"
            value="{{ old('budget_max', $isEdit ? $rule->budget_max : '') }}"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="Leave empty for 'and above'"
        >
        <p class="text-xs text-gray-500 mt-1">If empty, this rule will apply to any budget above the minimum.</p>
    </div>

    <div>
        <label for="credits_cost" class="block text-sm font-medium text-gray-700 mb-2">Credits Cost Per View</label>
        <input
            type="number"
            min="0"
            id="credits_cost"
            name="credits_cost"
            value="{{ old('credits_cost', $isEdit ? $rule->credits_cost : 1) }}"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            required
        >
    </div>

    <div>
        <label for="is_active" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
        <select
            id="is_active"
            name="is_active"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        >
            @php($activeValue = old('is_active', $isEdit ? (int) $rule->is_active : 1))
            <option value="1" {{ (string) $activeValue === '1' ? 'selected' : '' }}>Active</option>
            <option value="0" {{ (string) $activeValue === '0' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
</div>

