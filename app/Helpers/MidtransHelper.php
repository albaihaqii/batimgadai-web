<?php

namespace App\Helpers;

use Midtrans\Config;
use Midtrans\Snap;

class MidtransHelper
{
    public static function setup(): void
    {
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized  = config('services.midtrans.is_sanitized');
        Config::$is3ds        = config('services.midtrans.is_3ds');
    }

    public static function createSnapToken(array $params): string
    {
        self::setup();
        return Snap::getSnapToken($params);
    }

    public static function createTransaction(array $params): object
    {
        self::setup();
        return Snap::createTransaction($params);
    }
}