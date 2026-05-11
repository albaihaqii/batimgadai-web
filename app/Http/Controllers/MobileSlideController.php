<?php

namespace App\Http\Controllers;

use App\Models\MobileSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MobileSlideController extends Controller
{
    private const MAX_ACTIVE_SLIDES = 4;

    public function index()
    {
        $slides = MobileSlide::orderBy('sort_order')->orderBy('id')->get();
        $activeCount = $slides->where('is_active', true)->count();

        return view('superadmin.settings.mobile.index', compact('slides', 'activeCount'));
    }

    public function create()
    {
        $activeCount = MobileSlide::where('is_active', true)->count();
        $activeSlides = MobileSlide::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();
        $maxActiveSlides = self::MAX_ACTIVE_SLIDES;

        $slide = new MobileSlide([
            'sort_order' => (MobileSlide::max('sort_order') ?? 0) + 1,
            'is_active' => $activeCount < self::MAX_ACTIVE_SLIDES,
        ]);

        return view('superadmin.settings.mobile.create', compact('slide', 'activeCount', 'activeSlides', 'maxActiveSlides'));
    }

    public function store(Request $request)
    {
        $validated = $this->validatedData($request);
        $validated['is_active'] = $request->boolean('is_active');
        $replaceActiveId = $request->integer('replace_active_id') ?: null;
        unset($validated['replace_active_id']);

        $this->ensureActiveLimit($validated['is_active'], $replaceActiveId);

        $file = $request->file('image');
        $filename = 'mockup-' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('frontend/images'), $filename);
        $validated['image_path'] = 'frontend/images/' . $filename;

        MobileSlide::create($validated);

        return redirect()
            ->route('superadmin.settings.mobile')
            ->with('success', 'Slide mobile app berhasil ditambahkan.');
    }

    public function edit(MobileSlide $mobileSlide)
    {
        $slide = $mobileSlide;
        $activeCount = MobileSlide::where('is_active', true)->count();
        $activeSlides = MobileSlide::where('is_active', true)
            ->where('id', '!=', $mobileSlide->id)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
        $maxActiveSlides = self::MAX_ACTIVE_SLIDES;

        return view('superadmin.settings.mobile.edit', compact('slide', 'activeCount', 'activeSlides', 'maxActiveSlides'));
    }

    public function update(Request $request, MobileSlide $mobileSlide)
    {
        $validated = $this->validatedData($request, false);
        $validated['is_active'] = $request->boolean('is_active');
        $replaceActiveId = $request->integer('replace_active_id') ?: null;
        unset($validated['replace_active_id']);

        $this->ensureActiveLimit($validated['is_active'], $replaceActiveId, $mobileSlide);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'mockup-' . $mobileSlide->id . '-' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('frontend/images'), $filename);
            $validated['image_path'] = 'frontend/images/' . $filename;
        }

        $mobileSlide->update($validated);

        return redirect()
            ->route('superadmin.settings.mobile')
            ->with('success', 'Konten mobile app berhasil diperbarui.');
    }

    public function destroy(MobileSlide $mobileSlide)
    {
        $mobileSlide->delete();

        return redirect()
            ->route('superadmin.settings.mobile')
            ->with('success', 'Slide mobile app berhasil dihapus.');
    }

    private function validatedData(Request $request, bool $imageRequired = true): array
    {
        return $request->validate([
            'title' => 'required|string|max:120',
            'description' => 'nullable|string|max:500',
            'sort_order' => 'required|integer|min:1|max:99',
            'is_active' => 'nullable|boolean',
            'replace_active_id' => 'nullable|integer',
            'image' => ($imageRequired ? 'required' : 'nullable') . '|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);
    }

    private function ensureActiveLimit(bool $willBeActive, ?int $replaceActiveId = null, ?MobileSlide $currentSlide = null): void
    {
        if (!$willBeActive) {
            return;
        }

        $activeQuery = MobileSlide::where('is_active', true);

        if ($currentSlide) {
            $activeQuery->where('id', '!=', $currentSlide->id);
        }

        if ($activeQuery->count() < self::MAX_ACTIVE_SLIDES) {
            return;
        }

        if ($replaceActiveId) {
            $replaceQuery = MobileSlide::where('id', $replaceActiveId)->where('is_active', true);

            if ($currentSlide) {
                $replaceQuery->where('id', '!=', $currentSlide->id);
            }

            if ($replaceQuery->exists()) {
                $replaceQuery->update(['is_active' => false]);
                return;
            }
        }

        if ($activeQuery->count() >= self::MAX_ACTIVE_SLIDES) {
            back()
                ->withErrors(['replace_active_id' => 'Pilih slide aktif yang akan dinonaktifkan terlebih dahulu.'])
                ->withInput()
                ->throwResponse();
        }
    }
}
