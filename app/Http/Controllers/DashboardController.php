<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        return view('pages.dashboard');
    }

    public function superadmin()
    {
        $goldRateUsd = null;
        $goldRateIdr = null;
        $usdIdrRate = null;
        $goldError = null;

        $apiKey = env('GOLD_API_KEY');

        if ($apiKey) {
            try {
                $goldResponse = Http::timeout(10)
                    ->withHeaders(['x-access-token' => $apiKey])
                    ->get('https://api.gold-api.com/price/XAU/USD');

                if ($goldResponse->successful()) {
                    $goldPayload = $goldResponse->json();
                    $goldRateUsd = data_get($goldPayload, 'price') ?? data_get($goldPayload, 'amount') ?? data_get($goldPayload, 'ask') ?? data_get($goldPayload, 'bid');

                    if (! is_numeric($goldRateUsd)) {
                        $goldRateUsd = null;
                        $goldError = 'Respons API emas tidak valid.';
                    }
                } else {
                    $goldError = 'Gagal mengambil harga emas dari API.';
                }
            } catch (\Throwable $error) {
                $goldError = 'Gagal terhubung ke API emas.';
                report($error);
            }
        } else {
            $goldError = 'GOLD_API_KEY belum diset di .env.';
        }

        if ($goldRateUsd) {
            try {
                $rateResponse = Http::timeout(10)
                    ->get('https://open.er-api.com/v6/latest/USD');

                if ($rateResponse->successful()) {
                    $usdIdrRate = data_get($rateResponse->json(), 'rates.IDR');
                    if (is_numeric($usdIdrRate)) {
                        $goldRateIdr = $goldRateUsd * $usdIdrRate;
                    } else {
                        $goldError = $goldError ?: 'Gagal mengonversi USD ke IDR.';
                    }
                } else {
                    $goldError = $goldError ?: 'Gagal mengambil kurs USD/IDR.';
                }
            } catch (\Throwable $error) {
                $goldError = $goldError ?: 'Gagal mengambil kurs USD/IDR.';
                report($error);
            }
        }

        return view('superadmin.dashboard', compact(
            'goldRateUsd',
            'goldRateIdr',
            'usdIdrRate',
            'goldError'
        ));
    }
}
