<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    // Landing

    public function indexLanding()
    {
        $banners = Banner::where('tipe', 'landing')
            ->orderBy('urutan')->paginate(10);
        $total   = $banners->total();
        $aktif   = Banner::where('tipe', 'landing')->where('is_active', true)->count();
        return view('superadmin.banner.landing.index', compact('banners', 'total', 'aktif'));
    }

    public function createLanding()
    {
        return view('superadmin.banner.landing.create');
    }

    public function storeLanding(Request $request)
    {
        $request->validate([
            'judul'       => 'required|string|max:255',
            'subjudul'    => 'nullable|string|max:255',
            'deskripsi'   => 'nullable|string',
            'teks_tombol' => 'nullable|string|max:100',
            'url_tombol'  => 'nullable|string|max:255',
            'foto'        => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'urutan'      => 'required|integer|min:1',
        ]);

        $fotoPath = $request->file('foto')->store('banners', 'public');

        Banner::create([
            'tipe'        => 'landing',
            'judul'       => $request->judul,
            'subjudul'    => $request->subjudul,
            'deskripsi'   => $request->deskripsi,
            'teks_tombol' => $request->teks_tombol,
            'url_tombol'  => $request->url_tombol,
            'foto'        => $fotoPath,
            'urutan'      => $request->urutan,
            'is_active'   => $request->has('is_active'),
            'created_by'  => Auth::id(),
        ]);

        return redirect()->route('superadmin.banner.landing')
            ->with('success', 'Banner landing berhasil ditambahkan.');
    }

    public function editLanding(int $id)
    {
        $banner = Banner::where('tipe', 'landing')->findOrFail($id);
        return view('superadmin.banner.landing.edit', compact('banner'));
    }

    public function updateLanding(Request $request, int $id)
    {
        $banner = Banner::where('tipe', 'landing')->findOrFail($id);

        $request->validate([
            'judul'       => 'required|string|max:255',
            'subjudul'    => 'nullable|string|max:255',
            'deskripsi'   => 'nullable|string',
            'teks_tombol' => 'nullable|string|max:100',
            'url_tombol'  => 'nullable|string|max:255',
            'foto'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'urutan'      => 'required|integer|min:1',
        ]);

        $fotoPath = $banner->foto;
        if ($request->hasFile('foto')) {
            if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }
            $fotoPath = $request->file('foto')->store('banners', 'public');
        }

        $banner->update([
            'judul'       => $request->judul,
            'subjudul'    => $request->subjudul,
            'deskripsi'   => $request->deskripsi,
            'teks_tombol' => $request->teks_tombol,
            'url_tombol'  => $request->url_tombol,
            'foto'        => $fotoPath,
            'urutan'      => $request->urutan,
            'is_active'   => $request->has('is_active'),
        ]);

        return redirect()->route('superadmin.banner.landing')
            ->with('success', 'Banner landing berhasil diperbarui.');
    }

    // Mobile

    public function indexMobile()
    {
        /** @var \App\Models\User $user */
        $user     = Auth::user();
        $cabangId = $user->role === 'superadmin' ? null : (int) $user->cabang_id;

        $banners = Banner::where('tipe', 'mobile')
            ->when($cabangId, fn($q) => $q->where(function ($q) use ($cabangId) {
                $q->where('cabang_id', $cabangId)->orWhereNull('cabang_id');
            }))
            ->with('branch')
            ->orderBy('urutan')
            ->paginate(10);

        $total = $banners->total();
        $aktif = Banner::where('tipe', 'mobile')->where('is_active', true)->count();

        return view('superadmin.banner.mobile.index', compact('banners', 'total', 'aktif'));
    }

    public function createMobile()
    {
        $branches = Branch::where('status', 'aktif')->orderBy('nama')->get();
        return view('superadmin.banner.mobile.create', compact('branches'));
    }

    public function storeMobile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'judul'     => 'required|string|max:255',
            'foto'      => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'url_link'  => 'nullable|string|max:255',
            'cabang_id' => 'nullable|exists:cabang,id',
            'urutan'    => 'required|integer|min:1',
        ]);

        $cabangId = $user->role === 'admin'
            ? $user->cabang_id
            : $request->cabang_id;

        $fotoPath = $request->file('foto')->store('banners', 'public');

        $banner = Banner::create([
            'tipe'       => 'mobile',
            'judul'      => $request->judul,
            'foto'       => $fotoPath,
            'url_link'   => $request->url_link,
            'cabang_id'  => $cabangId,
            'urutan'     => $request->urutan,
            'is_active'  => $request->has('is_active'),
            'created_by' => Auth::id(),
        ]);

        if ($banner->is_active) {
            $this->kirimNotifBanner($banner);
        }

        return redirect()->route('superadmin.banner.mobile')
            ->with('success', 'Banner mobile berhasil ditambahkan.');
    }

    public function editMobile(int $id)
    {
        $banner   = Banner::where('tipe', 'mobile')->findOrFail($id);
        $branches = Branch::where('status', 'aktif')->orderBy('nama')->get();
        return view('superadmin.banner.mobile.edit', compact('banner', 'branches'));
    }

    public function updateMobile(Request $request, int $id)
    {
        /** @var \App\Models\User $user */
        $user   = Auth::user();
        $banner = Banner::where('tipe', 'mobile')->findOrFail($id);

        $request->validate([
            'judul'     => 'required|string|max:255',
            'foto'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'url_link'  => 'nullable|string|max:255',
            'cabang_id' => 'nullable|exists:cabang,id',
            'urutan'    => 'required|integer|min:1',
        ]);

        $wasActive = $banner->is_active;
        $fotoPath  = $banner->foto;

        if ($request->hasFile('foto')) {
            if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }
            $fotoPath = $request->file('foto')->store('banners', 'public');
        }

        $cabangId = $user->role === 'admin'
            ? $user->cabang_id
            : $request->cabang_id;

        $banner->update([
            'judul'     => $request->judul,
            'foto'      => $fotoPath,
            'url_link'  => $request->url_link,
            'cabang_id' => $cabangId,
            'urutan'    => $request->urutan,
            'is_active' => $request->has('is_active'),
        ]);

        if (!$wasActive && $banner->is_active) {
            $this->kirimNotifBanner($banner);
        }

        return redirect()->route('superadmin.banner.mobile')
            ->with('success', 'Banner mobile berhasil diperbarui.');
    }

    // Shared

    public function destroy(int $id)
    {
        $banner = Banner::findOrFail($id);
        if ($banner->foto && Storage::disk('public')->exists($banner->foto)) {
            Storage::disk('public')->delete($banner->foto);
        }
        $banner->delete();
        return back()->with('success', 'Banner berhasil dihapus.');
    }

    public function toggle(int $id)
    {
        $banner    = Banner::findOrFail($id);
        $wasActive = $banner->is_active;
        $banner->update(['is_active' => !$banner->is_active]);

        if (!$wasActive && $banner->is_active && $banner->tipe === 'mobile') {
            $this->kirimNotifBanner($banner);
        }

        return back()->with('success', 'Status banner berhasil diubah.');
    }

    public function apiBanners()
    {
        $banners = Banner::where('tipe', 'mobile')
            ->where('is_active', true)
            ->orderBy('urutan')
            ->limit(3)
            ->get()
            ->map(fn($b) => [
                'id'        => $b->id,
                'judul'     => $b->judul,
                'foto'      => asset('storage/' . $b->foto),
                'url_link'  => $b->url_link,
                'cabang_id' => $b->cabang_id,
            ]);

        return response()->json(['data' => $banners]);
    }

    private function kirimNotifBanner(Banner $banner): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $nasabahQuery = Customer::query();
        if ($user->role === 'admin' || $banner->cabang_id) {
            $cabangId = $banner->cabang_id ?? $user->cabang_id;
            $nasabahQuery->where('cabang_id', $cabangId);
        }

        $notifs = $nasabahQuery->pluck('id')->map(fn($id) => [
            'tipe_penerima' => 'nasabah',
            'penerima_id'   => $id,
            'tipe_notif'    => 'info',
            'judul'         => $banner->judul,
            'pesan'         => 'Ada informasi baru dari BATIM GADAI.',
            'is_read'       => false,
            'created_at'    => now(),
            'updated_at'    => now(),
        ])->toArray();

        if (!empty($notifs)) {
            Notification::insert($notifs);
        }
    }
}