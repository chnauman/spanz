<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CompanyDetail extends Model
{
    /** @var list<string>|null */
    private static ?array $australianStateNamesCache = null;

    protected $fillable = [
        'user_id',
        'company_name',
        'abn',
        'registration_number',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'phone',
        'website',
        'description',
        'headquarter_location',
        'employees_range',
        'main_industries',
        'subcategories_by_industry',
        'company_types',
        'yearly_revenue_range',
        'quality_certifications',
        'brands_represented',
        'industry_awards',
        'industry_memberships',
        'unique_value_propositions',
        'major_projects',
        'delivery_capabilities',
        'office_locations',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return list<string>
     */
    public static function resolveLocationSlugsForRow(CompanyDetail $detail): array
    {
        $slugs = [];
        $slugFromCountry = static::countryLabelToSlug($detail->country);
        if ($slugFromCountry) {
            $slugs[] = $slugFromCountry;
        }

        if (static::stateLooksAustralian($detail->state) && ! in_array('australia', $slugs, true)) {
            $slugs[] = 'australia';
        }

        $labels = Tender::locationSlugLabels();
        foreach ($labels as $slug => $label) {
            $hq = (string) ($detail->headquarter_location ?? '');
            if ($hq !== '' && stripos($hq, $label) !== false) {
                $slugs[] = $slug;
            }
        }

        return array_values(array_unique($slugs));
    }

    /**
     * Query scope: company row matches a tender search location slug (country / AU state / HQ text).
     */
    public function scopeWhereMatchesTenderLocationSlug(Builder $query, string $slug): Builder
    {
        $labels = Tender::locationSlugLabels();
        if (! isset($labels[$slug])) {
            return $query->whereRaw('1 = 0');
        }

        $label = $labels[$slug];
        $dbCountry = Tender::countryCodeToDbCountryName($slug);

        return $query->where(function (Builder $q) use ($slug, $label, $dbCountry) {
            if ($dbCountry !== '') {
                $q->where('country', $dbCountry);
                if ($slug === 'usa') {
                    $q->orWhere('country', 'United States');
                }
                if ($slug === 'uk') {
                    $q->orWhere('country', 'UK');
                }
            }

            $q->orWhere('headquarter_location', 'like', '%' . $label . '%');

            if ($slug === 'australia') {
                $auStates = static::australianStateNames();
                if ($auStates !== []) {
                    $q->orWhereIn('state', $auStates);
                }
            }
        });
    }

    private static function countryLabelToSlug(?string $country): ?string
    {
        if ($country === null) {
            return null;
        }
        $t = trim($country);
        if ($t === '' || strcasecmp($t, 'Not provided') === 0) {
            return null;
        }

        $map = [
            'Australia' => 'australia',
            'New Zealand' => 'new-zealand',
            'Singapore' => 'singapore',
            'USA' => 'usa',
            'United States' => 'usa',
            'United Kingdom' => 'uk',
            'UK' => 'uk',
            'Canada' => 'canada',
            'Germany' => 'germany',
            'France' => 'france',
        ];

        return $map[$t] ?? null;
    }

    private static function stateLooksAustralian(?string $state): bool
    {
        if ($state === null || trim($state) === '' || strcasecmp(trim($state), 'Not provided') === 0) {
            return false;
        }

        $t = trim($state);
        foreach (static::australianStateNames() as $name) {
            if (strcasecmp($t, $name) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return list<string>
     */
    private static function australianStateNames(): array
    {
        if (static::$australianStateNamesCache === null) {
            static::$australianStateNamesCache = State::where('country_name', 'Australia')
                ->orderBy('name')
                ->pluck('name')
                ->all();
        }

        return static::$australianStateNamesCache;
    }
}
