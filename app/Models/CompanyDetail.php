<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyDetail extends Model
{
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
}
