<?php

namespace App\Support;

class CompanyEmail
{
    public static function domainFrom(string $email): ?string
    {
        $email = strtolower(trim($email));

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return null;
        }

        $parts = explode('@', $email, 2);

        return $parts[1] ?? null;
    }

    public static function localPartFrom(string $email): ?string
    {
        $email = strtolower(trim($email));

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return null;
        }

        $parts = explode('@', $email, 2);

        return $parts[0] ?? null;
    }

    public static function build(string $localPart, string $domain): string
    {
        return strtolower(trim($localPart)) . '@' . strtolower(trim(ltrim($domain, '@')));
    }

    /**
     * @return list<string>
     */
    public static function blockedDomains(): array
    {
        return config('company_email.blocked_domains', []);
    }

    public static function isBlockedDomain(string $domain): bool
    {
        return in_array(strtolower(trim($domain)), self::blockedDomains(), true);
    }

    public static function isCompanyEmail(string $email): bool
    {
        $domain = self::domainFrom($email);

        if ($domain === null || $domain === '') {
            return false;
        }

        return ! self::isBlockedDomain($domain);
    }

    public static function matchesDomain(string $email, string $requiredDomain): bool
    {
        $domain = self::domainFrom($email);
        $requiredDomain = strtolower(trim(ltrim($requiredDomain, '@')));

        return $domain !== null && $domain === $requiredDomain;
    }

    public static function validationMessage(): string
    {
        return 'Please use your company email address. Personal email providers (Gmail, Yahoo, Hotmail, etc.) are not allowed.';
    }
}
