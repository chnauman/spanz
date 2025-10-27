<?php

namespace Tests\Unit;

use App\Services\CurrencyConversionService;
use PHPUnit\Framework\TestCase;

class CurrencyConversionServiceTest extends TestCase
{
    public function test_same_currency_conversion()
    {
        $result = CurrencyConversionService::convert(100.00, 'USD', 'USD');
        $this->assertEquals(100.00, $result);
    }

    public function test_usd_to_aud_conversion()
    {
        $result = CurrencyConversionService::convert(100.00, 'USD', 'AUD');
        $this->assertEquals(152.00, $result);
    }

    public function test_aud_to_usd_conversion()
    {
        $result = CurrencyConversionService::convert(152.00, 'AUD', 'USD');
        $this->assertEquals(100.00, $result);
    }

    public function test_eur_to_gbp_conversion()
    {
        $result = CurrencyConversionService::convert(100.00, 'EUR', 'GBP');
        // 100 EUR = 100/0.92 USD = 108.70 USD
        // 108.70 USD = 108.70 * 0.79 GBP = 85.87 GBP
        $this->assertEquals(85.87, $result);
    }

    public function test_supported_currencies()
    {
        $currencies = CurrencyConversionService::getSupportedCurrencies();
        $this->assertContains('USD', $currencies);
        $this->assertContains('AUD', $currencies);
        $this->assertContains('EUR', $currencies);
        $this->assertContains('GBP', $currencies);
        $this->assertContains('SGD', $currencies);
        $this->assertContains('NZD', $currencies);
    }

    public function test_currency_support_check()
    {
        $this->assertTrue(CurrencyConversionService::isSupported('USD'));
        $this->assertTrue(CurrencyConversionService::isSupported('AUD'));
        $this->assertFalse(CurrencyConversionService::isSupported('INVALID'));
    }
}
