<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use App\Models\City;
use App\Models\State;

class Tender extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'budget',
        'currency',
        'deadline',
        'status',
        'requirements',
        'location',
        'country_code',
        'state_id',
        'city_id',
        'contact_email',
        'contact_phone',
        'request_type',
        'categories',
        'attachments',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'datetime',
            'budget' => 'decimal:2',
            'categories' => 'array',
            'attachments' => 'array',
        ];
    }

    // Override the getAttribute method to handle JSON decoding and datetime casting
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);
        
        if (in_array($key, ['categories', 'attachments']) && is_string($value)) {
            $decoded = json_decode($value, true);
            return $decoded !== null ? $decoded : [];
        }
        
        // Ensure deadline is always a Carbon instance
        if ($key === 'deadline' && is_string($value)) {
            return \Carbon\Carbon::parse($value);
        }
        
        return $value;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function invitations()
    {
        return $this->hasMany(TenderInvitation::class);
    }

    public function isActive()
    {
        return $this->status === 'active' && $this->deadline > now();
    }

    public function isExpired()
    {
        return $this->deadline < now();
    }

    public function getFormattedDeadline($format = 'd F Y')
    {
        if (!$this->deadline) {
            return 'Not specified';
        }
        
        return \Carbon\Carbon::parse($this->deadline)->format($format);
    }

    /**
     * Main listing headline: always the stored Title field only (never description).
     */
    public function titleHeadline(): string
    {
        $t = trim((string) ($this->title ?? ''));

        return $t !== '' ? $t : 'Untitled tender';
    }

    /**
     * Headline for emails / legacy contexts when Title may be empty.
     */
    public function cardTitle(): string
    {
        $t = trim((string) ($this->title ?? ''));
        if ($t !== '') {
            return $t;
        }

        $desc = trim(strip_tags((string) $this->description));
        if ($desc !== '') {
            return \Illuminate\Support\Str::limit($desc, 100);
        }

        return 'Tender #' . $this->id;
    }

    public function getCurrencyAttribute($value): string
    {
        return 'AUD';
    }

    public function setCurrencyAttribute($value): void
    {
        $this->attributes['currency'] = 'AUD';
    }

    /**
     * Human-readable budget band matching the create-tender form (stored value is the band anchor).
     */
    public function budgetRangeLabel(): string
    {
        if ($this->budget === null) {
            return '—';
        }

        $key = number_format((float) $this->budget, 2, '.', '');
        $c = $this->currency ?? 'AUD';

        $map = [
            '1000.00' => "Less than {$c} 1,000",
            '5000.00' => "{$c} 1,000 – 5,000",
            '10000.00' => "{$c} 5,000 – 10,000",
            '30000.00' => "{$c} 10,000 – 30,000",
            '50000.00' => "{$c} 30,000 – 50,000",
            '100000.00' => "{$c} 50,000 – 100,000",
            '500000.00' => "{$c} 100,000 – 500,000",
            '1000000.00' => "{$c} 500,000 – 1,000,000",
            '1000001.00' => "Over {$c} 1,000,000",
        ];

        return $map[$key] ?? "{$c} " . number_format((float) $this->budget, 0);
    }

    /**
     * Location option keys from tender create form → display labels.
     */
    public static function locationSlugLabels(): array
    {
        return [
            'australia' => 'Australia',
            'new-zealand' => 'New Zealand',
            'singapore' => 'Singapore',
            'usa' => 'United States',
            'uk' => 'United Kingdom',
            'canada' => 'Canada',
            'germany' => 'Germany',
            'france' => 'France',
        ];
    }

    /**
     * Country option key (create / filter) → State.country_name in location seed data.
     */
    public static function countryCodeToDbCountryName(string $code): string
    {
        return match ($code) {
            'australia' => 'Australia',
            'new-zealand' => 'New Zealand',
            'singapore' => 'Singapore',
            'usa' => 'USA',
            'uk' => 'United Kingdom',
            'canada' => 'Canada',
            'germany' => 'Germany',
            'france' => 'France',
            default => '',
        };
    }

    public function displayLocation(): string
    {
        if ($this->city_id) {
            $city = $this->relationLoaded('city')
                ? $this->city
                : City::with('state')->find($this->city_id);

            if ($city && $city->state) {
                $countryName = $city->state->country_name;
                if (strcasecmp($countryName, 'Australia') === 0) {
                    return $city->name . ', ' . $city->state->name . ', Australia';
                }

                return $city->name . ', ' . $countryName;
            }
        }

        $loc = $this->location;
        if ($loc === null || trim((string) $loc) === '') {
            return 'Location not specified';
        }

        $key = strtolower(trim((string) $loc));
        $map = static::locationSlugLabels();
        if (isset($map[$key])) {
            return $map[$key];
        }

        return \Illuminate\Support\Str::title(str_replace(['-', '_'], ' ', $key));
    }

    /**
     * Sub-category slug → label (matches tender create form options).
     *
     * @return array<string, string>
     */
    public static function subCategorySlugLabels(): array
    {
        return [
            'electrical' => 'Electrical',
            'mechanical' => 'Mechanical',
            'engines' => 'Engines',
            'avionics' => 'Avionics',
            'apus' => 'Auxiliary Power Units (APUs)',
            'navigation' => 'Navigation systems',
            'communication' => 'Communication systems (radio, satellite)',
        ];
    }

    /**
     * Category rows from the JSON column grouped by main category. Each line
     * represents one stored category bundle: a main category plus up to 3
     * selected subcategories plus a single budget-share percentage.
     *
     * - `sub_labels` (array<string>) holds every resolved subcategory name in
     *   the bundle (preferred for new views that want to list them).
     * - `sub_label` (string) is a comma-joined fallback string kept for
     *   backwards compatibility with views that expected a single label.
     *
     * @return Collection<int, array{
     *     main_name: string,
     *     lines: Collection<int, array{sub_labels: array<int, string>, sub_label: string, pct: string}>
     * }>
     */
    public function categoriesGroupedForDisplay(): Collection
    {
        $rows = is_array($this->categories) ? $this->categories : [];
        $slugLabels = self::subCategorySlugLabels();

        $grouped = collect($rows)
            ->filter(fn ($row) => is_array($row))
            ->groupBy(fn ($row) => (int) ($row['main_category'] ?? 0));

        $ids = $grouped->keys()->filter(fn ($id) => (int) $id > 0)->values();
        $names = $ids->isNotEmpty()
            ? Category::whereIn('id', $ids->all())->pluck('name', 'id')
            : collect();

        return $grouped
            ->map(function ($items, $mainId) use ($names, $slugLabels) {
                $mainId = (int) $mainId;
                if ($mainId < 1) {
                    return null;
                }

                $mainName = $names[$mainId] ?? ('Category #' . $mainId);

                $lines = collect($items)->map(function ($row) use ($slugLabels) {
                    // Resolve percentage once per row (applies to the entire
                    // subcategory bundle).
                    $pctRaw = $row['product_type'] ?? $row['percentage'] ?? $row['percent'] ?? null;
                    $pct = null;
                    if (is_numeric($pctRaw)) {
                        $pctNum = (int) $pctRaw;
                        $pct = $pctNum === 5 ? '<10%' : ($pctNum . '%');
                    } elseif (is_string($pctRaw) && trim($pctRaw) !== '') {
                        $pct = trim($pctRaw);
                    }

                    // New schema stores `sub_categories` (array of labels).
                    // Legacy schema stored `sub_category` (single string).
                    $rawLabels = [];
                    if (isset($row['sub_categories']) && is_array($row['sub_categories'])) {
                        $rawLabels = $row['sub_categories'];
                    } else {
                        $legacy = $row['sub_category'] ?? $row['work'] ?? $row['type'] ?? null;
                        if (is_string($legacy) && $legacy !== '') {
                            $rawLabels = [$legacy];
                        }
                    }

                    $resolveLabel = function ($raw) use ($slugLabels) {
                        if (!is_string($raw) || $raw === '') {
                            return null;
                        }
                        $key = strtolower($raw);
                        return $slugLabels[$key] ?? ucwords(str_replace(['_', '-'], ' ', $raw));
                    };

                    $resolvedLabels = collect($rawLabels)
                        ->map(fn ($raw) => $resolveLabel($raw))
                        ->filter()
                        ->values()
                        ->all();

                    if (empty($resolvedLabels) && !$pct) {
                        return null;
                    }

                    // `sub_labels` is the canonical list (array) of resolved
                    // subcategory display names for this category bundle.
                    // `sub_label` (singular) is a comma-joined string kept
                    // around for backward compatibility with older views.
                    return [
                        'sub_labels' => $resolvedLabels,
                        'sub_label' => $resolvedLabels ? implode(', ', $resolvedLabels) : '—',
                        'pct' => $pct ?: '—',
                    ];
                })->filter()->values();

                if ($lines->isEmpty()) {
                    return null;
                }

                return [
                    'main_name' => $mainName,
                    'lines' => $lines,
                ];
            })
            ->filter()
            ->values();
    }

    /**
     * Numeric min/max for the tender's budget band (matches post-tender form anchors).
     *
     * @return array{0: float, 1: float}|null
     */
    public function budgetBandMinMax(): ?array
    {
        if ($this->budget === null) {
            return null;
        }

        $key = number_format((float) $this->budget, 2, '.', '');
        $bands = [
            '1000.00' => [0.0, 1000.0],
            '5000.00' => [1000.0, 5000.0],
            '10000.00' => [5000.0, 10000.0],
            '30000.00' => [10000.0, 30000.0],
            '50000.00' => [30000.0, 50000.0],
            '100000.00' => [50000.0, 100000.0],
            '500000.00' => [100000.0, 500000.0],
            '1000000.00' => [500000.0, 1000000.0],
            '1000001.00' => [1000000.0, 3000000.0],
        ];

        return $bands[$key] ?? [0.0, max(0.0, (float) $this->budget)];
    }

    /**
     * Parses display pct like "30%" or "<10%" to a number for bars/ranges.
     */
    public static function parsePctDisplayToNumber(string $pct): float
    {
        $pct = trim($pct);
        if ($pct === '' || $pct === '—') {
            return 0.0;
        }
        if (stripos($pct, '<') !== false) {
            return 10.0;
        }
        if (preg_match('/(\d+(?:\.\d+)?)/', $pct, $m)) {
            return min(100.0, max(0.0, (float) $m[1]));
        }

        return 0.0;
    }

    /**
     * Allocated AUD range for a percentage of the total budget band (indicative).
     */
    public function formatAllocatedBudgetRange(float $percentOfTotal): ?string
    {
        $band = $this->budgetBandMinMax();
        if (!$band) {
            return null;
        }

        $p = min(100.0, max(0.0, $percentOfTotal)) / 100.0;
        $c = $this->currency ?? 'AUD';
        $lo = (int) round($band[0] * $p);
        $hi = (int) round($band[1] * $p);

        return $c . ' ' . number_format($lo) . ' – ' . number_format($hi);
    }
}
