@extends('polani.layout')

@section('title', $course->name . ' — One Stop Mart')

@push('styles')
    <link rel="stylesheet" href="{{ asset('one-stop-mart/css/customer-account.css') }}">
@endpush

@section('content')
<div class="osm-purchased-content-page">
    <div class="osm-account-container">
    <div class="container-fluid py-4">
        <div class="row">
            <!-- Left Sidebar - Course Navigation -->
            <div class="col-lg-3">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0">{{ $course->name }}</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <div class="accordion" id="courseContentAccordion">
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header" id="headingLectures">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLectures" aria-expanded="true" aria-controls="collapseLectures">
                                            <i class="fas fa-video me-2"></i> Lectures
                                        </button>
                                    </h2>
                                    <div id="collapseLectures" class="accordion-collapse collapse show" aria-labelledby="headingLectures">
                                        <div class="accordion-body p-0">
                                            <div class="list-group list-group-flush">
                                                @foreach($course->lectures as $lecItem)
                                                    <a href="{{ route('lecture.detail', ['course' => $course->id, 'lecture' => $lecItem->id]) }}"
                                                        class="list-group-item list-group-item-action d-flex align-items-center {{ $lecture && $lecture->id == $lecItem->id ? 'active' : '' }}">
                                                        <i class="fas fa-play-circle me-2"></i>
                                                        <div>
                                                            <span>{{ $lecItem->name }}</span>
                                                            <small class="d-block text-muted">{{ $lecItem->duration ?? 'N/A' }}</small>
                                                        </div>
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="col-lg-9">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">My Courses</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('course.view', $course->id) }}">{{ $course->name }}</a></li>
                        @if(isset($lecture))
                            <li class="breadcrumb-item active">{{ $lecture->name }}</li>
                        @endif
                    </ol>
                </nav>

                <!-- Content Tabs: Video/Material and Q&A -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white p-0">
                        <ul class="nav nav-tabs" id="contentTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="content-tab" data-bs-toggle="tab" data-bs-target="#content" type="button" role="tab" aria-controls="content" aria-selected="true">
                                    <i class="fas fa-video me-2"></i> Lecture
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="contentTabContent">
                            <!-- Content Tab -->
                            <div class="tab-pane fade show active" id="content" role="tabpanel" aria-labelledby="content-tab">
                                @if(isset($lecture))
                                    <div class="lecture-content">
                                        <h3 class="mb-3">{{ $lecture->name }}</h3>

                                        <!-- Video Player -->
                                        <div class="ratio ratio-16x9 mb-4">
                                            @if ($lecture && $lecture->youtube_url)
                                                <div id="youtube-player" class="w-100 h-100">
                                                    <div class="video-container w-100 h-100">
                                                        <!-- Plyr video player with data attributes - no iframe -->
                                                        @php
                                                            // Extract YouTube ID
                                                            $youtubeId = null;
                                                            $patterns = [
                                                                '/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/',
                                                                '/youtu\.be\/([a-zA-Z0-9_-]+)/'
                                                            ];

                                                            foreach ($patterns as $pattern) {
                                                                if (preg_match($pattern, $lecture->youtube_url, $matches)) {
                                                                    $youtubeId = $matches[1];
                                                                    break;
                                                                }
                                                            }

                                                            // Base64 encode for security
                                                            $encodedId = base64_encode($youtubeId);
                                                        @endphp
                                                        <div class="video-player w-100 h-100"
                                                            data-plyr-provider="youtube"
                                                            data-plyr-embed-id="encrypted:{{ $encodedId }}"></div>
                                                        <div class="video-overlay"></div>
                                                    </div>
                                                </div>
                                            @elseif($lecture->video_path)
                                                <video id="custom-video-player" class="w-100 h-100 plyr-video" controls>
                                                    <source src="{{ Storage::url($lecture->video_path) }}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            @else
                                                <div class="d-flex align-items-center justify-content-center bg-light h-100">
                                                    <p class="text-muted">No video available for this lecture</p>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Lecture Description -->
                                        <div class="lecture-description mt-4">
                                            <h4>Description</h4>
                                            <div class="p-3 bg-light rounded">
                                                {!! $lecture->description !!}
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                                        <h4>Select a lecture from the sidebar</h4>
                                        <p class="text-muted">Choose what you want to view</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="d-flex justify-content-between mb-4">
                    @if(isset($previousItem) && $previousItem)
                        <a href="{{ route('lecture.detail', ['course' => $course->id, 'lecture' => $previousItem->id]) }}" class="btn btn-outline-primary">
                            <i class="fas fa-chevron-left me-2"></i> Previous Lecture
                        </a>
                    @else
                        <div></div>
                    @endif

                    @if(isset($nextItem) && $nextItem)
                        <a href="{{ route('lecture.detail', ['course' => $course->id, 'lecture' => $nextItem->id]) }}" class="btn btn-primary">
                            Next Lecture <i class="fas fa-chevron-right ms-2"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.plyr.io/3.6.8/plyr.css" />
