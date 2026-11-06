@extends('polani.layout')

@section('title', $blog->title . ' — Polani Fragrance')
@section('meta_description', Str::limit(strip_tags(htmlspecialchars_decode($blog->content)), 150))

@push('styles')
<link rel="stylesheet" href="{{ asset('one-stop-mart/css/information-pages.css') }}" />
@endpush

@section('content')
<div class="osm-information-page osm-information-page--blog-detail">
  {{-- Breadcrumbs --}}
  <nav class="osm-info-breadcrumb" aria-label="Breadcrumb">
    <a href="{{ route('home') }}">Home</a>
    <span>&rsaquo;</span>
    <a href="{{ route('home') }}#blogs-section">Blog Journal</a>
    <span>&rsaquo;</span>
    <span aria-current="page">{{ $blog->title }}</span>
  </nav>

  {{-- Container --}}
  <div class="osm-info-container">
    <div class="osm-info-content-card">
      {{-- Blog Header --}}
      <header class="osm-blog-article-header">
        <span class="osm-info-hero-eyebrow">Polani Journal</span>
        <h1>{{ $blog->title }}</h1>
        <div class="osm-blog-article-meta">
          <span>By <strong>Polani Editor</strong></span>
          <span aria-hidden="true">&bull;</span>
          <time datetime="{{ $blog->created_at->toW3cString() }}">{{ $blog->created_at->format('F d, Y') }}</time>
        </div>
      </header>

      {{-- Cover Image --}}
      @if($blog->image_path)
        <div class="osm-blog-cover">
          <img src="{{ asset($blog->image_path) }}" alt="{{ $blog->title }}" />
        </div>
      @endif

      {{-- Rich Text Content --}}
      <article class="osm-blog-content">
        {!! htmlspecialchars_decode($blog->content) !!}
      </article>

      {{-- Blog Footer --}}
      <footer class="osm-blog-footer">
        <div class="osm-blog-footer-brand">
          <img src="{{ asset('polani/assets/logos/logo-white-trans.png?v=4') }}" alt="Polani Fragrance Logo" style="filter: brightness(0) saturate(100%) invert(8%) sepia(9%) saturate(4155%) hue-rotate(186deg) brightness(97%) contrast(96%);" />
          <div>
            <div class="osm-blog-footer-name">Polani Fragrance</div>
            <div class="osm-blog-footer-sub">Pakistan's Signature Extrait de Parfum</div>
          </div>
        </div>
        <div>
          <a href="{{ route('home') }}" class="osm-blog-cta-btn">
            Shop Collection
          </a>
        </div>
      </footer>
    </div>
  </div>
</div>
@endsection
