@extends('polani.layout')

@section('title', 'Insights & Updates — RazaToys')
@section('meta_description', 'Stay tuned for shopping tips, product guides, and news from the RazaToys editorial team.')

@push('styles')
<link rel="stylesheet" href="{{ asset('one-stop-mart/css/information-pages.css') }}" />
@endpush

@section('content')
<div class="osm-information-page osm-information-page--blogs">
  {{-- Breadcrumbs --}}
  <nav class="osm-info-breadcrumb" aria-label="Breadcrumb">
    <a href="{{ route('home') }}">Home</a>
    <span>&rsaquo;</span>
    <span aria-current="page">Journal</span>
  </nav>

  {{-- Hero Section --}}
  <header class="osm-info-hero">
    <span class="osm-info-hero-eyebrow">Journal</span>
    <h1>Insights & Updates</h1>
    <p class="osm-info-hero-subtitle">
      Your source for product guides, shopping tips, and the latest store announcements.
    </p>
  </header>

  {{-- Container --}}
  <div class="osm-info-container">
    @if($blogs->isNotEmpty())
      <div class="osm-info-blog-grid">
        @foreach($blogs as $blog)
          @php
            $blogImg = $blog->image_path ? asset($blog->image_path) : asset('polani/assets/story-packaging.svg');
          @endphp
          <article class="osm-info-blog-card">
            <div class="osm-info-blog-image">
              <img src="{{ $blogImg }}" alt="{{ $blog->title }}" loading="lazy" />
              <div class="osm-info-blog-date">
                {{ $blog->created_at->format('M d, Y') }}
              </div>
            </div>
            
            <div class="osm-info-blog-body">
              <div>
                <h2 class="osm-info-blog-title">
                  <a href="{{ route('polani.blog.detail', $blog->slug) }}">
                    {{ $blog->title }}
                  </a>
                </h2>
                <p class="osm-info-blog-excerpt">
                  {{ Str::limit(strip_tags(htmlspecialchars_decode($blog->content)), 110) }}
                </p>
              </div>
              
              <a href="{{ route('polani.blog.detail', $blog->slug) }}" class="osm-info-blog-link">
                Read Article &rarr;
              </a>
            </div>
          </article>
        @endforeach
      </div>

      {{-- Pagination --}}
      <div class="osm-info-pagination" aria-label="Pagination">
        {{ $blogs->links() }}
      </div>
    @else
      <div class="osm-info-empty" style="text-align: center; padding: 60px 20px; background: var(--color-white); border: 1px solid var(--color-border); border-radius: 8px; max-width: 600px; margin: 0 auto; box-shadow: var(--shadow-sm);">
        <span class="osm-info-empty-icon" aria-hidden="true" style="font-size: 3rem; color: var(--color-primary-dark); display: block; margin-bottom: 20px;">✦</span>
        <h2 style="font-size: 1.8rem; font-weight: bold; margin-bottom: 10px; color: var(--color-secondary);">No Articles Available</h2>
        <p style="color: var(--color-text-light); line-height: 1.6; margin-bottom: 25px;">We are currently preparing informative product guides and shopping tips. They will be added here in the future!</p>
        <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
          <a href="{{ route('polani.collection') }}" class="osm-info-submit-btn" style="background: var(--color-primary); color: var(--color-secondary); font-weight: bold; text-decoration: none; padding: 12px 24px; border-radius: var(--border-radius); display: inline-block; border: none; font-size: 0.9rem;">
            Browse Our Shop
          </a>
          <a href="{{ route('polani.new-arrivals') }}" class="osm-info-submit-btn" style="background: transparent; color: var(--color-secondary); border: 2px solid var(--color-secondary); font-weight: bold; text-decoration: none; padding: 10px 22px; border-radius: var(--border-radius); display: inline-block; font-size: 0.9rem;">
            Explore New Arrivals
          </a>
        </div>
      </div>
    @endif
  </div>
</div>
@endsection
