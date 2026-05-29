<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gadai;
use App\Models\Pelunasan;
use App\Models\Locker;
use App\Models\Sbg;
use App\Models\User;
use App\Helpers\HitungBiayaHelper;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PelunasanSeeder extends Seeder
{
    public function run(): void
    {
        $gadais = Gadai::with(['nasabah', 'branch', 'loker'])
            ->where('status', 'lunas')
            ->get();

        foreach ($gadais as $gadai) {
            $officer = User::where('role', 'officer')
                ->where('cabang_id', $gadai->cabang_id)
                ->first();

            if (!$officer || !$gadai->nilai_pinjaman) continue;
            if (Pelunasan::where('gadai_id', $gadai->id)->exists()) continue;

            $nilaiPinjaman = (int) $gadai->nilai_pinjaman;
            $tipeJasa      = $gadai->tipe_jasa ?? 'umum';
            $tglPelunasan  = Carbon::create(2026, rand(2, 5), rand(1, 28));
            $tglJt         = $gadai->tgl_jatuh_tempo ? Carbon::parse($gadai->tgl_jatuh_tempo) : $tglPelunasan->copy()->addDays(10);

            $rate        = HitungBiayaHelper::getJasaRate($nilaiPinjaman, $tipeJasa);
            $jasaPersen  = $rate['jasa_30_hari'];
            $jasaNominal = round($nilaiPinjaman * ($jasaPersen / 100));

            $hariTerlambat = rand(0, 1) ? rand(1, 15) : 0;
            $dendaPersen   = 0;
            $dendaNominal  = 0;

            if ($hariTerlambat > 0 && $hariTerlambat <= 15) {
                $dendaPersen  = $rate['jasa_15_hari'];
                $dendaNominal = round($nilaiPinjaman * ($dendaPersen / 100));
            } elseif ($hariTerlambat > 15) {
                $dendaPersen  = $rate['jasa_30_hari'];
                $dendaNominal = round($nilaiPinjaman * ($dendaPersen / 100));
            }

            $totalTebus  = $nilaiPinjaman + $jasaNominal + $dendaNominal;
            $metodeBayar = rand(1, 10) <= 7 ? 'tunai' : 'midtrans';

            $prefix = $tglPelunasan->format('ym') . strtoupper($gadai->branch->kode);
            $last   = Pelunasan::where('no_sbg', 'like', $prefix . '%')->count();
            $noSbg  = $prefix . 'L' . str_pad($last + 1, 5, '0', STR_PAD_LEFT);

            $pelunasan = Pelunasan::create([
                'gadai_id'          => $gadai->id,
                'nasabah_id'        => $gadai->nasabah_id,
                'officer_id'        => $officer->id,
                'no_sbg'            => $noSbg,
                'nilai_pinjaman'    => $nilaiPinjaman,
                'jasa_persen'       => $jasaPersen,
                'jasa_nominal'      => $jasaNominal,
                'denda_persen'      => $dendaPersen,
                'denda_nominal'     => $dendaNominal,
                'hari_terlambat'    => $hariTerlambat,
                'total_tebus'       => $totalTebus,
                'tgl_pelunasan'     => $tglPelunasan,
                'tgl_jt'            => $tglJt,
                'status_bayar'      => 'berhasil',
                'metode_bayar'      => $metodeBayar,
                'midtrans_order_id' => $metodeBayar === 'midtrans' ? 'LNS-' . $gadai->id . '-' . time() . rand(100, 999) : null,
                'created_at'        => $tglPelunasan,
                'updated_at'        => $tglPelunasan,
            ]);

            if ($gadai->loker_id) {
                Locker::where('id', $gadai->loker_id)->update(['status' => 'kosong', 'gadai_id' => null]);
            }

            Sbg::create([
                'no_sbg'        => $noSbg,
                'nasabah_id'    => $gadai->nasabah_id,
                'gadai_id'      => $gadai->id,
                'tipe'          => 'pelunasan',
                'referensi_id'  => $pelunasan->id,
                'tgl_transaksi' => $tglPelunasan,
                'qr_token'      => Str::uuid()->toString(),
            ]);
        }
    }
}