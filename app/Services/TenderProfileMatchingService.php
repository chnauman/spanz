<?php

namespace App\Services;

use App\Models\Category;
use App\Models\CompanyDetail;
use App\Models\Tender;
use App\Models\User;
use Illuminate\Support\Collection;

class TenderProfileMatchingService
{
    /**
     * Users whose strengthen-profile categories match this tender (excludes poster).
     *
     * @return Collection<int, User>
     */
    public function matchingUsers(Tender $tender): Collection
    {
        $tenderMainIds = $this->tenderMainCategoryIds($tender);
        $tenderSubIds = $this->tenderSubcategoryIds($tender);

        if ($tenderMainIds === [] && $tenderSubIds === []) {
            return collect();
        }

        $users = User::query()
            ->where('id', '!=', $tender->user_id)
            ->whereHas('companyDetail', function ($q) {
                $q->where(function ($inner) {
                    $inner->whereNotNull('profile_category_ids')
                        ->orWhereNotNull('profile_subcategory_ids');
                });
            })
            ->with('companyDetail')
            ->get();

        return $users->filter(function (User $user) use ($tenderMainIds, $tenderSubIds) {
            return $this->userMatchesTender($user->companyDetail, $tenderMainIds, $tenderSubIds);
        })->values();
    }

    /**
     * @return list<int>
     */
    public function tenderMainCategoryIds(Tender $tender): array
    {
        $ids = [];

        if ($tender->category_id) {
            $ids[] = (int) $tender->category_id;
        }

        $bundles = $this->decodeCategories($tender->categories);
        foreach ($bundles as $bundle) {
            if (!empty($bundle['main_category'])) {
                $ids[] = (int) $bundle['main_category'];
            }
        }

        return array_values(array_unique($ids));
    }

    /**
     * Resolve tender subcategory names to category IDs.
     *
     * @return list<int>
     */
    public function tenderSubcategoryIds(Tender $tender): array
    {
        $ids = [];
        $bundles = $this->decodeCategories($tender->categories);

        foreach ($bundles as $bundle) {
            $mainId = !empty($bundle['main_category']) ? (int) $bundle['main_category'] : null;
            $subs = $bundle['sub_categories'] ?? [];
            if (!is_array($subs)) {
                continue;
            }
            foreach ($subs as $sub) {
                if (is_numeric($sub)) {
                    $ids[] = (int) $sub;
                    continue;
                }
                $name = trim((string) $sub);
                if ($name === '') {
                    continue;
                }
                $query = Category::query()->where('name', $name);
                if ($mainId) {
                    $query->where('parent_category_id', $mainId);
                }
                $cat = $query->first();
                if ($cat) {
                    $ids[] = (int) $cat->id;
                }
            }
        }

        return array_values(array_unique($ids));
    }

    /**
     * @param  list<int>  $tenderMainIds
     * @param  list<int>  $tenderSubIds
     */
    public function userMatchesTender(?CompanyDetail $detail, array $tenderMainIds, array $tenderSubIds): bool
    {
        if (!$detail) {
            return false;
        }

        $userMainIds = $this->decodeIdList($detail->profile_category_ids);
        $userSubIds = $this->decodeIdList($detail->profile_subcategory_ids);

        if ($userMainIds === [] && $userSubIds === []) {
            return false;
        }

        if ($userSubIds !== [] && $tenderSubIds !== []) {
            if (array_intersect($userSubIds, $tenderSubIds) !== []) {
                return true;
            }
        }

        if ($userMainIds !== [] && $tenderMainIds !== []) {
            $matchedMains = array_intersect($userMainIds, $tenderMainIds);
            if ($matchedMains === []) {
                return false;
            }

            foreach ($matchedMains as $mainId) {
                $userSubsForMain = $this->userSubcategoryIdsForParent($userSubIds, (int) $mainId);
                if ($userSubsForMain === []) {
                    return true;
                }
                if ($tenderSubIds !== [] && array_intersect($userSubsForMain, $tenderSubIds) !== []) {
                    return true;
                }
                if ($tenderSubIds === []) {
                    return true;
                }
            }
        }

        if ($userSubIds !== [] && $tenderMainIds !== []) {
            foreach ($tenderMainIds as $mainId) {
                $parentOfUserSubs = Category::query()
                    ->whereIn('id', $userSubIds)
                    ->where('parent_category_id', $mainId)
                    ->exists();
                if ($parentOfUserSubs) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param  list<int>  $userSubIds
     * @return list<int>
     */
    private function userSubcategoryIdsForParent(array $userSubIds, int $parentId): array
    {
        if ($userSubIds === []) {
            return [];
        }

        return Category::query()
            ->whereIn('id', $userSubIds)
            ->where('parent_category_id', $parentId)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function decodeCategories(mixed $raw): array
    {
        if (is_string($raw)) {
            $decoded = json_decode($raw, true);
            return is_array($decoded) ? $decoded : [];
        }

        return is_array($raw) ? $raw : [];
    }

    /**
     * @return list<int>
     */
    private function decodeIdList(mixed $raw): array
    {
        if (is_string($raw)) {
            $raw = json_decode($raw, true);
        }
        if (!is_array($raw)) {
            return [];
        }

        return array_values(array_unique(array_filter(array_map('intval', $raw))));
    }
}
