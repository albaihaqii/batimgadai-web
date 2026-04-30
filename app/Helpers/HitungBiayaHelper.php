<?php

namespace App\Helpers;

use App\Models\Gadai;
use App\Models\JasaRate;
use Carbon\Carbon;

class HitungBiayaHelper
{
    /**
     * Tentukan tipe jasa berdasarkan kategori barang
     */
    public static function getTipeJasa(string $kategori): string
    {
        return $kategori === 'perhiasan' ? 'perhiasan' : 'umum';
    }

    /**
     * Ambil rate jasa berdasarkan nilai pinjaman dan tipe
     */
    public static function getJasaRate(int $nilaiPinjaman, string $tipe = 'umum'): array
    {
        $rate = JasaRate::getRate($nilaiPinjaman, $tipe);

        if (!$rate) {
            // Fallback default jika tidak ada rate
            return [
                'jasa_15_hari' => 5.00,
                'jasa_30_hari' => 5.00,
            ];
        }

        return [
            'jasa_15_hari' => $rate->jasa_15_hari,
            'jasa_30_hari' => $rate->jasa_30_hari,
        ];
    }

    /**
     * Hitung biaya perpanjangan
     * - Ontime / sebelum JT    : bayar jasa 30 hari saja
     * - Terlambat 1-15 hari    : jasa 30 hari + denda (jasa_15_hari sebagai denda)
     * - Terlambat 16-30 hari   : jasa 30 hari + denda (jasa_30_hari sebagai denda)
     */
    public static function hitungPerpanjangan(Gadai $gadai): array
    {
        $nilaiPinjaman = $gadai->nilai_pinjaman ?? 0;
        $tipeJasa      = $gadai->tipe_jasa ?? self::getTipeJasa($gadai->barang->kategori ?? 'handphone');
        $rate          = self::getJasaRate($nilaiPinjaman, $tipeJasa);

        $today         = Carbon::today();
        $tglJt         = $gadai->tgl_jatuh_tempo ? Carbon::parse($gadai->tgl_jatuh_tempo) : $today;
        $hariTerlambat = $today->gt($tglJt) ? $today->diffInDays($tglJt) : 0;

        // Jasa pokok = 30 hari
        $jasaPersen  = $rate['jasa_30_hari'];
        $jasaNominal = $nilaiPinjaman * ($jasaPersen / 100);

        // Denda keterlambatan
        $dendaPersen  = 0;
        $dendaNominal = 0;

        if ($hariTerlambat > 0 && $hariTerlambat <= 15) {
            // Terlambat 1-15 hari: denda = jasa_15_hari
            $dendaPersen  = $rate['jasa_15_hari'];
            $dendaNominal = $nilaiPinjaman * ($dendaPersen / 100);
        } elseif ($hariTerlambat > 15) {
            // Terlambat 16-30 hari: denda = jasa_30_hari
            $dendaPersen  = $rate['jasa_30_hari'];
            $dendaNominal = $nilaiPinjaman * ($dendaPersen / 100);
        }

        $totalBayar  = $jasaNominal + $dendaNominal;
        $tglJtBaru   = Carbon::today()->addDays(30);

        return [
            'nilai_pinjaman' => $nilaiPinjaman,
            'tipe_jasa'      => $tipeJasa,
            'jasa_persen'    => $jasaPersen,
            'jasa_nominal'   => round($jasaNominal),
            'denda_persen'   => $dendaPersen,
            'denda_nominal'  => round($dendaNominal),
            'hari_terlambat' => $hariTerlambat,
            'total_bayar'    => round($totalBayar),
            'tgl_jt_lama'    => $tglJt->format('d M Y'),
            'tgl_jt_baru'    => $tglJtBaru->format('d M Y'),
            'tgl_jt_baru_raw'=> $tglJtBaru,
        ];
    }

    /**
     * Hitung biaya pelunasan
     * - Ontime: bayar pokok + jasa 30 hari
     * - Terlambat 1-15 hari: pokok + jasa 30 hari + denda jasa_15_hari
     * - Terlambat 16-30 hari: pokok + jasa 30 hari + denda jasa_30_hari
     */
    public static function hitungPelunasan(Gadai $gadai): array
    {
        $nilaiPinjaman = $gadai->nilai_pinjaman ?? 0;
        $tipeJasa      = $gadai->tipe_jasa ?? self::getTipeJasa($gadai->barang->kategori ?? 'handphone');
        $rate          = self::getJasaRate($nilaiPinjaman, $tipeJasa);

        $today         = Carbon::today();
        $tglJt         = $gadai->tgl_jatuh_tempo ? Carbon::parse($gadai->tgl_jatuh_tempo) : $today;
        $hariTerlambat = $today->gt($tglJt) ? $today->diffInDays($tglJt) : 0;

        // Jasa pokok = 30 hari
        $jasaPersen  = $rate['jasa_30_hari'];
        $jasaNominal = $nilaiPinjaman * ($jasaPersen / 100);

        // Denda keterlambatan
        $dendaPersen  = 0;
        $dendaNominal = 0;

        if ($hariTerlambat > 0 && $hariTerlambat <= 15) {
            $dendaPersen  = $rate['jasa_15_hari'];
            $dendaNominal = $nilaiPinjaman * ($dendaPersen / 100);
        } elseif ($hariTerlambat > 15) {
            $dendaPersen  = $rate['jasa_30_hari'];
            $dendaNominal = $nilaiPinjaman * ($dendaPersen / 100);
        }

        $totalTebus = $nilaiPinjaman + $jasaNominal + $dendaNominal;

        return [
            'nilai_pinjaman' => $nilaiPinjaman,
            'tipe_jasa'      => $tipeJasa,
            'jasa_persen'    => $jasaPersen,
            'jasa_nominal'   => round($jasaNominal),
            'denda_persen'   => $dendaPersen,
            'denda_nominal'  => round($dendaNominal),
            'hari_terlambat' => $hariTerlambat,
            'total_tebus'    => round($totalTebus),
            'tgl_jt'         => $tglJt->format('d M Y'),
        ];
    }

    /**
     * Preview jasa rate untuk simulasi di form create gadai
     */
    public static function previewRate(int $nilaiPinjaman, string $tipe = 'umum'): array
    {
        $rate        = self::getJasaRate($nilaiPinjaman, $tipe);
        $jasa15      = round($nilaiPinjaman * ($rate['jasa_15_hari'] / 100));
        $jasa30      = round($nilaiPinjaman * ($rate['jasa_30_hari'] / 100));
        $totalTebus15 = $nilaiPinjaman + $jasa15;
        $totalTebus30 = $nilaiPinjaman + $jasa30;

        return [
            'jasa_15_hari'    => $rate['jasa_15_hari'],
            'jasa_30_hari'    => $rate['jasa_30_hari'],
            'jasa_nominal_15' => $jasa15,
            'jasa_nominal_30' => $jasa30,
            'total_tebus_15'  => $totalTebus15,
            'total_tebus_30'  => $totalTebus30,
        ];
    }
}