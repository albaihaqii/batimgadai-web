<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gadai;
use App\Models\Perpanjangan;
use App\Models\Sbg;
use App\Models\User;
use App\Helpers\HitungBiayaHelper;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PerpanjanganSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil gadai perpanjangan — tidak ambil jatuh_tempo supaya statusnya tetap jatuh_tempo
        $gadais = Gadai::with(['nasabah', 'branch'])
            ->where('status', 'perpanjangan')
            ->get();

        foreach ($gadais as $gadai) {
            $officer = User::where('role', 'officer')
                ->where('cabang_id', $gadai->cabang_id)
                ->first();

            if (!$officer || !$gadai->nilai_pinjaman) continue;

            $nilaiPinjaman   = (int) $gadai->nilai_pinjaman;
            $tipeJasa        = $gadai->tipe_jasa ?? 'umum';
            $tglPerpanjangan = Carbon::now()->subDays(rand(3, 10));
            $tglJtLama       = $gadai->tgl_jatuh_tempo
                ? Carbon::parse($gadai->tgl_jatuh_tempo)->subDays(30)
                : Carbon::now()->subDays(35);
            $tglJtBaru       = $tglPerpanjangan->copy()->addDays(30);

            $rate       = HitungBiayaHelper::getJasaRate($nilaiPinjaman, $tipeJasa);
            $jasaPersen = $rate['jasa_30_hari'];
            $jasaNominal = round($nilaiPinjaman * ($jasaPersen / 100));

            // Simulasikan sebagian telat
            $hariTerlambat = rand(0, 1) ? rand(1, 20) : 0;
            $dendaPersen   = 0;
            $dendaNominal  = 0;

            if ($hariTerlambat > 0 && $hariTerlambat <= 15) {
                $dendaPersen  = $rate['jasa_15_hari'];
                $dendaNominal = round($nilaiPinjaman * ($dendaPersen / 100));
            } elseif ($hariTerlambat > 15) {
                $dendaPersen  = $rate['jasa_30_hari'];
                $dendaNominal = round($nilaiPinjaman * ($dendaPersen / 100));
            }

            $totalBayar = $jasaNominal + $dendaNominal;

            $prefix = $tglPerpanjangan->format('ym') . strtoupper($gadai->branch->kode);
            $last   = Perpanjangan::where('no_sbg', 'like', $prefix . '%')->count();
            $noSbg  = $prefix . 'P' . str_pad($last + 1, 5, '0', STR_PAD_LEFT);

            $metodeBayar = rand(1, 10) <= 7 ? 'tunai' : 'midtrans';

            $perpanjangan = Perpanjangan::create([
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
                'total_bayar'       => $totalBayar,
                'tgl_perpanjangan'  => $tglPerpanjangan,
                'tgl_jt_lama'       => $tglJtLama,
                'tgl_jt_baru'       => $tglJtBaru,
                'status_bayar'      => 'berhasil',
                'metode_bayar'      => $metodeBayar,
                'midtrans_order_id' => $metodeBayar === 'midtrans'
                    ? 'PRP-' . $gadai->id . '-' . time() . rand(100, 999)
                    : null,
                'created_at' => $tglPerpanjangan,
                'updated_at' => $tglPerpanjangan,
            ]);

            Sbg::create([
                'no_sbg'        => $noSbg,
                'nasabah_id'    => $gadai->nasabah_id,
                'gadai_id'      => $gadai->id,
                'tipe'          => 'perpanjangan',
                'referensi_id'  => $perpanjangan->id,
                'tgl_transaksi' => $tglPerpanjangan,
                'qr_token'      => Str::uuid()->toString(),
            ]);
        }
    }
}