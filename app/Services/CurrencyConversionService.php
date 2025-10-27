<?php

namespace App\Services;

class CurrencyConversionService
{
    /**
     * Exchange rates relative to USD (as of 2024)
     * These should be updated regularly or fetched from an API
     */
    private static $exchangeRates = [
        'USD' => 1.0,
        'AUD' => 1.52,  // 1 USD = 1.52 AUD
        'EUR' => 0.92,  // 1 USD = 0.92 EUR
        'GBP' => 0.79,  // 1 USD = 0.79 GBP
        'SGD' => 1.35,  // 1 USD = 1.35 SGD
        'NZD' => 1.62,  // 1 USD = 1.62 NZD
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
        // If same currency, return as is
        if ($fromCurrency === $toCurrency) {
            return $amount;
        }

        // Convert to USD first, then to target currency
        $usdAmount = $amount / self::$exchangeRates[$fromCurrency];
        $convertedAmount = $usdAmount * self::$exchangeRates[$toCurrency];

        return round($convertedAmount, 2);
    }

    /**
     * Convert amount to USD
     *
     * @param float $amount
     * @param string $fromCurrency
     * @return float
     */
    public static function toUSD(float $amount, string $fromCurrency): float
    {
        return $amount / self::$exchangeRates[$fromCurrency];
    }

    /**
     * Convert amount from USD
     *
     * @param float $amount
     * @param string $toCurrency
     * @return float
     */
    public static function fromUSD(float $amount, string $toCurrency): float
    {
        return $amount * self::$exchangeRates[$toCurrency];
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
