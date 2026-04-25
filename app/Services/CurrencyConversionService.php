<?php

namespace App\Services;

class CurrencyConversionService
{
    /**
     * Only Australian Dollar is supported.
     */
    private static $exchangeRates = [
        'AUD' => 1.0,
    ];

    /**
     * Convert amount from one currency to another
     *
     * @param float $amount
     * @param string $fromCurrency
     * @param string $toCurrency
     * @return float
     */
    public static function convert(float $amount, string $fromCurrency, string $toCurrency): float
    {
        if (!self::isSupported($fromCurrency) || !self::isSupported($toCurrency)) {
            throw new \InvalidArgumentException('Only AUD currency is supported.');
        }

        return round($amount, 2);
    }

    /**
     * Keep compatibility for existing callers.
     *
     * @param float $amount
     * @param string $fromCurrency
     * @return float
     */
    public static function toAud(float $amount, string $fromCurrency): float
    {
        return self::convert($amount, $fromCurrency, 'AUD');
    }

    /**
     * Keep compatibility for existing callers.
     *
     * @param float $amount
     * @param string $toCurrency
     * @return float
     */
    public static function fromAud(float $amount, string $toCurrency): float
    {
        return self::convert($amount, 'AUD', $toCurrency);
    }

    /**
     * Get supported currencies
     *
     * @return array
     */
    public static function getSupportedCurrencies(): array
    {
        return array_keys(self::$exchangeRates);
    }

    /**
     * Check if currency is supported
     *
     * @param string $currency
     * @return bool
     */
    public static function isSupported(string $currency): bool
    {
        return isset(self::$exchangeRates[$currency]);
    }
}
