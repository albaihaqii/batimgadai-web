<?php

namespace App\Http\Controllers;

use App\Models\HeroSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HeroSlideController extends Controller
{
    private const MAX_ACTIVE_SLIDES = 4;

    public function index()
    {
        $slides = HeroSlide::orderBy('sort_order')->orderBy('id')->get();
        $activeCount = $slides->where('is_active', true)->count();

        return view('superadmin.settings.hero.index', compact('slides', 'activeCount'));
    }

    public function create()
    {
        $activeCount = HeroSlide::where('is_active', true)->count();
        $activeSlides = HeroSlide::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();
        $maxActiveSlides = self::MAX_ACTIVE_SLIDES;

        $slide = new HeroSlide([
            'sort_order' => (HeroSlide::max('sort_order') ?? 0) + 1,
            'is_active' => $activeCount < self::MAX_ACTIVE_SLIDES,
        ]);

        return view('superadmin.settings.hero.create', compact('slide', 'activeCount', 'activeSlides', 'maxActiveSlides'));
    }

    public function store(Request $request)
    {
        $validated = $this->validatedData($request);
        $validated['is_active'] = $request->boolean('is_active');
        $replaceActiveId = $request->integer('replace_active_id') ?: null;
        unset($validated['replace_active_id']);

        $this->ensureActiveLimit($validated['is_active'], $replaceActiveId);

        $file = $request->file('image');
        $filename = 'hero-' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('frontend/images'), $filename);
        $validated['image_path'] = 'frontend/images/' . $filename;

        HeroSlide::create($validated);

        return redirect()
            ->route('superadmin.settings.hero')
            ->with('success', 'Slide hero berhasil ditambahkan.');
    }

    public function edit(HeroSlide $heroSlide)
    {
        $slide = $heroSlide;
        $activeCount = HeroSlide::where('is_active', true)->count();
        $activeSlides = HeroSlide::where('is_active', true)
            ->where('id', '!=', $heroSlide->id)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
        $maxActiveSlides = self::MAX_ACTIVE_SLIDES;

        return view('superadmin.settings.hero.edit', compact('slide', 'activeCount', 'activeSlides', 'maxActiveSlides'));
    }

    public function update(Request $request, HeroSlide $heroSlide)
    {
        $validated = $this->validatedData($request, false);
        $validated['is_active'] = $request->boolean('is_active');
        $replaceActiveId = $request->integer('replace_active_id') ?: null;
        unset($validated['replace_active_id']);

        $this->ensureActiveLimit($validated['is_active'], $replaceActiveId, $heroSlide);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'hero-' . $heroSlide->id . '-' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('frontend/images'), $filename);
            $validated['image_path'] = 'frontend/images/' . $filename;
        }

        $heroSlide->update($validated);

        return redirect()
            ->route('superadmin.settings.hero')
            ->with('success', 'Konten hero berhasil diperbarui.');
    }

    public function destroy(HeroSlide $heroSlide)
    {
        $heroSlide->delete();

        return redirect()
            ->route('superadmin.settings.hero')
            ->with('success', 'Slide hero berhasil dihapus.');
    }

    private function validatedData(Request $request, bool $imageRequired = true): array
    {
        return $request->validate([
            'title' => 'required|string|max:120',
            'highlighted_title' => 'nullable|string|max:120',
            'description' => 'required|string|max:1000',
            'primary_button_label' => 'nullable|string|max:50',
            'primary_button_url' => 'nullable|string|max:120',
            'secondary_button_label' => 'nullable|string|max:50',
            'secondary_button_url' => 'nullable|string|max:120',
            'sort_order' => 'required|integer|min:1|max:99',
            'is_active' => 'nullable|boolean',
            'replace_active_id' => 'nullable|integer',
            'image' => ($imageRequired ? 'required' : 'nullable') . '|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);
    }

    private function ensureActiveLimit(bool $willBeActive, ?int $replaceActiveId = null, ?HeroSlide $currentSlide = null): void
    {
        if (!$willBeActive) {
            return;
        }

        $activeQuery = HeroSlide::where('is_active', true);

        if ($currentSlide) {
            $activeQuery->where('id', '!=', $currentSlide->id);
        }

        if ($activeQuery->count() < self::MAX_ACTIVE_SLIDES) {
            return;
        }

        if ($replaceActiveId) {
            $replaceQuery = HeroSlide::where('id', $replaceActiveId)->where('is_active', true);

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
