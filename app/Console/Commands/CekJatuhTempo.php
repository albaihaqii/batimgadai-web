<?php

namespace App\Console\Commands;

use App\Models\Gadai;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CekJatuhTempo extends Command
{
    protected $signature   = 'gadai:cek-jatuh-tempo';
    protected $description = 'Cek gadai mendekati jatuh tempo dan kirim notifikasi ke nasabah';

    public function handle(): void
    {
        $today   = Carbon::today();
        $targets = [7, 3, 1, 0];

        foreach ($targets as $hari) {
            $tanggal = $today->copy()->addDays($hari);

            $gadais = Gadai::with('nasabah')
                ->whereIn('status', ['aktif', 'perpanjangan'])
                ->whereDate('tgl_jatuh_tempo', $tanggal)
                ->get();

            foreach ($gadais as $gadai) {
                if (!$gadai->nasabah_id) continue;

                $sudahAda = Notification::where('tipe_penerima', 'nasabah')
                    ->where('penerima_id', $gadai->nasabah_id)
                    ->where('referensi_tipe', 'gadai')
                    ->where('referensi_id', $gadai->id)
                    ->where('tipe_notif', 'jatuh_tempo')
                    ->whereDate('created_at', $today)
                    ->exists();

                if ($sudahAda) continue;

                $judul = $hari === 0
                    ? 'Jatuh Tempo Hari Ini!'
                    : 'Pengingat Jatuh Tempo (H-' . $hari . ')';

                $pesan = 'Gadai ' . $gadai->no_sbg . ' akan jatuh tempo pada '
                    . $gadai->tgl_jatuh_tempo->format('d M Y')
                    . '. Segera perpanjang atau lunasi.';

                Notification::kirimKeNasabah(
                    $gadai->nasabah_id,
                    $judul, $pesan,
                    'jatuh_tempo', 'gadai', $gadai->id
                );
            }
        }

        Gadai::whereIn('status', ['aktif', 'perpanjangan'])
            ->whereDate('tgl_jatuh_tempo', '<', $today)
            ->update(['status' => 'jatuh_tempo']);

        $this->info('Selesai: ' . now()->format('d M Y H:i'));
    }
}