<script src="https://cdn.plyr.io/3.6.8/plyr.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Plyr.js for YouTube videos with secure data attributes
        const youtubePlayers = Array.from(document.querySelectorAll('.video-player')).map(p => {
            // Check if embed ID is encrypted
            let embedId = p.getAttribute('data-plyr-embed-id');
            if (embedId && embedId.startsWith('encrypted:')) {
                // Extract and decode the Base64 encrypted ID
                const encryptedId = embedId.replace('encrypted:', '');
                try {
                    // Decode Base64
                    embedId = atob(encryptedId);
                    console.log('Decoded YouTube ID for playback');
                } catch (e) {
                    console.error('Error decoding YouTube ID:', e);
                    embedId = ''; // Use blank if decode fails
                }

                // Update the attribute with decoded ID
                p.setAttribute('data-plyr-embed-id', embedId);
            }

            // Initialize player
            return new Plyr(p, {
                controls: ['play', 'progress', 'current-time', 'mute', 'volume'],
                youtube: {
                    rel: 0,
                    showinfo: 0,
                    modestbranding: 1,
                    iv_load_policy: 3,
                    origin: window.location.origin
                },
                responsive: true
            });
        });

        // Initialize regular video players with Plyr
        const videoPlayers = Array.from(document.querySelectorAll('.plyr-video')).map(p => new Plyr(p, {
            controls: ['play', 'progress', 'current-time', 'mute', 'volume'],
            responsive: true
        }));

        // Apply basic protection to videos
        function applyVideoProtection() {
            // Find the video container
            const videoContainer = document.querySelector('.video-container');
            if (!videoContainer) return;

            // Add protection classes
            videoContainer.classList.add('video-protected');

            // Create overlay div for click protection
            const overlay = document.createElement('div');
            overlay.className = 'video-overlay';
            overlay.style.cssText = `
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 90%;
                background-color: transparent;
                z-index: 10;
                cursor: not-allowed;
            `;
            videoContainer.appendChild(overlay);

            // Disable right click on video and overlay
            videoContainer.addEventListener('contextmenu', function(e) {
                e.preventDefault();
                return false;
            });

            // Disable dragging of video elements
            const mediaElement = videoContainer.querySelector('iframe') || videoContainer.querySelector('video');
            if (mediaElement) {
                mediaElement.addEventListener('dragstart', function(e) {
                    e.preventDefault();
                    return false;
                });
            }
        }

        // Apply protection to videos
        applyVideoProtection();
    });
</script>
<style>
    /* Video protection styles */
    .video-container {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
        width: 100% !important;
        height: auto !important;
        aspect-ratio: 16/9;
    }

    .plyr {
        width: 100% !important;
        height: 100% !important;
    }

    .video-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 90%;
        background-color: transparent;
        z-index: 10;
        cursor: not-allowed;
    }
</style>
@endpush
