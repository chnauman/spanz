<?php

namespace App\Rules;

use App\Support\CompanyEmail as CompanyEmailSupport;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CompanyEmail implements ValidationRule
{
    public function __construct(
        protected ?string $requiredDomain = null
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || ! filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $fail('Please enter a valid email address.');

            return;
        }

        if (! CompanyEmailSupport::isCompanyEmail($value)) {
            $fail(CompanyEmailSupport::validationMessage());

            return;
        }

        if ($this->requiredDomain !== null && ! CompanyEmailSupport::matchesDomain($value, $this->requiredDomain)) {
            $fail('The email must use your company domain (@' . ltrim($this->requiredDomain, '@') . ').');
        }
    }
}
