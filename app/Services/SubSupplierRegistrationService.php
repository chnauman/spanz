<?php

namespace App\Services;

use App\Models\CompanyDetail;
use App\Models\RegistrationProgress;
use App\Models\SupplierInvitation;
use App\Models\User;

class SubSupplierRegistrationService
{
    /**
     * @return array<string, mixed>
     */
    public static function companyPrefillFromParent(User $parentSupplier): array
    {
        $company = $parentSupplier->companyDetail;

        if (! $company) {
            return [
                'registered_business_name' => '',
                'business_address' => '',
                'country' => $parentSupplier->country ?? '',
                'state' => $parentSupplier->state ?? '',
                'city' => $parentSupplier->city ?? '',
            ];
        }

        return [
            'registered_business_name' => $company->company_name,
            'business_address' => $company->address,
            'country' => $company->country ?? $parentSupplier->country ?? '',
            'state' => $company->state ?? $parentSupplier->state ?? '',
            'city' => $company->city ?? $parentSupplier->city ?? '',
        ];
    }

    public static function copyCompanyDetailFromParent(User $subSupplier, User $parentSupplier): CompanyDetail
    {
        $parentCompany = $parentSupplier->companyDetail;

        $attributes = [
            'user_id' => $subSupplier->id,
            'company_name' => $parentCompany?->company_name ?? 'Company',
            'abn' => $parentCompany?->abn,
            'registration_number' => $parentCompany?->registration_number,
            'address' => $parentCompany?->address ?? '',
            'city' => $parentCompany?->city ?? '',
            'state' => $parentCompany?->state ?? '',
            'postal_code' => $parentCompany?->postal_code ?? '',
            'country' => $parentCompany?->country ?? '',
            'phone' => $subSupplier->phone,
            'website' => $parentCompany?->website,
            'description' => $parentCompany?->description,
            'headquarter_location' => $parentCompany?->headquarter_location,
            'employees_range' => $parentCompany?->employees_range,
            'main_industries' => $parentCompany?->main_industries,
            'subcategories_by_industry' => $parentCompany?->subcategories_by_industry,
            'profile_category_ids' => $parentCompany?->profile_category_ids,
            'profile_subcategory_ids' => $parentCompany?->profile_subcategory_ids,
            'company_types' => $parentCompany?->company_types,
            'yearly_revenue_range' => $parentCompany?->yearly_revenue_range,
            'quality_certifications' => $parentCompany?->quality_certifications,
            'brands_represented' => $parentCompany?->brands_represented,
            'industry_awards' => $parentCompany?->industry_awards,
            'industry_memberships' => $parentCompany?->industry_memberships,
            'unique_value_propositions' => $parentCompany?->unique_value_propositions,
            'major_projects' => $parentCompany?->major_projects,
            'delivery_capabilities' => $parentCompany?->delivery_capabilities,
            'office_locations' => $parentCompany?->office_locations,
        ];

        return CompanyDetail::updateOrCreate(
            ['user_id' => $subSupplier->id],
            $attributes
        );
    }

    public static function applyProgressCompanyData(RegistrationProgress $progress, User $parentSupplier): void
    {
        $prefill = self::companyPrefillFromParent($parentSupplier);

        $progress->update([
            'registered_business_name' => $prefill['registered_business_name'],
            'business_address' => $prefill['business_address'],
            'country' => $prefill['country'],
            'state' => $prefill['state'],
            'city' => $prefill['city'],
        ]);
    }

    public static function resolveInvitation(?string $token): ?SupplierInvitation
    {
        if (! $token) {
            return null;
        }

        return SupplierInvitation::where('token', $token)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->with('supplier.companyDetail')
            ->first();
    }
}
