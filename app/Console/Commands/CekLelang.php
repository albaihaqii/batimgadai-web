<?php

namespace App\Console\Commands;

use App\Models\Gadai;
use App\Models\Lelang;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CekLelang extends Command
{
    protected $signature   = 'gadai:cek-lelang';
    protected $description = 'Cek gadai jatuh tempo lebih dari 120 hari dan masukkan ke lelang';

    public function handle(): void
    {
        $batas = Carbon::today()->subDays(120);

        $gadais = Gadai::with(['nasabah', 'loker'])
            ->where('status', 'jatuh_tempo')
            ->whereDate('tgl_jatuh_tempo', '<=', $batas)
            ->whereDoesntHave('lelang')
            ->get();

        foreach ($gadais as $gadai) {

            // Hitung sisa hutang
            $sisaHutang = (float) ($gadai->nilai_pinjaman ?? 0)
                        + (float) ($gadai->jasa_nominal ?? 0);

            // Buat record lelang
            Lelang::create([
                'gadai_id'        => $gadai->id,
                'nasabah_id'      => $gadai->nasabah_id,
                'no_sbg'          => $gadai->no_sbg,
                'tgl_jatuh_tempo' => $gadai->tgl_jatuh_tempo,
                'sisa_hutang'     => $sisaHutang,
                'status'          => 'proses',
            ]);

            // Update status gadai
            $gadai->update(['status' => 'lelang']);

            // Kosongkan loker
            if ($gadai->loker) {
                $gadai->loker->update([
                    'status'   => 'kosong',
                    'gadai_id' => null,
                ]);
            }

            // Kirim notifikasi ke nasabah
            if ($gadai->nasabah) {
                Notification::create([
                    'tipe_penerima' => 'nasabah',
                    'penerima_id'   => $gadai->nasabah_id,
                    'tipe_notif'    => 'lelang',
                    'judul'         => 'Barang Anda Dilelang',
                    'pesan'         => 'Barang gadai No. SBG ' . $gadai->no_sbg . ' telah diproses untuk dilelang karena melewati batas waktu 120 hari.',
                    'is_read'       => false,
                ]);
            }
        }

        $this->info('Selesai: ' . $gadais->count() . ' gadai diproses ke lelang — ' . now()->format('d M Y H:i'));
    }
}