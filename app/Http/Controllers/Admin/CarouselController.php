<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CarouselSlide;
use App\Services\CarouselImageProcessor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CarouselController extends Controller
{
    protected CarouselImageProcessor $imageProcessor;

    public function __construct(CarouselImageProcessor $imageProcessor)
    {
        $this->imageProcessor = $imageProcessor;
    }

    /**
     * Display a listing of banners.
     */
    public function index()
    {
        $pageKey = request('page_key');
        $slides = CarouselSlide::query()
            ->when($pageKey && in_array($pageKey, CarouselSlide::PAGE_KEYS, true), fn ($query) => $query->where('page_key', $pageKey))
            ->orderBy('page_key')
            ->orderBy('order')
            ->get();

        return view('admin.carousel.index', compact('slides', 'pageKey'));
    }

    /**
     * Show the form for creating a new banner.
     */
    public function create()
    {
        return view('admin.carousel.create');
    }

    /**
     * Store a newly created banner in storage.
     */
    public function store(Request $request)
    {
        // Check if upload size exceeded post_max_size
        if ($request->isMethod('POST')) {
            $contentLength = (int)$request->server('CONTENT_LENGTH');
            $postMaxSize = ini_get('post_max_size');
            $limitBytes = $this->parseMemoryLimit($postMaxSize);
            
            if ($contentLength > $limitBytes && empty($request->all()) && empty($request->files->all())) {
                return back()
                    ->withInput()
                    ->withErrors(['image' => 'The upload exceeded the server request-size limit. Please upload an image no larger than 100MB.']);
            }
        }

        // Validate input
        $validated = $request->validate([
            'page_key' => 'required|in:' . implode(',', CarouselSlide::PAGE_KEYS),
            'title' => 'nullable|string|max:100',
            'subtitle' => 'nullable|string|max:500',
            'cta_text' => 'nullable|string|max:50|required_with:cta_url',
            'cta_url' => 'nullable|string|max:255|required_with:cta_text',
            'image' => [
                'nullable',
                'file',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:102400',
            ],
            'is_active' => 'nullable|boolean',
        ], [
            'image.max' => 'The banner image must not be larger than 100MB.',
            'cta_text.required_with' => 'Button Text is required when Button URL is provided.',
            'cta_url.required_with' => 'Button URL is required when Button Text is provided.',
        ]);

        try {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('banners', 'public');
            }

            // Determine next order value
            $nextOrder = (int) CarouselSlide::where('page_key', $validated['page_key'])->max('order') + 1;

            // Create banner
            $slide = CarouselSlide::create([
                'page_key' => $validated['page_key'],
                'title' => $validated['title'] ?? null,
                'subtitle' => $validated['subtitle'] ?? null,
                'cta_text' => $validated['cta_text'] ?? null,
                'cta_url' => $validated['cta_url'] ?? null,
                'image_name' => $imagePath,
                'order' => $nextOrder,
                'is_active' => $validated['is_active'] ?? true,
                'created_by' => Auth::id(),
            ]);

            return redirect()->route('admin.banners.index')
                ->with('success', "Banner '{$slide->title}' created successfully!");
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['image' => $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified banner.
     */
    public function edit(CarouselSlide $slide)
    {
        return view('admin.carousel.edit', compact('slide'));
    }

    /**
     * Update the specified banner in storage.
     */
    public function update(Request $request, CarouselSlide $slide)
    {
        // Check if upload size exceeded post_max_size
        if ($request->isMethod('POST') || $request->isMethod('PUT') || $request->isMethod('PATCH')) {
            $contentLength = (int)$request->server('CONTENT_LENGTH');
            $postMaxSize = ini_get('post_max_size');
            $limitBytes = $this->parseMemoryLimit($postMaxSize);
            
            if ($contentLength > $limitBytes && empty($request->all()) && empty($request->files->all())) {
                return back()
                    ->withInput()
                    ->withErrors(['image' => 'The upload exceeded the server request-size limit. Please upload an image no larger than 100MB.']);
            }
        }

        // Validate input
        $validated = $request->validate([
            'page_key' => 'required|in:' . implode(',', CarouselSlide::PAGE_KEYS),
            'title' => 'nullable|string|max:100',
            'subtitle' => 'nullable|string|max:500',
            'cta_text' => 'nullable|string|max:50|required_with:cta_url',
            'cta_url' => 'nullable|string|max:255|required_with:cta_text',
            'image' => [
                'nullable',
                'file',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:102400',
            ],
            'is_active' => 'nullable|boolean',
        ], [
            'image.max' => 'The banner image must not be larger than 100MB.',
            'cta_text.required_with' => 'Button Text is required when Button URL is provided.',
            'cta_url.required_with' => 'Button URL is required when Button Text is provided.',
        ]);

        try {
            $imageName = $slide->image_name;
            $oldImageName = null;

            if ($request->hasFile('image')) {
                $imageName = $request->file('image')->store('banners', 'public');
                $oldImageName = $slide->image_name;
            }

            // Update banner
            $slide->update([
                'page_key' => $validated['page_key'],
                'title' => $validated['title'] ?? null,
                'subtitle' => $validated['subtitle'] ?? null,
                'cta_text' => $validated['cta_text'] ?? null,
                'cta_url' => $validated['cta_url'] ?? null,
                'image_name' => $imageName,
                'is_active' => $validated['is_active'] ?? true,
                'updated_by' => Auth::id(),
            ]);

            // Only delete old image after successful database update
            if ($oldImageName) {
                $this->imageProcessor->deleteImages($oldImageName);
            }

            return redirect()->route('admin.banners.index')
                ->with('success', "Banner '{$slide->title}' updated successfully!");
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['image' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified banner from storage.
     */
    public function destroy(CarouselSlide $slide)
    {
        // Check if this is the last active banner
        $activeCount = CarouselSlide::where('is_active', true)->count();
        if ($slide->is_active && $activeCount <= 1) {
            return back()->withErrors(['error' => 'Cannot delete the last active banner. There must always be at least one active banner.']);
        }

        try {
            // Delete image files
            $this->imageProcessor->deleteImages($slide->image_name);

            // Delete database record
            $title = $slide->title;
            $slide->delete();

            return redirect()->route('admin.banners.index')
                ->with('success', "Banner '{$title}' deleted successfully!");
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete banner: ' . $e->getMessage()]);
        }
    }

    /**
     * Reorder banners via AJAX.
     */
    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'slides' => 'required|array',
            'slides.*.id' => 'required|integer|exists:carousel_slides,id',
            'slides.*.order' => 'required|integer|min:0',
        ]);

        try {
            foreach ($validated['slides'] as $slideData) {
                CarouselSlide::where('id', $slideData['id'])
                    ->update(['order' => $slideData['order']]);
            }

            return response()->json(['success' => true, 'message' => 'Carousel reordered successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Helper to parse memory limit shorthand value to bytes.
     */
    protected function parseMemoryLimit(string $val): int
    {
        $val = trim($val);
        if (empty($val)) {
            return 128 * 1024 * 1024;
        }
        $last = strtolower($val[strlen($val) - 1]);
        $val = (int)$val;
        switch ($last) {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }
        return $val;
    }
}
