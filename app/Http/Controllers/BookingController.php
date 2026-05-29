<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user  = Auth::user();
        $role  = $user->role;
        $query = Booking::with(['nasabah', 'branch', 'diprosesOleh']);

        if ($role !== 'superadmin') {
            $query->where('cabang_id', $user->cabang_id);
        }

        if ($role === 'superadmin' && $request->filled('cabang_id')) {
            $query->where('cabang_id', $request->cabang_id);
        }

        $status = $request->get('status', 'menunggu');
        $query->where('status', $status);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('nasabah', fn($q) =>
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('no_cif', 'like', "%{$search}%")
            );
        }

        $perPage  = $request->get('per_page', 10);
        $bookings = $query->latest()->paginate($perPage)->withQueryString();

        if ($request->has('export')) {
          $bookings = $query->latest()->get();
          $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.booking', compact('bookings'))
              ->setPaper('a4', 'landscape');
          return $pdf->download('data-booking-kunjungan-' . now()->format('Ymd') . '.pdf');
      }

        $branches = Branch::where('status', 'aktif')->get();

        $totalMenunggu     = Booking::when($role !== 'superadmin', fn($q) => $q->where('cabang_id', $user->cabang_id))->where('status', 'menunggu')->count();
        $totalDikonfirmasi = Booking::when($role !== 'superadmin', fn($q) => $q->where('cabang_id', $user->cabang_id))->where('status', 'dikonfirmasi')->count();

        return view("{$role}.booking.index", compact(
            'bookings', 'branches', 'status',
            'totalMenunggu', 'totalDikonfirmasi'
        ));
    }

    public function show(int $id)
    {
        /** @var \App\Models\User $user */
        $user    = Auth::user();
        $booking = Booking::with(['nasabah', 'branch', 'diprosesOleh'])->findOrFail($id);

        if ($user->role !== 'superadmin' && (int)$booking->cabang_id !== (int)$user->cabang_id) {
            abort(403);
        }

        return view("{$user->role}.booking.show", compact('booking'));
    }

    public function proses(Request $request, int $id)
    {
        $request->validate([
            'aksi'          => 'required|in:konfirmasi,tolak',
            'catatan_admin' => 'nullable|string|max:500',
        ]);

        /** @var \App\Models\User $user */
        $user    = Auth::user();
        $booking = Booking::with('nasabah')->findOrFail($id);

        if ($user->role !== 'superadmin' && (int)$booking->cabang_id !== (int)$user->cabang_id) {
            abort(403);
        }

        $statusBaru = $request->aksi === 'konfirmasi' ? 'dikonfirmasi' : 'ditolak';

        $booking->update([
            'status'        => $statusBaru,
            'catatan_admin' => $request->catatan_admin,
            'diproses_oleh' => $user->id,
            'tgl_diproses'  => now(),
        ]);

        // Notifikasi ke nasabah
        $judulNotif = $request->aksi === 'konfirmasi'
            ? 'Booking Kunjungan Dikonfirmasi'
            : 'Booking Kunjungan Ditolak';

        $pesanNotif = $request->aksi === 'konfirmasi'
            ? 'Booking kunjungan Anda No. ' . $booking->no_booking . ' pada ' . $booking->tgl_kunjungan->format('d M Y') . ' pukul ' . $booking->jam_kunjungan . ' telah dikonfirmasi.'
            : 'Booking kunjungan Anda No. ' . $booking->no_booking . ' ditolak.' . ($request->catatan_admin ? ' Catatan: ' . $request->catatan_admin : '');

        Notification::create([
            'tipe_penerima' => 'nasabah',
            'penerima_id'   => $booking->nasabah_id,
            'tipe_notif'    => 'booking_kunjungan',
            'judul'         => $judulNotif,
            'pesan'         => $pesanNotif,
            'is_read'       => false,
        ]);

        $pesan = $request->aksi === 'konfirmasi'
            ? 'Booking berhasil dikonfirmasi.'
            : 'Booking berhasil ditolak.';

        return redirect()->route("{$user->role}.booking.kunjungan")
            ->with('success', $pesan);
    }

    public function selesai(int $id)
    {
        /** @var \App\Models\User $user */
        $user    = Auth::user();
        $booking = Booking::findOrFail($id);

        $booking->update([
            'status'        => 'selesai',
            'diproses_oleh' => $user->id,
            'tgl_diproses'  => now(),
        ]);

        return back()->with('success', 'Booking ditandai selesai.');
    }

    // API untuk Flutter

    public function apiStore(Request $request)
    {
        $request->validate([
            'no_cif'         => 'required|exists:nasabah,no_cif',
            'cabang_id'      => 'required|exists:cabang,id',
            'tgl_kunjungan'  => 'required|date|after_or_equal:today',
            'jam_kunjungan'  => 'required|date_format:H:i',
            'keperluan'      => 'nullable|string|max:255',
            'catatan_nasabah'=> 'nullable|string|max:500',
            'kategori_barang'=> 'nullable|string',
            'harga_pasar'    => 'nullable|numeric',
            'estimasi_min'   => 'nullable|numeric',
            'estimasi_max'   => 'nullable|numeric',
        ]);

        $nasabah = Customer::where('no_cif', $request->no_cif)->firstOrFail();

        $booking = Booking::create([
            'no_booking'      => Booking::generateNoBooking(),
            'nasabah_id'      => $nasabah->id,
            'cabang_id'       => $request->cabang_id,
            'tgl_kunjungan'   => $request->tgl_kunjungan,
            'jam_kunjungan'   => $request->jam_kunjungan,
            'keperluan'       => $request->keperluan ?? 'Gadai Baru',
            'catatan_nasabah' => $request->catatan_nasabah,
            'kategori_barang' => $request->kategori_barang,
            'harga_pasar'     => $request->harga_pasar,
            'estimasi_min'    => $request->estimasi_min,
            'estimasi_max'    => $request->estimasi_max,
            'status'          => 'menunggu',
        ]);

        // Notifikasi ke admin cabang
        $adminCabang = \App\Models\User::where('role', 'admin')
            ->where('cabang_id', $request->cabang_id)->first();

        if ($adminCabang) {
            Notification::create([
                'tipe_penerima' => 'user',
                'penerima_id'   => $adminCabang->id,
                'tipe_notif'    => 'booking_kunjungan',
                'judul'         => 'Booking Kunjungan Baru',
                'pesan'         => 'Nasabah ' . $nasabah->nama . ' mengajukan booking kunjungan pada ' . \Carbon\Carbon::parse($request->tgl_kunjungan)->format('d M Y') . ' pukul ' . $request->jam_kunjungan,
                'is_read'       => false,
            ]);
        }

        return response()->json([
            'message'    => 'Booking berhasil diajukan.',
            'no_booking' => $booking->no_booking,
            'status'     => $booking->status,
        ], 201);
    }

    public function apiList(Request $request)
    {
        $request->validate(['no_cif' => 'required|exists:nasabah,no_cif']);

        $nasabah  = Customer::where('no_cif', $request->no_cif)->firstOrFail();
        $bookings = Booking::with('branch')
            ->where('nasabah_id', $nasabah->id)
            ->latest()
            ->get()
            ->map(fn($b) => [
                'id'             => $b->id,
                'no_booking'     => $b->no_booking,
                'cabang'         => $b->branch->nama ?? '-',
                'tgl_kunjungan'  => $b->tgl_kunjungan->format('d M Y'),
                'jam_kunjungan'  => $b->jam_kunjungan,
                'keperluan'      => $b->keperluan,
                'status'         => $b->status,
                'catatan_admin'  => $b->catatan_admin,
                'estimasi_min'   => $b->estimasi_min,
                'estimasi_max'   => $b->estimasi_max,
                'kategori_barang'=> $b->kategori_barang,
            ]);

        return response()->json(['data' => $bookings]);
    }
}