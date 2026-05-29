<?php

namespace App\Http\Controllers;

use App\Models\Gadai;
use App\Models\Customer;
use App\Models\Perpanjangan;
use App\Models\Pelunasan;
use App\Models\Branch;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user     = Auth::user();
        $role     = $user->role;
        $cabangId = $role !== 'superadmin' ? $user->cabang_id : null;

        $now       = Carbon::now();
        $bulanIni  = $now->month;
        $tahunIni  = $now->year;
        $bulanLalu = $now->copy()->subMonth()->month;
        $tahunLalu = $now->copy()->subMonth()->year;

        $gadaiQuery = Gadai::query()->when($cabangId, fn($q) => $q->where('cabang_id', $cabangId));

        $totalNasabah     = Customer::when($cabangId, fn($q) => $q->where('cabang_id', $cabangId))->count();
        $totalNasabahLalu = Customer::when($cabangId, fn($q) => $q->where('cabang_id', $cabangId))
            ->whereYear('created_at', $tahunLalu)
            ->whereMonth('created_at', '<=', $bulanLalu)
            ->count();

        $transaksiAktif     = (clone $gadaiQuery)->whereIn('status', ['aktif', 'perpanjangan', 'jatuh_tempo'])->count();
        $transaksiAktifLalu = (clone $gadaiQuery)->whereIn('status', ['aktif', 'perpanjangan', 'jatuh_tempo'])
            ->whereYear('updated_at', $tahunLalu)
            ->whereMonth('updated_at', $bulanLalu)
            ->count();

        $menungguApproval = (clone $gadaiQuery)->where('status', 'menunggu_approval')->count();

        $totalPinjamanBulanIni  = (clone $gadaiQuery)
            ->whereMonth('tgl_gadai', $bulanIni)
            ->whereYear('tgl_gadai', $tahunIni)
            ->whereNotNull('nilai_pinjaman')
            ->sum('nilai_pinjaman');

        $totalPinjamanBulanLalu = (clone $gadaiQuery)
            ->whereMonth('tgl_gadai', $bulanLalu)
            ->whereYear('tgl_gadai', $tahunLalu)
            ->whereNotNull('nilai_pinjaman')
            ->sum('nilai_pinjaman');

        $pctNasabah  = $this->pct((float) $totalNasabah, (float) $totalNasabahLalu);
        $pctAktif    = $this->pct((float) $transaksiAktif, (float) $transaksiAktifLalu);
        $pctPinjaman = $this->pct((float) $totalPinjamanBulanIni, (float) $totalPinjamanBulanLalu);

        $bulan = [];
        for ($i = 1; $i <= 12; $i++) {
            $bulan[] = [
                'gadai'        => (clone $gadaiQuery)
                    ->whereMonth('tgl_gadai', $i)
                    ->whereYear('tgl_gadai', $tahunIni)
                    ->count(),
                'perpanjangan' => Perpanjangan::when($cabangId, fn($q) =>
                    $q->whereHas('gadai', fn($g) => $g->where('cabang_id', $cabangId)))
                    ->whereMonth('tgl_perpanjangan', $i)
                    ->whereYear('tgl_perpanjangan', $tahunIni)
                    ->count(),
                'pelunasan'    => Pelunasan::when($cabangId, fn($q) =>
                    $q->whereHas('gadai', fn($g) => $g->where('cabang_id', $cabangId)))
                    ->whereMonth('tgl_pelunasan', $i)
                    ->whereYear('tgl_pelunasan', $tahunIni)
                    ->count(),
                'jatuh_tempo'  => (clone $gadaiQuery)
                    ->where('status', 'jatuh_tempo')
                    ->whereMonth('tgl_jatuh_tempo', $i)
                    ->whereYear('tgl_jatuh_tempo', $tahunIni)
                    ->count(),
            ];
        }

        $perCabang = [];
        if ($role === 'superadmin') {
            $perCabang = Branch::where('status', 'aktif')->get()->map(fn($b) => [
                'nama'  => $this->shortNama($b->nama),
                'total' => Gadai::where('cabang_id', $b->id)->count(),
            ])->toArray();
        }

        $pengajuanTerbaru = Gadai::with(['nasabah', 'barang', 'branch'])
            ->when($cabangId, fn($q) => $q->where('cabang_id', $cabangId))
            ->where('status', 'menunggu_approval')
            ->latest()
            ->limit(5)
            ->get();

        $view = match($role) {
            'admin'   => 'admin.dashboard',
            'officer' => 'officer.dashboard',
            default   => 'superadmin.dashboard',
        };

        return view($view, compact(
            'totalNasabah', 'transaksiAktif', 'menungguApproval',
            'totalPinjamanBulanIni', 'bulan', 'perCabang', 'pengajuanTerbaru',
            'pctNasabah', 'pctAktif', 'pctPinjaman'
        ));
    }

    private function pct(float $sekarang, float $lalu): array
    {
        if ($lalu == 0) {
            $nilai = $sekarang > 0 ? 100 : 0;
            $naik  = true;
        } else {
            $nilai = round((($sekarang - $lalu) / $lalu) * 100, 1);
            $naik  = $nilai >= 0;
        }
        return ['nilai' => abs($nilai), 'naik' => $naik];
    }

    private function shortNama(string $nama): string
    {
        $nama = str_ireplace(['batim gadai', 'batim', 'gadai'], '', $nama);
        return trim($nama) ?: $nama;
    }
}