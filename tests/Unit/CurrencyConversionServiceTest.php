<?php

namespace Tests\Unit;

use App\Services\CurrencyConversionService;
use PHPUnit\Framework\TestCase;

class CurrencyConversionServiceTest extends TestCase
{
    public function test_aud_to_aud_conversion()
    {
        $result = CurrencyConversionService::convert(100.00, 'AUD', 'AUD');
        $this->assertEquals(100.00, $result);
    }

    public function test_only_aud_supported()
    {
        $this->expectException(\InvalidArgumentException::class);
        CurrencyConversionService::convert(100.00, 'CAD', 'AUD');
    }

    public function test_supported_currencies()
    {
        $currencies = CurrencyConversionService::getSupportedCurrencies();
        $this->assertContains('AUD', $currencies);
        $this->assertCount(1, $currencies);
    }

    public function test_currency_support_check()
    {
        $this->assertTrue(CurrencyConversionService::isSupported('AUD'));
        $this->assertFalse(CurrencyConversionService::isSupported('INVALID'));
    }
}
