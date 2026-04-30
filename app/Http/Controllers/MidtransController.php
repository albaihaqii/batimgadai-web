<?php

namespace App\Http\Controllers;

use App\Models\Perpanjangan;
use App\Models\Pelunasan;
use App\Models\Locker;
use App\Models\Sbg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        Log::info('Midtrans callback received', $request->all());

        $serverKey = config('services.midtrans.server_key');
        $hashedKey = hash('sha512',
            $request->order_id .
            $request->status_code .
            $request->gross_amount .
            $serverKey
        );

        if ($hashedKey !== $request->signature_key) {
            Log::warning('Midtrans invalid signature', ['order_id' => $request->order_id]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $orderId = $request->order_id;
        $status  = $request->transaction_status;

        Log::info('Processing order', ['order_id' => $orderId, 'status' => $status]);

        if (str_starts_with($orderId, 'PRP-')) {
            $this->handlePerpanjangan($orderId, $status, $request->all());
        } elseif (str_starts_with($orderId, 'LNS-')) {
            $this->handlePelunasan($orderId, $status, $request->all());
        }

        return response()->json(['message' => 'OK']);
    }

    private function handlePerpanjangan(string $orderId, string $status, array $data): void
    {
        $perpanjangan = Perpanjangan::where('midtrans_order_id', $orderId)->first();

        if (!$perpanjangan) {
            Log::warning('Perpanjangan not found', ['order_id' => $orderId]);
            return;
        }

        if (in_array($status, ['capture', 'settlement'])) {
            DB::transaction(function () use ($perpanjangan, $data) {
                $perpanjangan->update([
                    'status_bayar'      => 'berhasil',
                    'midtrans_response' => $data,
                ]);

                $gadai = $perpanjangan->gadai;
                $gadai->update([
                    'tgl_jatuh_tempo' => $perpanjangan->tgl_jt_baru,
                    'status'          => 'perpanjangan',
                ]);

                $sbgExists = Sbg::where('gadai_id', $perpanjangan->gadai_id)
                    ->where('tipe', 'perpanjangan')
                    ->where('referensi_id', $perpanjangan->id)
                    ->exists();

                if (!$sbgExists) {
                    Sbg::create([
                        'no_sbg'        => $perpanjangan->no_sbg,
                        'nasabah_id'    => $perpanjangan->nasabah_id,
                        'gadai_id'      => $perpanjangan->gadai_id,
                        'tipe'          => 'perpanjangan',
                        'referensi_id'  => $perpanjangan->id,
                        'tgl_transaksi' => Carbon::today(),
                        'qr_token'      => Str::uuid()->toString(),
                    ]);
                }

                Log::info('Perpanjangan berhasil diupdate', ['id' => $perpanjangan->id]);
            });
        } elseif (in_array($status, ['deny', 'expire', 'cancel'])) {
            $perpanjangan->update([
                'status_bayar'      => 'gagal',
                'midtrans_response' => $data,
            ]);
            Log::info('Perpanjangan gagal', ['id' => $perpanjangan->id]);
        }
    }

    private function handlePelunasan(string $orderId, string $status, array $data): void
    {
        $pelunasan = Pelunasan::where('midtrans_order_id', $orderId)->first();

        if (!$pelunasan) {
            Log::warning('Pelunasan not found', ['order_id' => $orderId]);
            return;
        }

        if (in_array($status, ['capture', 'settlement'])) {
            DB::transaction(function () use ($pelunasan, $data) {
                $pelunasan->update([
                    'status_bayar'      => 'berhasil',
                    'midtrans_response' => $data,
                ]);

                $gadai = $pelunasan->gadai;
                $gadai->update(['status' => 'lunas']);

                if ($gadai->loker_id) {
                    Locker::where('id', $gadai->loker_id)->update([
                        'status'   => 'kosong',
                        'gadai_id' => null,
                    ]);
                }

                $sbgExists = Sbg::where('gadai_id', $pelunasan->gadai_id)
                    ->where('tipe', 'pelunasan')
                    ->where('referensi_id', $pelunasan->id)
                    ->exists();

                if (!$sbgExists) {
                    Sbg::create([
                        'no_sbg'        => $pelunasan->no_sbg,
                        'nasabah_id'    => $pelunasan->nasabah_id,
                        'gadai_id'      => $pelunasan->gadai_id,
                        'tipe'          => 'pelunasan',
                        'referensi_id'  => $pelunasan->id,
                        'tgl_transaksi' => Carbon::today(),
                        'qr_token'      => Str::uuid()->toString(),
                    ]);
                }

                Log::info('Pelunasan berhasil diupdate', ['id' => $pelunasan->id]);
            });
        } elseif (in_array($status, ['deny', 'expire', 'cancel'])) {
            $pelunasan->update([
                'status_bayar'      => 'gagal',
                'midtrans_response' => $data,
            ]);
            Log::info('Pelunasan gagal', ['id' => $pelunasan->id]);
        }
    }
}