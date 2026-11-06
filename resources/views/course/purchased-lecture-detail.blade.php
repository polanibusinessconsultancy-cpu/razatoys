@extends('polani.layout')

@section('title', $lecture->name . ' — One Stop Mart')

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
                        <h5 class="mb-0">{{ $course?->name ?? $lecture->name }}</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <div class="accordion" id="courseContentAccordion">
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header" id="headingLectures">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLectures" aria-expanded="true" aria-controls="collapseLectures">
                                            <i class="fas fa-graduation-cap me-2"></i> Course Content
                                        </button>
                                    </h2>
                                    <div id="collapseLectures" class="accordion-collapse collapse show" aria-labelledby="headingLectures">
                                        <div class="accordion-body p-0">
                                            <div class="list-group list-group-flush">
                                                @foreach($lectures as $lecItem)
                                                    <!-- Lecture Video -->
                                                    @php
                                                        $lectureUrl = $course ? route('user.lecture.purchased', ['course' => $course->id, 'lecture' => $lecItem->id]) : route('user.lecture.standalone', ['lecture' => $lecItem->id]);
                                                    @endphp
                                                    <a href="{{ $lectureUrl }}"
                                                        class="list-group-item list-group-item-action d-flex align-items-center ps-4 {{ $lecture->id == $lecItem->id && !$isViewingPdf ? 'active' : '' }}">
                                                        <i class="fas fa-play-circle me-2"></i>
                                                        <div class="flex-grow-1">
                                                            <span>{{ $lecItem->name }}</span>
                                                            <small class="d-block text-muted">{{ $lecItem->duration ?? 'N/A' }}</small>
                                                        </div>
                                                    </a>

                                                    <!-- Lecture PDF (indented) -->
                                                    @if($lecItem->pdf_file_path)
                                                    <a href="{{ $lectureUrl }}?view=pdf"
                                                        class="list-group-item list-group-item-action d-flex align-items-center ps-5 {{ $lecture->id == $lecItem->id && $isViewingPdf ? 'active' : '' }}">
                                                        <i class="fas fa-file-pdf text-danger me-2"></i>
                                                        <div>
                                                            <span>{{ $lecItem->name }} PDF</span>
                                                        </div>
                                                    </a>
                                                    @endif

                                                    <!-- Lecture Quizzes (indented) -->
                                                    @php
                                                        $quizzes = $lecItem->quizzes;
                                                        $userId = auth()->id();
                                                    @endphp
                                                    @if($quizzes && $quizzes->count() > 0)
                                                        @foreach($quizzes as $quiz)
                                                            <a href="{{ route('quiz.show', ['course' => $course ? $course->id : 0, 'lecture' => $lecItem->id, 'quiz' => $quiz->id]) }}"
                                                                class="list-group-item list-group-item-action d-flex align-items-center ps-5">
                                                                @if($quiz->isPassed($userId))
                                                                    <i class="fas fa-check-circle text-success me-2"></i>
                                                                @else
                                                                    <i class="fas fa-question-circle text-warning me-2"></i>
                                                                @endif
                                                                <div>
                                                                    <span>Quiz: {{ $quiz->title }}</span>
                                                                    @if($quiz->isPassed($userId))
                                                                        <small class="d-block text-success">Passed</small>
                                                                    @elseif($quiz->hasInProgressAttempt($userId))
                                                                        <small class="d-block text-primary">In Progress</small>
                                                                    @else
                                                                        <small class="d-block text-muted">{{ $quiz->total_points }} points</small>
                                                                    @endif
                                                                </div>
                                                            </a>
                                                        @endforeach
                                                    @endif
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
                        @if($course)
                            <li class="breadcrumb-item"><a href="{{ route('user.course.purchased', $course->id) }}">{{ $course->name }}</a></li>
                        @endif
                        <li class="breadcrumb-item active">{{ $lecture->name }}</li>
                    </ol>
                </nav>

                <!-- Lecture Player -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h3 class="mb-3">{{ $lecture->name }}</h3>

                        @php
                            $viewMode = request()->query('view', 'video');
                            $userProgress = $lecture->getProgressForUser(Auth::id());
                            $progressPercent = $userProgress ? $userProgress->progress_percent : 0;
                            $isCompleted = $userProgress ? $userProgress->completed : false;
                        @endphp

                        <!-- Lecture Progress Bar -->
                        <div class="lecture-progress mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small text-muted">Your progress</span>
                                <span class="small fw-bold lecture-progress-display">{{ number_format($progressPercent, 0) }}%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar lecture-progress-bar {{ $isCompleted ? 'bg-success' : 'bg-primary' }}"
                                     role="progressbar"
                                     style="width: {{ $progressPercent }}%;"
                                     aria-valuenow="{{ $progressPercent }}"
                                     aria-valuemin="0"
                                     aria-valuemax="100"></div>
                            </div>
                        </div>

                        @if($viewMode == 'pdf' && $lecture->pdf_file_path)
                            <!-- PDF Viewer -->
                            <div class="pdf-viewer mb-4" style="height: 600px;">
                                <!-- PDF Viewer with Fallback -->
                                <object data="{{ asset('storage/' . $lecture->pdf_file_path) }}" type="application/pdf" width="100%" height="100%">
                                    <div class="p-4 bg-light rounded text-center">
                                        <p class="mb-2">
                                            <i class="fas fa-exclamation-circle text-warning fa-2x mb-3"></i><br>
                                            Your browser doesn't support embedded PDF viewing.
                                        </p>
                                        <a href="{{ asset('storage/' . $lecture->pdf_file_path) }}" class="btn btn-primary mb-2" target="_blank">
                                            <i class="fas fa-external-link-alt me-1"></i> Open PDF in New Tab
                                        </a>
                                        <a href="{{ asset('storage/' . $lecture->pdf_file_path) }}" class="btn btn-outline-primary" download>
                                            <i class="fas fa-download me-1"></i> Download PDF
                                        </a>
                                    </div>
                                </object>
                            </div>

                            <!-- PDF Actions -->
                            <div class="d-flex justify-content-between mb-4">
                                @php
                                    $videoUrl = $course ? route('user.lecture.purchased', ['course' => $course->id, 'lecture' => $lecture->id]) : route('user.lecture.standalone', ['lecture' => $lecture->id]);
                                @endphp
                                <a href="{{ $videoUrl }}" class="btn btn-outline-primary">
                                    <i class="fas fa-video me-1"></i> Switch to Video
                                </a>
                                <div>
                                    <a href="{{ asset('storage/' . $lecture->pdf_file_path) }}" class="btn btn-outline-primary me-2" download>
                                        <i class="fas fa-download me-1"></i> Download PDF
                                    </a>
                                    <a href="{{ asset('storage/' . $lecture->pdf_file_path) }}" class="btn btn-primary" target="_blank">
                                        <i class="fas fa-external-link-alt me-1"></i> Open in New Tab
                                    </a>
                                </div>
                            </div>
                        @else
                            <!-- Video Player -->
                            <div class="ratio ratio-16x9 mb-4">
                                @if($lecture->youtube_url)
                                <div id="youtube-player" class="w-100 h-100">
                                    <div class="video-container w-100 h-100">
                                        <!-- Plyr video player with data attributes - no iframe -->
                                        @php
                                            // Handle YouTube ID - always encrypt even if not already encrypted
                                            $embedId = '';

                                            // First try to use the pre-encrypted ID from the controller
                                            if (isset($lecture->encrypted_youtube_id) && !empty($lecture->encrypted_youtube_id)) {
                                                $embedId = 'encrypted:' . $lecture->encrypted_youtube_id;
                                                // Debug output for server-side
                                                // error_log("Using pre-encrypted YouTube ID: " . $lecture->encrypted_youtube_id);
                                            }
                                            // If no pre-encrypted ID, extract and encrypt it now
                                            elseif ($lecture->youtube_url) {
                                                // Extract YouTube ID from URL
                                                $rawId = '';
                                                if (strpos($lecture->youtube_url, 'youtube.com/watch?v=') !== false) {
                                                    $parts = parse_url($lecture->youtube_url);
                                                    parse_str($parts['query'], $query);
                                                    $rawId = $query['v'];
                                                } elseif (strpos($lecture->youtube_url, 'youtu.be/') !== false) {
                                                    $rawId = substr(parse_url($lecture->youtube_url, PHP_URL_PATH), 1);
                                                }

                                                if ($rawId) {
                                                    $embedId = 'encrypted:' . base64_encode($rawId);
                                                    // Debug output for server-side
                                                    // error_log("Extracted and encrypted YouTube ID: " . $rawId);
                                                } else {
                                                    // If we can't extract the ID, use the whole URL as fallback
                                                    // This is just for debugging and shouldn't happen in production
                                                    $embedId = str_replace(['https://www.youtube.com/watch?v=', 'https://youtu.be/'], '', $lecture->youtube_url);
                                                    // error_log("Failed to extract YouTube ID, using fallback: " . $embedId);
                                                }
                                            }
                                        @endphp
                                        <div class="video-player w-100 h-100"
                                            data-plyr-provider="youtube"
                                            data-plyr-embed-id="{{ $embedId }}"
                                            @if(empty($embedId) && !empty($lecture->youtube_url))
                                            data-fallback-url="{{ $lecture->youtube_url }}"
                                            @endif
                                            ></div>
                                        <div class="video-overlay"></div>
                                    </div>
                                </div>
                                @elseif($lecture->video_path)
                                <div id="video-player-container" class="video-container w-100 h-100">
                                    <video id="custom-video-player" class="w-100 h-100 plyr-video" poster="{{ asset('img/video-poster.jpg') }}" controls>
                                        <source src="{{ Storage::url($lecture->video_path) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                                @else
                                <div class="d-flex align-items-center justify-content-center bg-light h-100">
                                    <p class="text-muted">No video available for this lecture</p>
                                </div>
                                @endif
                            </div>

                            <!-- Switch to PDF if available -->
                            @if($lecture->pdf_file_path)
                                <div class="d-flex justify-content-end mb-4">
                                    @php
                                        $pdfUrl = $course ? route('user.lecture.purchased', ['course' => $course->id, 'lecture' => $lecture->id]) : route('user.lecture.standalone', ['lecture' => $lecture->id]);
                                    @endphp
                                    <a href="{{ $pdfUrl }}?view=pdf" class="btn btn-outline-danger">
                                        <i class="fas fa-file-pdf me-1"></i> View PDF Notes
                                    </a>
                                </div>
                            @endif
                        @endif

                        <!-- Lecture Description -->
                        <div class="lecture-description mt-4">
                            <h4>Description</h4>
                            <div class="p-3 bg-light rounded">
                                {!! $lecture->description !!}
                            </div>
                        </div>

                        <!-- Lecture Navigation Buttons -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-body d-flex justify-content-between">
                                <div>
                                    @if($previousItem)
                                        @php
                                            $prevLecture = $previousItem['lecture'];
                                            $prevType = $previousItem['type'];
                                            $prevUrl = $course ? route('user.lecture.purchased', ['course' => $course->id, 'lecture' => $prevLecture->id]) : route('user.lecture.standalone', ['lecture' => $prevLecture->id]);
                                            if ($prevType === 'pdf') {
                                                $prevUrl .= '?view=pdf';
                                                $prevText = $prevLecture->name . ' PDF';
                                                $prevIcon = 'fa-file-pdf';
                                            } else {
                                                $prevText = $prevLecture->name;
                                                $prevIcon = 'fa-video';
                                            }
                                            
                                            $currentUrl = $course ? route('user.lecture.purchased', ['course' => $course->id, 'lecture' => $lecture->id]) : route('user.lecture.standalone', ['lecture' => $lecture->id]);
                                        @endphp
                                        <a href="{{ $prevUrl }}" class="btn btn-outline-secondary me-2">
                                            <i class="fas fa-chevron-left me-1"></i> <i class="fas {{ $prevIcon }} me-1"></i> Previous
                                        </a>
                                        
                                        @if($isViewingPdf)
                                            <a href="{{ $currentUrl }}"
                                               class="btn btn-outline-primary" data-bs-toggle="tooltip" title="View Video">
                                                <i class="fas fa-video me-1"></i> View Video
                                            </a>
                                        @elseif($lecture->pdf_file_path)
                                            <a href="{{ $currentUrl }}?view=pdf"
                                               class="btn btn-outline-danger" data-bs-toggle="tooltip" title="View PDF Notes">
                                                <i class="fas fa-file-pdf me-1"></i> View PDF Notes
                                            </a>
                                        @endif
                                    @elseif($isViewingPdf)
                                        @php
                                            $videoButtonUrl = $course ? route('user.lecture.purchased', ['course' => $course->id, 'lecture' => $lecture->id]) : route('user.lecture.standalone', ['lecture' => $lecture->id]);
                                        @endphp
                                        <a href="{{ $videoButtonUrl }}"
                                           class="btn btn-outline-primary me-2" data-bs-toggle="tooltip" title="View Video">
                                            <i class="fas fa-video me-1"></i> View Video
                                        </a>
                                    @endif
                                </div>

                                <div>
                                    @if($nextItem)
                                        @php
                                            $nextLecture = $nextItem['lecture'];
                                            $nextType = $nextItem['type'];
                                            $nextUrl = $course ? route('user.lecture.purchased', ['course' => $course->id, 'lecture' => $nextLecture->id]) : route('user.lecture.standalone', ['lecture' => $nextLecture->id]);
                                            if ($nextType === 'pdf') {
                                                $nextUrl .= '?view=pdf';
                                                $nextText = $nextLecture->name . ' PDF';
                                                $nextIcon = 'fa-file-pdf';
                                            } else {
                                                $nextText = $nextLecture->name;
                                                $nextIcon = 'fa-video';
                                            }
                                        @endphp
                                        <a href="{{ $nextUrl }}" class="btn btn-outline-primary">
                                            Next <i class="fas {{ $nextIcon }} ms-1"></i> <i class="fas fa-chevron-right ms-1"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Course Information -->
                @if($course)
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">About This Course</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <h6>Course: {{ $course->name }}</h6>
                                <p class="mb-0 text-muted">
                                    <i class="fas fa-book me-1"></i> {{ $lectures->count() }} lectures
                                </p>
                            </div>

                            <!-- Course Progress -->
                            <div class="col-md-12 mb-3">
                                @php
                                    $courseProgress = $course->getProgressPercentageForUser(Auth::id());
                                    $completedLectures = $course->getCompletedLectureCountForUser(Auth::id());
                                    $totalLectures = $course->total_lecture_count;
                                @endphp
                                <div class="course-progress">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="small text-muted">Course progress</span>
                                        <span class="small fw-bold course-progress-display">{{ number_format($courseProgress, 0) }}%</span>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar course-progress-bar {{ $courseProgress >= 90 ? 'bg-success' : 'bg-info' }}"
                                             role="progressbar"
                                             style="width: {{ $courseProgress }}%;"
                                             aria-valuenow="{{ $courseProgress }}"
                                             aria-valuemin="0"
                                             aria-valuemax="100"></div>
                                    </div>
                                    <p class="text-muted mt-1 small">
                                        <i class="fas fa-check-circle me-1"></i> <span class="completed-lectures-display">{{ $completedLectures }}/{{ $totalLectures }}</span> lectures completed
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-2">
                                    <a href="{{ route('user.course.purchased', $course->id) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-arrow-left me-1"></i> Back to Course Overview
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Rating Section -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Rate This Lecture</h5>
                    </div>
                    <div class="card-body">
                        <div id="user-rating-section">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="lecture-rating-stats mb-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <h3 class="mb-0 me-2" id="average-rating">{{ number_format($lecture->average_rating ?? 0, 1) }}</h3>
                                            <div id="average-star-display">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= ($lecture->average_rating ?? 0))
                                                        <i class="fas fa-star text-warning"></i>
                                                    @elseif ($i - 0.5 <= ($lecture->average_rating ?? 0))
                                                        <i class="fas fa-star-half-alt text-warning"></i>
                                                    @else
                                                        <i class="far fa-star text-warning"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>
                                        <p class="text-muted">
                                            ({{ $lecture->ratings_count ?? 0 }} ratings)
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="submit-rating mb-3">
                                        <p class="mb-2">Rate this lecture:</p>
                                        <input type="hidden" id="rating-value" value="0">
                                        <div class="star-rating-input mb-3">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="far fa-star fs-4 rating-star" data-value="{{ $i }}"></i>
                                            @endfor
                                        </div>
                                        <div class="mb-3">
                                            <label for="rating-comment" class="form-label">Your review (optional):</label>
                                            <textarea class="form-control" id="rating-comment" rows="3" placeholder="Share your experience with this lecture..."></textarea>
                                        </div>
                                        <button type="button" id="submit-rating" class="btn btn-primary">
                                            <i class="fas fa-star me-2"></i> Submit Rating
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div id="ratings-container" class="mt-4">
                                <!-- Ratings will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Q&A Section -->
                <div id="lecture-qa-section" class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Questions & Answers</h5>
                    </div>
                    <div class="card-body">
                        @if(!isset($isAdmin) || !$isAdmin)
                        <!-- Question Limits Display -->
                        <div id="question-limits-display" class="alert alert-info mb-3" style="display: none;">
                            <div class="question-limits-info"></div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="question" class="form-label fw-bold">Ask a Question</label>
                                    <textarea id="question-content" class="form-control mb-3" rows="3" placeholder="What would you like to know about this lecture?"></textarea>

                                    <!-- Attachment Options -->
                                    <div class="attachment-options mb-3">
                                        <div class="d-flex gap-3">
                                            <button type="button" id="attach-image-btn" class="btn btn-outline-secondary btn-sm">
                                                <i class="fas fa-image me-1"></i> Image
                                            </button>
                                            <button type="button" id="attach-pdf-btn" class="btn btn-outline-secondary btn-sm">
                                                <i class="fas fa-file-pdf me-1"></i> PDF
                                            </button>
                                            <button type="button" id="start-recording-btn" class="btn btn-outline-secondary btn-sm">
                                                <i class="fas fa-microphone me-1"></i> Record Voice
                                            </button>
                                            <button type="button" id="stop-recording-btn" class="btn btn-outline-danger btn-sm d-none">
                                                <i class="fas fa-stop-circle me-1"></i> Stop Recording
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Hidden File Inputs -->
                                    <input type="file" id="image-upload" accept="image/*" class="d-none">
                                    <input type="file" id="pdf-upload" accept="application/pdf" class="d-none">

                                    <!-- Preview Area -->
                                    <div id="attachments-preview" class="mb-3 d-none">
                                        <h6 class="border-bottom pb-2 mb-3">Attachments</h6>
                                        <div id="attachment-list" class="d-flex flex-wrap gap-3"></div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button id="submit-question" class="btn btn-primary">
                                        <i class="fas fa-paper-plane me-2"></i> Submit Question
                                    </button>
                                </div>
                            </div>
                        </div>

                        <hr>
                        @endif

                        <div class="row">
                            <div class="col-md-12">
                                <div id="questions-container" class="chat-container">
                                    <div class="text-center py-4 text-muted" id="no-questions-message">
                                        <i class="fas fa-comments fa-2x mb-3"></i>
                                        <p>No questions yet about this lecture. Be the first to ask!</p>
                                    </div>
                                    <!-- Questions will be loaded here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Admin Questions Section - Only visible to admins -->
                @if(isset($isAdmin) && $isAdmin && isset($adminQuestions) && $adminQuestions->count() > 0)
                <div id="admin-questions-section" class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">{{ Auth::user()->isAdmin() ? 'All Student Questions' : 'My Assigned Students Questions' }}</h5>
                    </div>
                    <div class="card-body">
                        @foreach($adminQuestions as $question)
                        <div class="question-card mb-4" id="admin-question-{{ $question->id }}">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $question->user->name }}</strong>
                                        <span class="text-muted ms-2">{{ $question->created_at->format('M d, Y H:i') }}</span>

                                        @if(Auth::user()->isAdmin())
                                            @php
                                                $adminAssignment = App\Models\AdminUserAssignment::where('user_id', $question->user_id)->first();
                                            @endphp

                                            @if($adminAssignment)
                                                <span class="badge bg-secondary ms-2">
                                                    Assigned to: {{ $adminAssignment->admin->name }}
                                                </span>
                                            @else
                                                <span class="badge bg-warning text-dark ms-2">Unassigned User</span>
                                            @endif
                                        @endif
                                    </div>
                                    <div>
                                        <span class="badge bg-{{ $question->status == 'pending' ? 'warning text-dark' : ($question->status == 'answered' ? 'success' : 'danger') }}">
                                            {{ ucfirst($question->status) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="question-content">
                                        <p>{{ $question->content }}</p>

                                        @if($question->attachments && $question->attachments->count() > 0)
                                        <div class="question-attachments mt-3">
                                            <h6 class="mb-2">Attachments:</h6>
                                            <div class="row">
                                                @foreach($question->attachments as $attachment)
                                                    @if($attachment->file_type == 'image')
                                                        <div class="col-md-3 mb-2">
                                                            <a href="{{ Storage::url($attachment->file_path) }}" target="_blank">
                                                                <img src="{{ Storage::url($attachment->file_path) }}" alt="Attachment" class="img-thumbnail" style="max-height: 150px;">
                                                            </a>
                                                        </div>
                                                    @elseif($attachment->file_type == 'pdf')
                                                        <div class="col-md-3 mb-2">
                                                            <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="btn btn-outline-danger">
                                                                <i class="fas fa-file-pdf me-1"></i> {{ $attachment->file_name ?? 'PDF Document' }}
                                                            </a>
                                                        </div>
                                                    @elseif($attachment->file_type == 'voice')
                                                        <div class="col-md-6 mb-2">
                                                            <audio controls class="w-100">
                                                                <source src="{{ Storage::url($attachment->file_path) }}" type="{{ $attachment->mime_type ?? 'audio/webm' }}">
                                                                Your browser does not support the audio element.
                                                            </audio>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
                                    </div>

                                    <!-- Answers Section -->
                                    @if($question->answers && $question->answers->count() > 0)
                                    <div class="answers-section mt-4">
                                        <h6 class="mb-3">Answers ({{ $question->answers->count() }})</h6>

                                        @foreach($question->answers as $answer)
                                        <div class="answer-card mb-3 {{ $answer->is_pinned ? 'border-start border-success border-3 ps-3' : '' }}">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <strong class="text-primary">{{ $answer->user->name }}</strong>
                                                    <small class="text-muted ms-2">{{ $answer->created_at->diffForHumans() }}</small>
                                                    @if($answer->is_pinned)
                                                        <span class="badge bg-success ms-2">
                                                            <i class="fas fa-thumbtack me-1"></i> Pinned
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <p class="mb-0 mt-2">{{ $answer->content }}</p>

                                            @if($answer->attachments && $answer->attachments->count() > 0)
                                            <div class="answer-attachments mt-2">
                                                @foreach($answer->attachments as $attachment)
                                                    @if($attachment->file_type == 'image')
                                                        <div class="mb-2">
                                                            <a href="{{ Storage::url($attachment->file_path) }}" target="_blank">
                                                                <img src="{{ Storage::url($attachment->file_path) }}" class="img-thumbnail" style="max-height: 150px;">
                                                            </a>
                                                        </div>
                                                    @elseif($attachment->file_type == 'pdf')
                                                        <div class="mb-2">
                                                            <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="btn btn-sm btn-outline-danger">
                                                                <i class="fas fa-file-pdf me-1"></i> {{ $attachment->file_name ?? 'PDF Document' }}
                                                            </a>
                                                        </div>
                                                    @elseif($attachment->file_type == 'voice')
                                                        <div class="mb-2">
                                                            <audio controls>
                                                                <source src="{{ Storage::url($attachment->file_path) }}" type="{{ $attachment->mime_type ?? 'audio/webm' }}">
                                                                Your browser does not support the audio element.
                                                            </audio>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif

                                    <!-- Answer Form -->
                                    @if($question->status == 'pending')
                                        @livewire('admin.answer-form', ['questionId' => $question->id], key('lecture-answer-form-'.$question->id))
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <style>
        /* Video Protection Styles */
        .video-protected {
            position: relative;
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            overflow: hidden;
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

        .copy-protected-notice {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            z-index: 20;
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }

        .copy-notice-visible {
            opacity: 1;
        }

        /* Custom video player styles */
        .video-container {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            width: 100% !important;
            height: auto !important;
            aspect-ratio: 16/9;
        }

        .plyr {
            width: 100% !important;
            height: 100% !important;
        }

        .plyr__video-wrapper {
            width: 100% !important;
            height: 100% !important;
        }

        .plyr iframe {
            width: 100% !important;
            height: 100% !important;
        }

        #video-player-container {
        position: relative;
        overflow: hidden; /* Ensure controls don't overflow */
        background-color: #000; /* Add a background for aspect ratio */
    }


    #custom-video-player {
        display: block; /* Prevents extra space below video */
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .video-controls-container {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 100%);
        padding: 5px 10px 10px 10px; /* Adjust padding if needed */
        transition: opacity 0.3s ease; /* Simplified transition */
        opacity: 0;
        z-index: 21; /* Ensure controls are above overlay */
    }


    #video-player-container:hover .video-controls-container,
    #video-player-container .video-controls-container.visible { /* Add class for touch devices */
        opacity: 1;
    }

        .video-progress {
            width: 100%;
            height: 5px;
            background-color: rgba(255,255,255,0.3);
            border-radius: 5px;
            margin-bottom: 10px;
            cursor: pointer;
            position: relative;
            margin-bottom: 8px; /* Slightly adjust spacing */
        }

        .video-progress-filled {
            position: absolute;
            top: 0;
            left: 0;
            width: 0%;
            height: 100%;
            background-color: #4CAF50;
            border-radius: 5px;
            transition: width 0.1s linear;
        }
        .video-controls .fullscreen {
        display: none !important;
    }


        .video-button {
            background-color: transparent;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s;
        }

        .video-button:hover {
            background-color: rgba(255,255,255,0.2);
        }

        .video-time {
            color: white;
            font-size: 14px;
        }

        .video-volume {
            display: flex;
            align-items: center;
            width: 120px;
        }

        .volume-slider {
            width: 60px;
            height: 5px;
            background-color: rgba(255,255,255,0.3);
            border-radius: 5px;
            margin-left: 10px;
            cursor: pointer;
            position: relative;
        }

        .volume-filled {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: white;
            border-radius: 5px;
        }

        /* Make custom player responsive */
        @media (max-width: 768px) {
            .video-volume .volume-slider {
                display: none;
            }

            .video-time {
                font-size: 12px;
            }

            .video-button {
                width: 32px;
                height: 32px;
                font-size: 14px;
            }
        }

        /* WhatsApp-style Chat UI */
        .chat-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
            max-width: 100%;
            padding: 15px;
            background-color: #e5ddd5;
            background-image: url("data:image/svg+xml,%3Csvg width='90' height='90' viewBox='0 0 90 90' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23d9d4cd' fill-opacity='0.4' fill-rule='evenodd'%3E%3Cpath d='M0 0h90v90H0z'/%3E%3Cpath d='M0 0h45v45H0z'/%3E%3Cpath d='M45 45h45v45H45z'/%3E%3C/g%3E%3C/svg%3E");
            border-radius: 8px;
        }

        /* Conversation groups */
        .conversation-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            position: relative;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 12px;
            border: 1px solid rgba(0,0,0,0.1);
            background-color: rgba(255,255,255,0.5);
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        /* Alternating conversation colors */
        .conversation-group:nth-child(odd) {
            background-color: rgba(255,255,255,0.7);
        }

        .conversation-group:nth-child(even) {
            background-color: rgba(255,255,255,0.3);
        }

        /* Date header for each conversation */
        .conversation-date {
            text-align: center;
            font-size: 0.75rem;
            margin-bottom: 10px;
            padding: 5px 10px;
            background-color: rgba(0,0,0,0.1);
            border-radius: 10px;
            color: #333;
            align-self: center;
            font-weight: 500;
        }

        .chat-message {
            display: flex;
            flex-direction: column;
            max-width: 70%;
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .chat-message.question {
            align-self: flex-start;
        }

        .chat-message.answer {
            align-self: flex-end;
        }

        .chat-bubble {
            padding: 10px 14px;
            border-radius: 10px;
            position: relative;
            box-shadow: 0 1px 1px rgba(0,0,0,0.1);
            word-wrap: break-word;
        }

        .chat-message.question .chat-bubble {
            background-color: #ffffff;
            border-top-left-radius: 0;
        }

        .chat-message.question .chat-bubble:before {
            content: "";
            position: absolute;
            top: 0;
            left: -10px;
            width: 0;
            height: 0;
            border-top: 10px solid #ffffff;
            border-left: 10px solid transparent;
        }

        .chat-message.answer .chat-bubble {
            background-color: #dcf8c6;
            border-top-right-radius: 0;
            text-align: left;
        }

        .chat-message.answer .chat-bubble:before {
            content: "";
            position: absolute;
            top: 0;
            right: -10px;
            width: 0;
            height: 0;
            border-top: 10px solid #dcf8c6;
            border-right: 10px solid transparent;
        }

        .chat-meta {
            display: flex;
            font-size: 0.7rem;
            color: #6c757d;
            margin-bottom: 2px;
            padding: 0 5px;
        }

        .chat-message.answer .chat-meta {
            justify-content: flex-end;
        }

        .chat-attachments {
            margin-top: 8px;
        }

        /* Timestamp style */
        .chat-time {
            font-size: 0.65rem;
            color: #888;
            align-self: flex-end;
            margin-top: 2px;
            margin-right: 5px;
        }

        /* Pinned answer styles */
        .chat-message.answer.pinned .chat-bubble {
            background-color: #c6e2f8;
            border-left: 2px solid #3498db;
        }

        .chat-message.answer.pinned .chat-bubble:before {
            border-top-color: #c6e2f8;
        }

        /* Status indicators */
        .status-indicator {
            position: absolute;
            top: 8px;
            right: 8px;
            padding: 3px 8px;
            border-radius: 15px;
            font-size: 0.65rem;
            font-weight: 500;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-answered {
            background-color: #d4edda;
            color: #155724;
        }

        .status-rejected {
            background-color: #f8d7da;
            color: #721c24;
        }

        /* Mobile responsive */
        @media (max-width: 576px) {
            .chat-message {
                max-width: 85%;
            }
        }



        /* Bouncing watermark for video area */
        .video-watermark {
            position: absolute;
            color: rgba(255, 255, 255, 0.7);
            font-size: 16px;
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 3px;
            background-color: rgba(0, 0, 0, 0.3);
            white-space: nowrap;
            z-index: 50;
            opacity: 0;
            animation: none;
            transition: opacity 0.2s ease;
            user-select: none;
            pointer-events: none;
        }

        .video-watermark.is-active {
            opacity: 1;
            animation: bounce 20s linear;
        }

        @keyframes bounce {
            0%, 100% {
                top: 10%;
                left: 10%;
                animation-timing-function: ease-out;
            }
            20% {
                top: 80%;
                left: 30%;
                animation-timing-function: ease-in;
            }
            40% {
                top: 20%;
                left: 70%;
                animation-timing-function: ease-out;
            }
            60% {
                top: 70%;
                left: 80%;
                animation-timing-function: ease-in;
            }
            80% {
                top: 30%;
                left: 20%;
                animation-timing-function: ease-out;
            }
        }

        /* Second bouncing watermark with different timing */
        .video-watermark.second {
            font-size: 14px;
            opacity: 0;
        }

        .video-watermark.second.is-active {
            opacity: 0.8;
            animation: bounce2 20s linear;
        }

        @keyframes bounce2 {
            0%, 100% {
                top: 20%;
                left: 80%;
                animation-timing-function: ease-in;
            }
            25% {
                top: 70%;
                left: 20%;
                animation-timing-function: ease-out;
            }
            50% {
                top: 30%;
                left: 50%;
                animation-timing-function: ease-in;
            }
            75% {
                top: 80%;
                left: 70%;
                animation-timing-function: ease-out;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdn.plyr.io/3.6.8/plyr.css" />
    <script src="https://cdn.plyr.io/3.6.8/plyr.js"></script>

    <script>
        // Add meta tag to disable Game Bar
        const gameBarMetaTag = document.createElement('meta');
        gameBarMetaTag.name = 'xbox-game-bar-allowed';
        gameBarMetaTag.content = 'false';
        document.head.appendChild(gameBarMetaTag);

        // Track key states to detect combinations with Windows key
        const keyStates = {
            alt: false,
            g: false,
            r: false,
            windowsPressed: false,
            lastKeyDown: 0
        };

        // Global key event listeners to block Game Bar shortcuts
        // Listen to ALL keyboard events at the document level with capture
        document.addEventListener('keydown', function(e) {
            // Track Alt key
            if (e.key === 'Alt') {
                keyStates.alt = true;
            }

            // Track G and R keys
            if (e.key === 'g' || e.key === 'G') {
                keyStates.g = true;

                // Immediately block Alt+G combinations
                if (keyStates.alt) {
                    console.log('Alt+G detected - blocking potential Game Bar shortcut');
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    return false;
                }
            }

            if (e.key === 'r' || e.key === 'R') {
                keyStates.r = true;

                // Immediately block Alt+R combinations
                if (keyStates.alt) {
                    console.log('Alt+R detected - blocking potential Game Bar shortcut');
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    return false;
                }
            }

            // Special check for Windows key (also called Meta key)
            if (e.key === 'Meta') {
                keyStates.windowsPressed = true;
            }

            // Check for Windows+Alt+G combination (Game Bar recording)
            if ((keyStates.windowsPressed && keyStates.alt && keyStates.g) ||
                (e.altKey && keyStates.g) ||
                (e.key === 'g' && e.altKey)) {
                console.log('Game Bar shortcut detected - blocking');
                showGameBarWarning('Game Bar shortcut blocked', true);

                // Block the event completely
                e.preventDefault();
                e.stopImmediatePropagation();

                // Reset key states to prevent lock-ups
                keyStates.alt = false;
                keyStates.g = false;
                keyStates.r = false;
                keyStates.windowsPressed = false;

                return false;
            }

            // Record timestamp of keydown
            keyStates.lastKeyDown = Date.now();
        }, true);  // Use capture phase for earlier interception

        // Use both keydown and keyup handlers
        document.addEventListener('keydown', function(e) {
            // Specifically block Game Bar shortcuts - explicit handling
            if (
                (e.key === 'g' && e.altKey) ||
                (e.key === 'G' && e.altKey) ||
                (e.key === 'r' && e.altKey) ||
                (e.key === 'R' && e.altKey)
            ) {
                console.log('Game Bar shortcut blocked via explicit handler');
                e.preventDefault();
                e.stopImmediatePropagation();
                showGameBarWarning('Game Bar shortcut blocked', true);
                return false;
            }
        }, true);  // Use capture phase for earlier interception

        // Listen for key up events to reset states
        window.addEventListener('keyup', function(e) {
            if (e.key === 'Alt') {
                keyStates.alt = false;
            }
            if (e.key === 'g' || e.key === 'G') {
                keyStates.g = false;
            }
            if (e.key === 'r' || e.key === 'R') {
                keyStates.r = false;
            }
            if (e.key === 'Meta') {
                keyStates.windowsPressed = false;
            }

            // If too much time has passed since last keydown, reset all states
            // This prevents keys getting "stuck" in pressed state
            if (Date.now() - keyStates.lastKeyDown > 3000) {
                keyStates.alt = false;
                keyStates.g = false;
                keyStates.r = false;
                keyStates.windowsPressed = false;
            }
        });

        // Reset key states when user switches away from tab/window
        window.addEventListener('blur', function() {
            keyStates.alt = false;
            keyStates.g = false;
            keyStates.r = false;
            keyStates.windowsPressed = false;
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Function to safely load videos via AJAX with protection
            window.loadVideoContentSafely = function(courseId, lectureId) {
                // Show loading indicator
                const videoContainer = document.querySelector('.ratio-16x9');
                if (videoContainer) {
                    videoContainer.innerHTML = `
                        <div class="d-flex justify-content-center align-items-center h-100 bg-light">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    `;
                }

                // Get CSRF token
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // Fetch lecture content securely
                fetch(`/user/courses/${courseId}/lectures/${lectureId}/content`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Create video player with appropriate data attributes instead of iframes
                        if (data.youtubeData) {
                            videoContainer.innerHTML = `
                                <div id="youtube-player" class="w-100 h-100">
                                    <div class="video-container w-100 h-100">
                                        <div class="video-player w-100 h-100"
                                            data-plyr-provider="youtube"
                                            data-plyr-embed-id="encrypted:${data.youtubeData.encrypted_id}"></div>
                                        <div class="video-overlay"></div>
                                    </div>
                                </div>
                            `;

                            // Initialize with Plyr and decrypt at runtime
                            initializeSecurePlayer();
                        } else if (data.videoPath) {
                            videoContainer.innerHTML = `
                                <div id="video-player-container" class="video-container w-100 h-100">
                                    <video id="custom-video-player" class="w-100 h-100 plyr-video" poster="${asset('img/video-poster.jpg')}" controls>
                                        <source src="${data.videoPath}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                            `;

                            // Initialize video player
                            const videoPlayers = Array.from(document.querySelectorAll('.plyr-video')).map(p => new Plyr(p, {
                                controls: ['play', 'progress', 'current-time', 'mute', 'volume'],
                                responsive: true
                            }));
                        } else {
                            videoContainer.innerHTML = `
                                <div class="d-flex align-items-center justify-content-center bg-light h-100">
                                    <p class="text-muted">No video available for this lecture</p>
                                </div>
                            `;
                        }

                        // Apply video protection
                        applyVideoProtection();
                    } else {
                        console.error('Error loading lecture content:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    videoContainer.innerHTML = `
                        <div class="d-flex align-items-center justify-content-center bg-light h-100">
                            <p class="text-danger">Error loading video content</p>
                        </div>
                    `;
                });
            };

            // Function to initialize secure players that need decryption
            function initializeSecurePlayer() {
                const youtubePlayers = Array.from(document.querySelectorAll('.video-player')).map(p => {
                    // Check if embed ID is encrypted
                    let embedId = p.getAttribute('data-plyr-embed-id');
                    console.log('AJAX player - Original embed ID:', embedId); // Debug log

                    if (embedId && embedId.startsWith('encrypted:')) {
                        // Extract and decode the Base64 encrypted ID
                        const encryptedId = embedId.replace('encrypted:', '');
                        try {
                            // Decode Base64
                            embedId = atob(encryptedId);
                            console.log('AJAX player - Decoded YouTube ID:', embedId); // Debug log
                        } catch (e) {
                            console.error('Error decoding YouTube ID:', e);

                            // Try to use fallback URL if available
                            const fallbackUrl = p.getAttribute('data-fallback-url');
                            if (fallbackUrl) {
                                console.log('Using fallback URL:', fallbackUrl);
                                // Extract YouTube ID from fallback URL
                                if (fallbackUrl.includes('youtube.com/watch?v=')) {
                                    embedId = new URL(fallbackUrl).searchParams.get('v');
                                } else if (fallbackUrl.includes('youtu.be/')) {
                                    embedId = fallbackUrl.split('/').pop();
                                }
                                console.log('Extracted ID from fallback:', embedId);
                            } else {
                                embedId = ''; // Use blank if all extraction fails
                            }
                        }

                        // Only update if we have a valid ID
                        if (embedId) {
                            p.setAttribute('data-plyr-embed-id', embedId);
                        }
                    }

                    // Initialize player with the decoded ID
                    console.log('AJAX player - Initializing with ID:', embedId); // Debug log

                    // Only create player if we have a valid ID
                    if (embedId) {
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
                    } else {
                        console.error('Could not initialize AJAX player: no valid YouTube ID');
                        return null;
                    }
                }).filter(player => player !== null); // Filter out null players

                return youtubePlayers;
            }

            // Initialize YouTube players with Plyr
            const youtubePlayers = Array.from(document.querySelectorAll('.video-player')).map(p => {
                // Check if embed ID is encrypted
                let embedId = p.getAttribute('data-plyr-embed-id');
                console.log('Original embed ID:', embedId); // Debug log

                if (embedId && embedId.startsWith('encrypted:')) {
                    // Extract and decode the Base64 encrypted ID
                    const encryptedId = embedId.replace('encrypted:', '');
                    try {
                        // Decode Base64
                        embedId = atob(encryptedId);
                        console.log('Decoded YouTube ID for playback:', embedId); // Debug log
                    } catch (e) {
                        console.error('Error decoding YouTube ID:', e);

                        // Try to use fallback URL if available
                        const fallbackUrl = p.getAttribute('data-fallback-url');
                        if (fallbackUrl) {
                            console.log('Using fallback URL:', fallbackUrl);
                            // Extract YouTube ID from fallback URL
                            if (fallbackUrl.includes('youtube.com/watch?v=')) {
                                embedId = new URL(fallbackUrl).searchParams.get('v');
                            } else if (fallbackUrl.includes('youtu.be/')) {
                                embedId = fallbackUrl.split('/').pop();
                            }
                            console.log('Extracted ID from fallback:', embedId);
                        } else {
                            embedId = ''; // Use blank if all extraction fails
                        }
                    }

                    // Only update if we have a valid ID
                    if (embedId) {
                        p.setAttribute('data-plyr-embed-id', embedId);
                    }
                }

                // Initialize player
                console.log('Initializing player with ID:', embedId); // Debug log

                // Only create player if we have a valid ID
                if (embedId) {
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
                } else {
                    console.error('Could not initialize player: no valid YouTube ID');
                    return null;
                }
            }).filter(player => player !== null); // Filter out null players

            // Initialize video files with Plyr
            const videoPlayers = Array.from(document.querySelectorAll('.plyr-video')).map(p => new Plyr(p, {
                controls: ['play', 'progress', 'current-time', 'mute', 'volume'],
                responsive: true
            }));

            // Combine all players for unified handling
            const allPlayers = [...youtubePlayers, ...videoPlayers];

            // Ensure all players are responsive
            allPlayers.forEach(player => {
                player.on('ready', () => {
                    if (player.elements.container) {
                        player.elements.container.style.width = "100%";
                        player.elements.container.style.height = "100%";
                    }
                });
            });

            // Apply video protection
            applyVideoProtection();

            // Q&A Functionality
            const courseId = {{ $course?->id ?? 'null' }};
            const lectureId = {{ $lecture->id }};

            // Store attachments
            let attachments = {
                images: [],
                pdfs: [],
                voice: null
            };
            let mediaRecorder = null;
            let audioChunks = [];

            // Load questions for this lecture
            loadQuestions();
            loadQuestionLimits();

            // Auto-refresh questions every 30 seconds
            setInterval(loadQuestions, 30000);
            setInterval(loadQuestionLimits, 30000);

            // Video protection functions
            function applyVideoProtection() {
                // Find the video container
                const videoContainer = document.querySelector('.video-container');
                if (!videoContainer) return;

                // Add protection classes
                videoContainer.classList.add('video-protected');

                // Create overlay div - check if it already exists
                let overlay = videoContainer.querySelector('.video-overlay');
                if (!overlay) {
                    overlay = document.createElement('div');
                    overlay.className = 'video-overlay';
                    videoContainer.appendChild(overlay);
                }

                // Create copy protection notice
                const notice = document.createElement('div');
                notice.className = 'copy-protected-notice';
                notice.textContent = 'Content is protected. Recording is prohibited.';
                videoContainer.appendChild(notice);

                // Create "Recording Detected" overlay
                const recordingDetectionOverlay = document.createElement('div');
                recordingDetectionOverlay.style.cssText = `
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(0,0,0,0.9);
                    z-index: 99999;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    color: white;
                    font-size: 24px;
                    text-align: center;
                    display: none;
                `;
                recordingDetectionOverlay.innerHTML = `
                    <div>
                        <i class="fas fa-exclamation-triangle" style="font-size: 48px; color: red; margin-bottom: 20px;"></i>
                        <h2>Screen Recording Detected</h2>
                        <p>Recording or capturing course content is prohibited.</p>
                        <p>User ID: {{ auth()->id() }}</p>
                        <p>This violation has been logged.</p>
                    </div>
                `;
                document.body.appendChild(recordingDetectionOverlay);

                // Detect screen recording attempts (not foolproof but adds some deterrent)
                try {
                    // Detect PictureInPicture which can be used for recording
                    const video = videoContainer.querySelector('video');
                    if (video) {
                        video.addEventListener('enterpictureinpicture', function() {
                            recordingDetectionOverlay.style.display = 'flex';
                            // Log the attempt
                            console.log('Picture-in-Picture attempt detected');
                            setTimeout(() => {
                                recordingDetectionOverlay.style.display = 'none';
                            }, 5000);
                            // Exit picture-in-picture mode
                            if (document.exitPictureInPicture) {
                                document.exitPictureInPicture();
                            }
                        });

                        // Disable video download by intercepting media source
                        video.addEventListener('loadedmetadata', function() {
                            // Check if video has src attribute
                            if (video.getAttribute('src')) {
                                // Store original source
                                const originalSrc = video.getAttribute('src');

                                // Remove the src attribute
                                video.removeAttribute('src');

                                // Create a blob URL that expires
                                fetch(originalSrc)
                                .then(response => response.blob())
                                .then(blob => {
                                    const blobUrl = URL.createObjectURL(blob);
                                    video.src = blobUrl;

                                    // Set a timeout to revoke the blob URL to make it expire
                                    setTimeout(() => {
                                        URL.revokeObjectURL(blobUrl);
                                    }, 3600000); // Expire after 1 hour
                                })
                                .catch(err => {
                                    // If fetch fails, restore original src
                                    video.src = originalSrc;
                                    console.error("Failed to create temporary blob URL", err);
                                });
                            }
                        });
                    }

                    // Add more recording detection when browser APIs are available
                    if (navigator.mediaDevices && navigator.mediaDevices.getDisplayMedia) {
                        const originalGetDisplayMedia = navigator.mediaDevices.getDisplayMedia;
                        navigator.mediaDevices.getDisplayMedia = function(constraints) {
                            recordingDetectionOverlay.style.display = 'flex';
                            console.log('Screen capture attempt detected');

                            // Show warning for 5 seconds then hide
                            setTimeout(() => {
                                recordingDetectionOverlay.style.display = 'none';
                            }, 5000);

                            return originalGetDisplayMedia.call(this, constraints);
                        };
                    }

                    // Add protection against screen capture API
                    function detectScreenCapture() {
                        if (document.fullscreenElement) {
                            document.addEventListener('keydown', function(e) {
                                // PrintScreen key is pressed
                                if (e.key === 'PrintScreen') {
                                    recordingDetectionOverlay.style.display = 'flex';
                                    setTimeout(() => {
                                        recordingDetectionOverlay.style.display = 'none';
                                    }, 5000);
                                    e.preventDefault();
                                    return false;
                                }
                            });
                        }
                    }

                    // Listen for fullscreen changes
                    document.addEventListener('fullscreenchange', detectScreenCapture);
                    document.addEventListener('webkitfullscreenchange', detectScreenCapture);
                    document.addEventListener('mozfullscreenchange', detectScreenCapture);
                    document.addEventListener('MSFullscreenChange', detectScreenCapture);
                } catch (e) {
                    console.error('Error setting up recording detection:', e);
                }

                // Disable right click on video and overlay
                videoContainer.addEventListener('contextmenu', function(e) {
                    e.preventDefault();
                    showCopyProtectionNotice(notice);
                    return false;
                });

                // Disable drag on iframe or video
                const mediaElement = videoContainer.querySelector('iframe') || videoContainer.querySelector('video');
                if (mediaElement) {
                    mediaElement.addEventListener('dragstart', function(e) {
                        e.preventDefault();
                        showCopyProtectionNotice(notice);
                        return false;
                    });
                }

                // Disable keyboard shortcuts that might be used for capturing
                document.addEventListener('keydown', function(e) {
                    // Check if video is in view
                    const rect = videoContainer.getBoundingClientRect();
                    const isInView = (
                        rect.top >= 0 &&
                        rect.left >= 0 &&
                        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
                    );

                    if (!isInView) return;

                    // Prevent PrintScreen, Ctrl+P (print), Ctrl+S (save)
                    if (e.key === 'PrintScreen' ||
                        (e.ctrlKey && e.key === 'p') ||
                        (e.ctrlKey && e.key === 's') ||
                        (e.key === 'F12')) {
                        e.preventDefault();
                        showCopyProtectionNotice(notice);
                        return false;
                    }
                });
            }

            function showCopyProtectionNotice(notice) {
                notice.classList.add('copy-notice-visible');
                setTimeout(() => {
                    notice.classList.remove('copy-notice-visible');
                }, 3000);
            }

            // Attach image event listener
            document.getElementById('attach-image-btn')?.addEventListener('click', function() {
                document.getElementById('image-upload')?.click();
            });

            // Attach PDF event listener
            document.getElementById('attach-pdf-btn')?.addEventListener('click', function() {
                document.getElementById('pdf-upload')?.click();
            });

            // Image upload handling
            document.getElementById('image-upload')?.addEventListener('change', function(e) {
                if (e.target.files.length > 0) {
                    const file = e.target.files[0];
                    if (file.size > 5 * 1024 * 1024) { // 5MB limit
                        alert('Image file is too large. Maximum allowed size is 5MB.');
                        return;
                    }

                    attachments.images.push(file);
                    updateAttachmentPreview();
                }
            });

            // PDF upload handling
            document.getElementById('pdf-upload')?.addEventListener('change', function(e) {
                if (e.target.files.length > 0) {
                    const file = e.target.files[0];
                    if (file.size > 10 * 1024 * 1024) { // 10MB limit
                        alert('PDF file is too large. Maximum allowed size is 10MB.');
                        return;
                    }

                    attachments.pdfs.push(file);
                    updateAttachmentPreview();
                }
            });

            // Voice recording handling
            document.getElementById('start-recording-btn')?.addEventListener('click', function() {
                // Check if browser supports audio recording
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    alert('Your browser does not support audio recording.');
                    return;
                }

                // Special handling for Chrome in local environment
                const isLocalhost = window.location.hostname === 'localhost' ||
                                   window.location.hostname === '127.0.0.1' ||
                                   window.location.hostname.includes('192.168.');

                const isChrome = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);

                if (isLocalhost && isChrome && window.location.protocol !== 'https:') {
                    console.warn('Audio recording may not work on Chrome in insecure contexts. If you encounter errors, try using Firefox or Edge for local development, or add a self-signed SSL certificate.');
                }

                // Enable required audio constraints
                const constraints = {
                    audio: {
                        echoCancellation: true,
                        noiseSuppression: true,
                        autoGainControl: true
                    }
                };

                // Request audio permission with specific constraints
                navigator.mediaDevices.getUserMedia(constraints)
                    .then(stream => {
                        try {
                            // Hide start button, show stop button
                            document.getElementById('start-recording-btn').classList.add('d-none');
                            document.getElementById('stop-recording-btn').classList.remove('d-none');

                            // Initialize media recorder with specific settings
                            const options = { mimeType: 'audio/webm' };
                            try {
                                mediaRecorder = new MediaRecorder(stream, options);
                            } catch (e) {
                                // Fallback if preferred format not supported
                                console.log('Using default MediaRecorder format');
                                mediaRecorder = new MediaRecorder(stream);
                            }

                            audioChunks = [];

                            // Collect audio chunks
                            mediaRecorder.addEventListener('dataavailable', event => {
                                if (event.data.size > 0) {
                                    audioChunks.push(event.data);
                                }
                            });

                            // When recording stops
                            mediaRecorder.addEventListener('stop', () => {
                                try {
                                    // Use the correct MIME type based on what the MediaRecorder actually created
                                    // Most browsers support audio/webm or audio/ogg
                                    const recordedMimeType = mediaRecorder.mimeType || 'audio/webm';
                                    const fileExtension = recordedMimeType.includes('webm') ? 'webm' :
                                                         recordedMimeType.includes('ogg') ? 'ogg' : 'wav';

                                    // Create blob from audio chunks with the MIME type that was actually used
                                    const audioBlob = new Blob(audioChunks, { type: recordedMimeType });

                                    // Use the correct file extension and MIME type
                                    attachments.voice = new File([audioBlob], `voice-note.${fileExtension}`, {
                                        type: recordedMimeType
                                    });

                                    console.log('Audio recording created with MIME type:', recordedMimeType);
                                    console.log('File created with name:', attachments.voice.name);

                                    // Update preview
                                    updateAttachmentPreview();

                                    // Clean up
                                    stream.getTracks().forEach(track => track.stop());
                                } catch (err) {
                                    console.error('Error creating audio file:', err);
                                    alert('Error creating audio recording. Please try again.');
                                }
                            });

                            // Set a timeout of 10ms before starting recording
                            // This helps avoid some browser bugs
                            setTimeout(() => {
                                // Start recording with 10ms timeslice for regular chunks
                                mediaRecorder.start(10);
                                console.log('MediaRecorder started', mediaRecorder.state);
                            }, 10);
                        } catch (err) {
                            console.error('Error initializing media recorder:', err);
                            alert('Error initializing audio recording. Please try again.');

                            // Reset UI
                            document.getElementById('start-recording-btn').classList.remove('d-none');
                            document.getElementById('stop-recording-btn').classList.add('d-none');

                            // Clean up
                            stream.getTracks().forEach(track => track.stop());
                        }
                    })
                    .catch(error => {
                        console.error('Error accessing microphone:', error);
                        let errorMessage = 'Failed to access microphone. ';

                        if (error.name === 'NotAllowedError' || error.name === 'PermissionDeniedError') {
                            errorMessage += 'Please ensure you have given permission to use the microphone.';
                            if (isLocalhost) {
                                errorMessage += '\n\nIn Chrome, microphone access may be blocked in non-HTTPS local environments. Try using Firefox or Edge instead.';
                            }
                        } else if (error.name === 'NotFoundError' || error.name === 'DevicesNotFoundError') {
                            errorMessage += 'No microphone device found. Please connect a microphone.';
                        } else if (error.name === 'NotReadableError' || error.name === 'TrackStartError') {
                            errorMessage += 'Microphone is already in use by another application.';
                        } else if (error.name === 'OverconstrainedError') {
                            errorMessage += 'Microphone constraints cannot be satisfied.';
                        } else if (error.name === 'SecurityError') {
                            errorMessage += 'Use of microphone is not allowed in this context. Please ensure you are using HTTPS.';
                            if (isLocalhost) {
                                errorMessage += '\n\nFor local development, try using Firefox which allows microphone access in non-HTTPS contexts.';
                            }
                        } else {
                            errorMessage += 'Please ensure you are using HTTPS and have given permission.';
                            if (isLocalhost) {
                                errorMessage += '\n\nFor local development, this might be a browser security limitation.';
                            }
                        }

                        alert(errorMessage);
                    });
            });

            // Stop recording
            document.getElementById('stop-recording-btn')?.addEventListener('click', function() {
                if (mediaRecorder && mediaRecorder.state !== 'inactive') {
                    try {
                        mediaRecorder.stop();
                    } catch (err) {
                        console.error('Error stopping recording:', err);
                    }
                    document.getElementById('stop-recording-btn').classList.add('d-none');
                    document.getElementById('start-recording-btn').classList.remove('d-none');
                }
            });

            // Update attachment preview
            function updateAttachmentPreview() {
                const previewArea = document.getElementById('attachments-preview');
                const attachmentList = document.getElementById('attachment-list');

                // Clear previous preview
                attachmentList.innerHTML = '';

                // Check if we have any attachments
                const hasAttachments = attachments.images.length > 0 ||
                                       attachments.pdfs.length > 0 ||
                                       attachments.voice !== null;

                if (hasAttachments) {
                    previewArea.classList.remove('d-none');

                    // Add image previews
                    attachments.images.forEach((image, index) => {
                        const imgPreview = document.createElement('div');
                        imgPreview.className = 'attachment-preview';
                        imgPreview.innerHTML = `
                            <div class="card" style="width: 100px;">
                                <img src="${URL.createObjectURL(image)}" class="card-img-top" style="height: 80px; object-fit: cover;">
                                <div class="card-footer p-1">
                                    <button type="button" class="btn btn-sm btn-danger w-100 remove-attachment" data-type="image" data-index="${index}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        `;
                        attachmentList.appendChild(imgPreview);
                    });

                    // Add PDF previews
                    attachments.pdfs.forEach((pdf, index) => {
                        const pdfPreview = document.createElement('div');
                        pdfPreview.className = 'attachment-preview';
                        pdfPreview.innerHTML = `
                            <div class="card" style="width: 100px;">
                                <div class="card-body p-2 text-center">
                                    <i class="fas fa-file-pdf fa-2x text-danger"></i>
                                    <p class="mb-0 small text-truncate">${pdf.name}</p>
                                </div>
                                <div class="card-footer p-1">
                                    <button type="button" class="btn btn-sm btn-danger w-100 remove-attachment" data-type="pdf" data-index="${index}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        `;
                        attachmentList.appendChild(pdfPreview);
                    });

                    // Add voice preview
                    if (attachments.voice) {
                        const voicePreview = document.createElement('div');
                        voicePreview.className = 'attachment-preview';
                        voicePreview.innerHTML = `
                            <div class="card" style="width: 180px;">
                                <div class="card-body p-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-microphone text-primary"></i>
                                        <audio controls class="w-100">
                                            <source src="${URL.createObjectURL(attachments.voice)}" type="${attachments.voice.type}">
                                            Your browser does not support the audio element.
                                        </audio>
                                    </div>
                                </div>
                                <div class="card-footer p-1">
                                    <button type="button" class="btn btn-sm btn-danger w-100 remove-attachment" data-type="voice">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        `;
                        attachmentList.appendChild(voicePreview);
                    }

                    // Add event listeners to remove buttons
                    document.querySelectorAll('.remove-attachment').forEach(btn => {
                        btn.addEventListener('click', function() {
                            const type = this.dataset.type;
                            if (type === 'image') {
                                attachments.images.splice(parseInt(this.dataset.index), 1);
                            } else if (type === 'pdf') {
                                attachments.pdfs.splice(parseInt(this.dataset.index), 1);
                            } else if (type === 'voice') {
                                attachments.voice = null;
                            }
                            updateAttachmentPreview();
                        });
                    });

                } else {
                    previewArea.classList.add('d-none');
                }
            }

            // Submit question
            document.getElementById('submit-question')?.addEventListener('click', function() {
                const content = document.getElementById('question-content').value.trim();

                if (!content && !attachments.images.length && !attachments.pdfs.length && !attachments.voice) {
                    alert('Please enter your question or add an attachment');
                    return;
                }

                // Disable button during submission
                this.disabled = true;
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Submitting...';

                // Create FormData object for file uploads
                const formData = new FormData();
                formData.append('content', content);
                formData.append('course_id', courseId);
                formData.append('lecture_id', lectureId);

                // Add attachments
                attachments.images.forEach((image, index) => {
                    formData.append(`images[${index}]`, image);
                });

                attachments.pdfs.forEach((pdf, index) => {
                    formData.append(`pdfs[${index}]`, pdf);
                });

                if (attachments.voice) {
                    formData.append('voice', attachments.voice);
                }

                // Send AJAX request to submit question
                fetch('{{ route('questions.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Clear textarea and attachments
                        document.getElementById('question-content').value = '';
                        attachments = {
                            images: [],
                            pdfs: [],
                            voice: null
                        };
                        updateAttachmentPreview();

                        // Show success message
                        alert('Your question has been submitted successfully!');

                        // Reload questions and limits
                        loadQuestions();
                        loadQuestionLimits();
                    } else {
                        alert('Error: ' + data.message);
                        // Reload limits to show current status
                        loadQuestionLimits();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while submitting your question.');
                })
                .finally(() => {
                    // Re-enable button
                    const btn = document.getElementById('submit-question');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-paper-plane me-2"></i> Submit Question';
                });
            });

            // Function to load questions
            function loadQuestions() {
                // Get CSRF token
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch('{{ route('questions.get') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        course_id: courseId,
                        lecture_id: lectureId,
                        user_id: {{ auth()->id() }}
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const container = document.getElementById('questions-container');
                        const noQuestionsMsg = document.getElementById('no-questions-message');

                        if (data.questions.length > 0) {
                            // Hide the "no questions" message
                            noQuestionsMsg.style.display = 'none';

                            // Sort questions by created_at in descending order (newest first)
                            data.questions.sort((a, b) => {
                                const dateA = new Date(a.created_at.replace(/(\d+)(st|nd|rd|th)/, "$1"));
                                const dateB = new Date(b.created_at.replace(/(\d+)(st|nd|rd|th)/, "$1"));
                                return dateB - dateA; // Newest first
                            });

                            // Generate HTML for questions and answers
                            let html = '';

                            data.questions.forEach(question => {
                                // Create conversation container for this question and its answers
                                html += '<div class="conversation-group mb-4">';

                                // Add status indicator
                                html += `<div class="status-indicator status-${question.status.toLowerCase()}">${formatStatus(question.status)}</div>`;

                                // Add date header
                                const formattedDate = formatDate(question.created_at);
                                html += `<div class="conversation-date">${formattedDate}</div>`;

                                // Prepare attachment previews
                                let attachmentsHtml = '';
                                if (question.attachments && question.attachments.length > 0) {
                                    attachmentsHtml += '<div class="chat-attachments">';

                                    question.attachments.forEach(attachment => {
                                        if (attachment.type === 'image') {
                                            attachmentsHtml += `
                                                <div class="mb-2">
                                                    <a href="${attachment.url}" target="_blank">
                                                        <img src="${attachment.url}" class="img-thumbnail" style="max-height: 150px;">
                                                    </a>
                                                </div>`;
                                        } else if (attachment.type === 'pdf') {
                                            attachmentsHtml += `
                                                <div class="mb-2">
                                                    <a href="${attachment.url}" target="_blank" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-file-pdf me-1"></i> ${attachment.name}
                                                    </a>
                                                </div>`;
                                        } else if (attachment.type === 'voice') {
                                            attachmentsHtml += `
                                                <div class="mb-2">
                                                    <audio controls>
                                                        <source src="${attachment.url}" type="${attachment.mime_type || 'audio/webm'}">
                                                        Your browser does not support the audio element.
                                                    </audio>
                                                </div>`;
                                        }
                                    });

                                    attachmentsHtml += '</div>';
                                }

                                // Create question chat bubble
                                html += `
                                <div class="chat-message question">
                                    <div class="chat-meta">
                                        <span class="user-name">${question.user}</span>
                                        <span class="badge ms-2 bg-${getStatusBadgeColor(question.status)}">${formatStatus(question.status)}</span>
                                    </div>
                                    <div class="chat-bubble">
                                        <p class="mb-0">${question.content}</p>
                                        ${attachmentsHtml}
                                        <div class="chat-time">${formatTime(question.created_at)}</div>
                                    </div>
                                </div>`;

                                // Add answers if any
                                if (question.answers && question.answers.length > 0) {
                                    html += renderAnswers(question.answers);
                                }

                                // Close the conversation group
                                html += '</div>';
                            });

                            container.innerHTML = html;
                        } else {
                            // Show the "no questions" message
                            noQuestionsMsg.style.display = 'block';
                        }
                    } else {
                        console.error('Error loading questions:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }

            // Helper function to render answers
            function renderAnswers(answers) {
                let html = '';

                // Sort answers to show pinned answers first
                answers.sort((a, b) => {
                    if (a.is_pinned && !b.is_pinned) return -1;
                    if (!a.is_pinned && b.is_pinned) return 1;
                    return 0;
                });

                answers.forEach(answer => {
                    // Prepare attachments for answers if any
                    let attachmentsHtml = '';
                    if (answer.attachments && answer.attachments.length > 0) {
                        attachmentsHtml += '<div class="chat-attachments">';

                        answer.attachments.forEach(attachment => {
                            if (attachment.type === 'image') {
                                attachmentsHtml += `
                                    <div class="mb-2">
                                        <a href="${attachment.url}" target="_blank">
                                            <img src="${attachment.url}" class="img-thumbnail" style="max-height: 150px;">
                                        </a>
                                    </div>`;
                            } else if (attachment.type === 'pdf') {
                                attachmentsHtml += `
                                    <div class="mb-2">
                                        <a href="${attachment.url}" target="_blank" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-file-pdf me-1"></i> ${attachment.name}
                                        </a>
                                    </div>`;
                            } else if (attachment.type === 'voice' || attachment.type === 'audio') {
                                attachmentsHtml += `
                                    <div class="mb-2">
                                        <audio controls>
                                            <source src="${attachment.url}" type="${attachment.mime_type || 'audio/webm'}">
                                            Your browser does not support the audio element.
                                        </audio>
                                    </div>`;
                            }
                        });

                        attachmentsHtml += '</div>';
                    }

                    // Create answer chat bubble
                    html += `
                    <div class="chat-message answer ${answer.is_pinned ? 'pinned' : ''}">
                        <div class="chat-meta">
                            ${answer.is_pinned ? '<span class="badge bg-success me-2"><i class="fas fa-thumbtack me-1"></i> Pinned</span>' : ''}
                            <span class="user-name">${answer.user}</span>
                        </div>
                        <div class="chat-bubble">
                            <p class="mb-0">${answer.content}</p>
                            ${attachmentsHtml}
                            <div class="chat-time">${formatTime(answer.created_at)}</div>
                        </div>
                    </div>`;
                });

                return html;
            }

            // Helper function to get badge color based on status
            function getStatusBadgeColor(status) {
                switch(status) {
                    case 'pending': return 'warning';
                    case 'answered': return 'success';
                    case 'rejected': return 'danger';
                    default: return 'secondary';
                }
            }

            // Helper function to format status text
            function formatStatus(status) {
                return status.charAt(0).toUpperCase() + status.slice(1);
            }

            // Helper function to format time in WhatsApp style
            function formatTime(dateStr) {
                // If the date string is already short (like "2 hours ago"), just return it
                if (dateStr && dateStr.length < 20) {
                    return dateStr;
                }

                try {
                    // Try to parse the date - first remove any ordinal suffixes that might cause issues
                    const cleanStr = dateStr.replace(/(\d+)(st|nd|rd|th)/, "$1");

                    // Create a date object - this works with ISO strings and many other formats
                    const date = new Date(cleanStr);

                    // Check if the date is valid
                    if (isNaN(date.getTime())) {
                        // If we can't parse it, return the original string
                        return dateStr;
                    }

                    // Format time as HH:MM (using locale settings)
                    return date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                } catch (e) {
                    console.log('Date parsing error:', e);
                    // In case of any error, return the original string
                    return dateStr;
                }
            }

            // Helper function to format date in WhatsApp style
            function formatDate(dateStr) {
                // If the date string is already short (like "2 hours ago"), just return it
                if (dateStr && dateStr.length < 20) {
                    return dateStr;
                }

                try {
                    // Try to parse the date - first remove any ordinal suffixes that might cause issues
                    const cleanStr = dateStr.replace(/(\d+)(st|nd|rd|th)/, "$1");

                    // Create a date object - this works with ISO strings and many other formats
                    const date = new Date(cleanStr);

                    // Check if the date is valid
                    if (isNaN(date.getTime())) {
                        // If we can't parse it, return the original string
                        return dateStr;
                    }

                    // Format date as MMM d, yyyy
                    return date.toLocaleDateString([], {month: 'short', day: '2-digit', year: 'numeric'});
                } catch (e) {
                    console.log('Date parsing error:', e);
                    // In case of any error, return the original string
                    return dateStr;
                }
            }

            const WATERMARK_VISIBLE_MS = 20000;
            const WATERMARK_CYCLE_MS = 120000;
            let watermarkCycleId = null;

            function startWatermarkCycle() {
                if (watermarkCycleId) return;

                const watermarks = Array.from(document.querySelectorAll('.video-watermark'));
                if (watermarks.length === 0) return;

                const showWatermarks = () => {
                    watermarks.forEach((watermark) => {
                        watermark.classList.remove('is-active');
                        void watermark.offsetWidth;
                        watermark.classList.add('is-active');
                    });

                    setTimeout(() => {
                        watermarks.forEach((watermark) => watermark.classList.remove('is-active'));
                    }, WATERMARK_VISIBLE_MS);
                };

                showWatermarks();
                watermarkCycleId = setInterval(showWatermarks, WATERMARK_CYCLE_MS);
            }

            // Create user watermark
            function createWatermark() {
                const userInfo = {
                    id: '{{ auth()->id() }}',
                    // You can replace this with actual phone number if available in your user model
                    phone: '{{ auth()->user()->phone ?? "Protected User" }}',
                    email: '{{ auth()->user()->email }}'
                };

                // Remove global watermark that shows at the bottom of the page
                // Only keep video-specific watermarks

                // Add specific bouncing watermarks for video area only
                addBounceWatermarksToVideo(userInfo);
            }

            // Add bouncing watermarks to the video container
            function addBounceWatermarksToVideo(userInfo) {
                // Find video container
                const videoContainer = document.querySelector('.video-container') ||
                                      document.querySelector('.ratio-16x9');

                if (!videoContainer) return;

                // Make sure the video container has position relative for absolute positioning
                videoContainer.style.position = 'relative';
                videoContainer.style.overflow = 'hidden';

                // Create email watermark (bouncing like a ball)
                const emailWatermark = document.createElement('div');
                emailWatermark.className = 'video-watermark';
                emailWatermark.innerText = `Email: ${userInfo.email}`;
                videoContainer.appendChild(emailWatermark);

                // Create phone watermark (different bouncing pattern)
                const phoneWatermark = document.createElement('div');
                phoneWatermark.className = 'video-watermark second';
                phoneWatermark.innerText = `ID: ${userInfo.id} | Phone: ${userInfo.phone}`;
                videoContainer.appendChild(phoneWatermark);

                startWatermarkCycle();
            }

            // Initialize watermark
            createWatermark();

            // Load and display question limits
            function loadQuestionLimits() {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch('{{ route('questions.limits') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        course_id: courseId,
                        lecture_id: lectureId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.limit_info) {
                        displayQuestionLimits(data.limit_info);
                    }
                })
                .catch(error => {
                    console.error('Error loading question limits:', error);
                });
            }

            // Display question limits in the UI
            function displayQuestionLimits(limitInfo) {
                const limitContainer = document.getElementById('question-limits-display');

                if (!limitContainer) {
                    console.warn('Question limits display container not found');
                    return;
                }

                let html = '<div class="question-limits-info">';

                // Show course level limits only if flagged to show
                if (limitInfo.show_course_limit && limitInfo.course) {
                    const courseLimitClass = limitInfo.course.can_ask ? 'text-success' : 'text-danger';
                    html += `
                        <div class="limit-item mb-2">
                            <strong>Course Questions:</strong>
                            <span class="${courseLimitClass}">
                                ${limitInfo.course.remaining}/${limitInfo.course.limit} remaining
                            </span>
                        </div>
                    `;
                }

                // Show lecture level limits only if flagged to show
                if (limitInfo.show_lecture_limit && limitInfo.lecture) {
                    const lectureLimitClass = limitInfo.lecture.can_ask ? 'text-success' : 'text-danger';
                    html += `
                        <div class="limit-item mb-2">
                            <strong>Lecture Questions:</strong>
                            <span class="${lectureLimitClass}">
                                ${limitInfo.lecture.remaining}/${limitInfo.lecture.limit} remaining
                            </span>
                        </div>
                    `;
                }

                // Show warning if limits reached
                if (!limitInfo.can_ask) {
                    html += `
                        <div class="alert alert-warning mt-2 mb-0" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            ${limitInfo.blocking_reason}
                        </div>
                    `;
                }

                html += '</div>';

                limitContainer.innerHTML = html;
                limitContainer.style.display = 'block';

                // Disable submit button if limits reached
                const submitBtn = document.getElementById('submit-question');
                if (submitBtn) {
                    submitBtn.disabled = !limitInfo.can_ask;
                    if (!limitInfo.can_ask) {
                        submitBtn.classList.add('opacity-50');
                        submitBtn.title = limitInfo.blocking_reason;
                    } else {
                        submitBtn.classList.remove('opacity-50');
                        submitBtn.title = '';
                    }
                }
            }
        });

        // EXTREME Windows Game Bar blocking
        (function() {
            // Add multiple meta tags to block Game Bar at all costs
            const metaTags = [
                { name: 'xbox-game-bar-allowed', content: 'false' },
                { name: 'gamepadAPI', content: 'false' },
                { name: 'gamepad', content: 'false' }
            ];

            metaTags.forEach(meta => {
                const metaTag = document.createElement('meta');
                metaTag.name = meta.name;
                metaTag.content = meta.content;
                document.head.appendChild(metaTag);
            });

            // Completely block the Windows Game Bar JavaScript API
            // This is the most aggressive approach
            if (window.Windows) {
                try {
                    // Attempt to completely disable the Game Bar API
                    Object.defineProperty(window, 'Windows', {
                        get: function() {
                            console.log('Blocked access to Windows namespace');
                            return undefined;
                        },
                        configurable: false
                    });
                } catch (e) {
                    console.log('Could not override Windows namespace');
                }
            }

            // Moderate keyboard monitoring - non-blocking to video playback
            // This runs every 100ms to check for Windows key combinations
            const gameBarMonitor = setInterval(function() {
                // Check if any Xbox Game Bar related elements exist
                const gameBarElements = document.querySelectorAll(
                    '[class*="xbox"], [class*="game-bar"], [id*="xbox"], [id*="game-bar"], ' +
                    '[class*="gamebar"], [id*="gamebar"], [class*="xboxlive"], [id*="xboxlive"]'
                );

                if (gameBarElements.length > 0) {
                    console.log('Game Bar elements detected!');
                    // Show warning but don't remove elements
                    showGameBarWarning();
                }

                // Non-blocking recording notification
                if (keyStates.windowsPressed && keyStates.alt) {
                    console.log('Windows+Alt combination detected');

                    // Reset Windows key state
                    keyStates.windowsPressed = false;

                    // Show a non-blocking warning notification
                    showGameBarWarning('Windows+Alt combination detected. Recording is prohibited.', true);
                }
            }, 100);

            // Handle focus changes to detect switching to Game Bar
            let lastActiveTime = Date.now();

            document.addEventListener('visibilitychange', function() {
                const timeDiff = Date.now() - lastActiveTime;

                // If page was hidden for a short time (Game Bar appears quickly)
                if (document.hidden === false && timeDiff < 1000 && timeDiff > 100) {
                    console.log('Possible Game Bar activation detected - visibility change');
                    showGameBarWarning();
                }

                lastActiveTime = Date.now();
            });

            // Additional blur/focus detection for Game Bar
            window.addEventListener('blur', function() {
                lastActiveTime = Date.now();
            });

            window.addEventListener('focus', function() {
                const timeDiff = Date.now() - lastActiveTime;

                // If window regained focus quickly (Game Bar appears quickly)
                if (timeDiff < 1000 && timeDiff > 100) {
                    console.log('Possible Game Bar activation detected - focus change');
                    showGameBarWarning();
                }
            });

            // Non-blocking warning message
            function showGameBarWarning(customMessage, isImportant) {
                // Create a temporary overlay warning that won't interfere with video playback
                const warning = document.createElement('div');
                warning.style.cssText = `
                    position: fixed;
                    top: ${isImportant ? '50%' : '20px'};
                    left: ${isImportant ? '50%' : '20px'};
                    transform: ${isImportant ? 'translate(-50%, -50%)' : 'none'};
                    background-color: ${isImportant ? 'rgba(255, 0, 0, 0.9)' : 'rgba(255, 0, 0, 0.8)'};
                    color: white;
                    padding: ${isImportant ? '20px 30px' : '10px 15px'};
                    border-radius: 5px;
                    z-index: 9999;
                    font-weight: bold;
                    text-align: center;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.4);
                    pointer-events: ${isImportant ? 'auto' : 'none'};
                    max-width: ${isImportant ? '400px' : '300px'};
                `;

                if (isImportant) {
                    warning.innerHTML = `
                        <div>${customMessage || 'Recording Attempt Detected'}</div>
                        <div style="font-size: 0.9em; margin-top: 8px;">User ID: {{ auth()->id() }} | {{ auth()->user()->email }}</div>
                        <div style="font-size: 0.9em; margin-top: 4px;">This violation has been logged.</div>
                        ${isImportant ? '<button style="margin-top: 15px; padding: 5px 15px; border: none; background: white; color: red; border-radius: 3px; cursor: pointer;">Dismiss</button>' : ''}
                    `;
                } else {
                    warning.textContent = customMessage || 'Possible Game Bar activity detected';
                }

                document.body.appendChild(warning);

                if (isImportant) {
                    warning.querySelector('button').addEventListener('click', function() {
                        if (document.body.contains(warning)) {
                            document.body.removeChild(warning);
                        }
                    });

                    // Auto-remove after 8 seconds for important warnings
                    setTimeout(() => {
                        if (document.body.contains(warning)) {
                            document.body.removeChild(warning);
                        }
                    }, 8000);
                } else {
                    // Auto-remove after 3 seconds for minor warnings
                    setTimeout(() => {
                        if (document.body.contains(warning)) {
                            document.body.removeChild(warning);
                        }
                    }, 3000);
                }
            }
        })();

        // Track key states to detect combinations with Windows key
    </script>
    @endpush
    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize rating stars
    const ratingStars = document.querySelectorAll('.rating-star');
    const ratingValue = document.getElementById('rating-value');
    const submitButton = document.getElementById('submit-rating');

    // Load existing ratings
    loadRatings();

    // Handle star rating selection
    ratingStars.forEach(star => {
        star.addEventListener('mouseover', function() {
            const value = parseInt(this.dataset.value);
            highlightStars(value);
        });

        star.addEventListener('mouseleave', function() {
            const selectedValue = parseInt(ratingValue.value);
            highlightStars(selectedValue);
        });

        star.addEventListener('click', function() {
            const value = parseInt(this.dataset.value);
            ratingValue.value = value;
            highlightStars(value);
        });
    });

    // Function to highlight stars
    function highlightStars(count) {
        ratingStars.forEach(star => {
            const starValue = parseInt(star.dataset.value);
            if (starValue <= count) {
                star.classList.remove('far');
                star.classList.add('fas', 'text-warning');
            } else {
                star.classList.remove('fas', 'text-warning');
                star.classList.add('far');
            }
        });
    }

    // Submit rating
    if (submitButton) {
        submitButton.addEventListener('click', function() {
            const rating = parseInt(ratingValue.value);
            const comment = document.getElementById('rating-comment').value.trim();

            if (rating === 0) {
                alert('Please select a rating before submitting.');
                return;
            }

            // Disable button during submission
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Submitting...';

            // Get CSRF token
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Send rating to server
            fetch('{{ route('lectures.rate', ['lecture' => $lecture->id]) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    rating: rating,
                    comment: comment
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);

                    // Update average rating display
                    const avgRatingEl = document.getElementById('average-rating');
                    avgRatingEl.textContent = parseFloat(data.average_rating).toFixed(1);

                    // Update rating count
                    const ratingCountEl = document.querySelector('.lecture-rating-stats .text-muted');
                    ratingCountEl.textContent = `(${data.rating_count} ratings)`;

                    // Update average stars display
                    updateAverageStarDisplay(data.average_rating);

                    // Reload the list of ratings
                    loadRatings();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while submitting your rating.');
            })
            .finally(() => {
                // Re-enable button
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="fas fa-star me-2"></i> Submit Rating';
            });
        });
    }

    // Update average star display
    function updateAverageStarDisplay(rating) {
        const stars = document.querySelectorAll('#average-star-display i');
        stars.forEach((star, index) => {
            star.className = ''; // reset
            if (index + 1 <= rating) {
                star.className = 'fas fa-star text-warning';
            } else if (index + 0.5 <= rating) {
                star.className = 'fas fa-star-half-alt text-warning';
            } else {
                star.className = 'far fa-star text-warning';
            }
        });
    }

    // Load ratings list
    function loadRatings() {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const ratingsContainer = document.getElementById('ratings-container');

        // Loading state
        ratingsContainer.innerHTML = `
            <div class="text-center py-3">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Loading ratings...</p>
            </div>
        `;

        fetch('{{ route('lectures.ratings', ['lecture' => $lecture->id]) }}')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.ratings.length > 0) {
                        let html = '<div class="ratings-list mt-4">';
                        data.ratings.forEach(rating => {
                            const date = new Date(rating.created_at);
                            const formattedDate = date.toLocaleDateString([], { year: 'numeric', month: 'short', day: 'numeric' });

                            html += `
                                <div class="rating-item mb-3 p-3 border-bottom">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <strong>${rating.user.name}</strong>
                                            <div class="d-inline-block ms-2">`;

                            for (let i = 1; i <= 5; i++) {
                                if (i <= rating.rating) {
                                    html += '<i class="fas fa-star text-warning small"></i>';
                                } else {
                                    html += '<i class="far fa-star text-warning small"></i>';
                                }
                            }

                            html += `
                                            </div>
                                        </div>
                                        <div>
                                            <small class="text-muted">${formattedDate}</small>
                                        </div>
                                    </div>`;

                            if (rating.comment) {
                                html += `<p class="mb-0 text-muted">${rating.comment}</p>`;
                            }

                            html += `</div>`;
                        });
                        html += '</div>';
                        ratingsContainer.innerHTML = html;
                    } else {
                        ratingsContainer.innerHTML = `
                            <div class="text-center py-3">
                                <p class="text-muted">No ratings yet. Be the first to rate this lecture!</p>
                            </div>
                        `;
                    }
                } else {
                    ratingsContainer.innerHTML = `
                        <div class="alert alert-warning">
                            Failed to load ratings.
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                ratingsContainer.innerHTML = `
                    <div class="alert alert-danger">
                        An error occurred while loading ratings.
                    </div>
                `;
            });
    }
});
</script>

<script>


// Enhanced Lecture Progress Tracking Script
document.addEventListener('DOMContentLoaded', function() {
    // Constants and variables
    const lectureId = {{ $lecture->id }};
    const courseId = {{ $course?->id ?? 'null' }};
    let players = [];
    let currentPlayer = null;
    let progressInterval = null;
    let lastProgressUpdate = 0;
    let isProgressUpdating = false;
    let userCompletedLecture = false;
    let playerReadyInterval = null;
    let playerDetectionAttempts = 0;
    const MAX_DETECTION_ATTEMPTS = 40; // Try for 20 seconds (40 * 500ms)

    // Debug mode for more verbose console logging
    const debugMode = true;

    // Add immediate debug info
    console.log('🎬 Progress tracking script loaded');
    console.log('📍 Current page URL:', window.location.href);
    console.log('🔍 Looking for video elements...');
    console.log('📺 Video players found:', document.querySelectorAll('.video-player').length);
    console.log('🎭 Plyr elements found:', document.querySelectorAll('.plyr').length);
    console.log('📹 YouTube iframes found:', document.querySelectorAll('iframe[src*="youtube"]').length);
    console.log('🎯 Lecture ID:', lectureId, 'Course ID:', courseId);

    // Debug logging function
    function debugLog(...args) {
        if (debugMode) {
            console.log('[Progress Tracker]', ...args);
        }
    }

    // Wait for YouTube API to be ready and Plyr to initialize
    function waitForPlayerInitialization() {
        debugLog('Waiting for player initialization...');

        playerReadyInterval = setInterval(() => {
            // Clear interval if we've tried too many times
            if (playerDetectionAttempts >= MAX_DETECTION_ATTEMPTS) {
                clearInterval(playerReadyInterval);
                debugLog('Giving up on player detection after max attempts');
                return;
            }

            playerDetectionAttempts++;

            // Check for Plyr instance of YouTube players (improved detection)
            const youtubePlyrElements = document.querySelectorAll('.plyr--youtube');
            const videoPlayerElements = document.querySelectorAll('.video-player');

            debugLog('Looking for Plyr players...', {
                youtubePlyrElements: youtubePlyrElements.length,
                videoPlayerElements: videoPlayerElements.length,
                windowPlyr: !!window.Plyr,
                plyrInstances: window.Plyr ? window.Plyr.instances : 'No Plyr'
            });

            if (youtubePlyrElements.length > 0 || videoPlayerElements.length > 0) {
                debugLog('Found potential Plyr elements');

                // Find all YouTube players initialized by Plyr
                const plyrInstances = [];

                // Method 1: Check elements for plyr property
                videoPlayerElements.forEach(element => {
                    if (element.plyr) {
                        debugLog('Found Plyr instance on element:', element);
                        plyrInstances.push(element.plyr);
                    }
                });

                // Method 2: Check global Plyr instances
                if (window.Plyr && window.Plyr.instances) {
                    window.Plyr.instances.forEach(instance => {
                        if (instance && instance.elements && instance.elements.container) {
                            debugLog('Found global Plyr instance:', instance);
                            plyrInstances.push(instance);
                        }
                    });
                }

                // Method 3: Check for any Plyr-like objects
                videoPlayerElements.forEach(element => {
                    // Look for any property that might be a Plyr instance
                    for (let prop in element) {
                        if (element[prop] && typeof element[prop] === 'object' &&
                            element[prop].constructor &&
                            element[prop].constructor.name === 'Plyr') {
                            debugLog('Found Plyr instance via property scan:', element[prop]);
                            plyrInstances.push(element[prop]);
                        }
                    }
                });

                // Remove duplicates
                const uniquePlyrInstances = [...new Set(plyrInstances)];

                if (uniquePlyrInstances.length > 0) {
                    debugLog('Found Plyr instances:', uniquePlyrInstances);
                    clearInterval(playerReadyInterval);
                    players = uniquePlyrInstances;
                    initializeProgressTracking();
                    return;
                }
            }

            // Check for YouTube iframe API being ready
            const youtubeIframes = document.querySelectorAll('iframe[src*="youtube"]');
            if (youtubeIframes.length > 0 && window.YT && window.YT.Player) {
                debugLog('Found YouTube iframes with API ready');

                // Get the player instances from the iframes
                const youtubePlayerInstances = Array.from(youtubeIframes).map(iframe => {
                    // Try to get the player instance
                    return iframe.id && window.YT.get(iframe.id);
                }).filter(Boolean);

                if (youtubePlayerInstances.length > 0) {
                    debugLog('Found YouTube player instances:', youtubePlayerInstances);
                    clearInterval(playerReadyInterval);
                    players = youtubePlayerInstances;
                    initializeProgressTracking();
                    return;
                }
            }

            // Check for plain HTML5 video elements
            const videoElements = document.querySelectorAll('video');
            if (videoElements.length > 0) {
                debugLog('Found HTML5 video elements:', videoElements);
                clearInterval(playerReadyInterval);
                players = Array.from(videoElements);
                initializeProgressTracking();
                return;
            }

            debugLog('Still looking for video players... Attempt', playerDetectionAttempts);

            // Fallback: If we've tried many times, try to force initialize with any available elements
            if (playerDetectionAttempts > 15) {
                debugLog('Attempting fallback player detection...');

                // Try to find any video-related elements
                const anyVideoElements = document.querySelectorAll('video, iframe[src*="youtube"], .video-player, .plyr');
                if (anyVideoElements.length > 0) {
                    debugLog('Found fallback video elements:', anyVideoElements);
                    clearInterval(playerReadyInterval);
                    players = Array.from(anyVideoElements);
                    initializeProgressTracking();
                    return;
                }
            }
        }, 500); // Check every 500ms
    }

    // Initialize progress tracking
    function initializeProgressTracking() {
        debugLog('Initializing progress tracking with players:', players);

        if (players.length === 0) {
            debugLog('No video players found');
            return;
        }

        // Set up the first player as current
        currentPlayer = players[0];
        debugLog('Current player set to:', currentPlayer);

        // Set up progress tracking for each player
        players.forEach(player => {
            setupProgressTracking(player);
        });

        // Load initial progress
        loadProgress();
    }

    // Function to set up progress tracking for a player
    function setupProgressTracking(player) {
        debugLog('Setting up tracking for player:', player);

        // Determine player type and set up appropriate event listeners
        if (player instanceof YT.Player) {
            // YouTube iframe API player
            debugLog('YouTube iframe API player detected');

            player.addEventListener('onStateChange', (event) => {
                const state = event.data;

                if (state === YT.PlayerState.PLAYING) {
                    debugLog('YouTube video playing');
                    clearInterval(progressInterval);
                    progressInterval = setInterval(() => updateProgress(), 5000);
                }
                else if (state === YT.PlayerState.PAUSED) {
                    debugLog('YouTube video paused');
                    updateProgress(true);
                    clearInterval(progressInterval);
                }
                else if (state === YT.PlayerState.ENDED) {
                    debugLog('YouTube video ended');
                    markLectureCompleted();
                    clearInterval(progressInterval);
                }
            });
        }
        else if (player.constructor && player.constructor.name === 'Plyr') {
            // Plyr player instance
            debugLog('Plyr player instance detected');

            player.on('play', () => {
                debugLog('Plyr video playing');
                clearInterval(progressInterval);
                progressInterval = setInterval(() => updateProgress(), 5000);

                // Reset recording detection when video starts playing
                videoEndedRecently = false;
                recordingWarningShown = false;
                visibilityChangeCount = 0;
                focusChangeCount = 0;
                suspiciousActivityScore = 0;
                console.log('🎬 Video started - reset recording detection flags');
            });

            player.on('pause', () => {
                debugLog('Plyr video paused');
                updateProgress(true);
                clearInterval(progressInterval);
            });

            player.on('ended', () => {
                debugLog('Plyr video ended');
                markLectureCompleted();
                clearInterval(progressInterval);

                // Disable recording detection for 5 minutes after video ends
                videoEndedRecently = true;
                console.log('🎬 Video ended - disabling recording detection for 5 minutes');
                setTimeout(() => {
                    videoEndedRecently = false;
                    console.log('🎬 Recording detection re-enabled after video end period');
                }, 300000); // 5 minutes
            });
        }
        else if (player instanceof HTMLVideoElement) {
            // Native HTML5 video element
            debugLog('Native HTML5 video element detected');

            player.addEventListener('play', () => {
                debugLog('HTML5 video playing');
                clearInterval(progressInterval);
                progressInterval = setInterval(() => updateProgress(), 5000);
            });

            player.addEventListener('pause', () => {
                debugLog('HTML5 video paused');
                updateProgress(true);
                clearInterval(progressInterval);
            });

            player.addEventListener('ended', () => {
                debugLog('HTML5 video ended');
                markLectureCompleted();
                clearInterval(progressInterval);
            });
        }
        else {
            debugLog('Unknown player type:', player);
            // Try generic approach for unknown player types
            try {
                if (player.on) {
                    // Looks like an event emitter interface
                    player.on('play', () => {
                        debugLog('Generic player playing');
                        clearInterval(progressInterval);
                        progressInterval = setInterval(() => updateProgress(), 5000);

                        // Reset recording detection when video starts playing
                        videoEndedRecently = false;
                        recordingWarningShown = false;
                        visibilityChangeCount = 0;
                        focusChangeCount = 0;
                        suspiciousActivityScore = 0;
                        console.log('🎬 Video started - reset recording detection flags');
                    });

                    player.on('pause', () => {
                        debugLog('Generic player paused');
                        updateProgress(true);
                        clearInterval(progressInterval);
                    });

                    player.on('ended', () => {
                        debugLog('Generic player ended');
                        markLectureCompleted();
                        clearInterval(progressInterval);
                    });
                }
                else if (player.addEventListener) {
                    // Looks like DOM event interface
                    player.addEventListener('play', () => {
                        debugLog('Generic DOM player playing');
                        clearInterval(progressInterval);
                        progressInterval = setInterval(() => updateProgress(), 5000);
                    });

                    player.addEventListener('pause', () => {
                        debugLog('Generic DOM player paused');
                        updateProgress(true);
                        clearInterval(progressInterval);
                    });

                    player.addEventListener('ended', () => {
                        debugLog('Generic DOM player ended');
                        markLectureCompleted();
                        clearInterval(progressInterval);
                    });
                }
            } catch (e) {
                debugLog('Error setting up event listeners for unknown player type:', e);
            }
        }
    }

    // Handle browser unload (tab close, navigation away)
    window.addEventListener('beforeunload', () => {
        updateProgress(true);
    });

    // Function to update progress
    function updateProgress(force = false) {
        // Don't update if another update is in progress
        if (isProgressUpdating) {
            debugLog('Progress update already in progress, skipping');
            return;
        }

        // Don't update too frequently unless forced
        const now = Date.now();
        if (!force && (now - lastProgressUpdate < 3000)) {
            debugLog('Skipping update as too recent');
            return;
        }

        // Don't update if lecture is already completed
        // if (userCompletedLecture) {
        //    debugLog('Lecture is already completed, skipping update');
        //    return;
        // }

        // Try to find a player if we don't have one
        if (!currentPlayer) {
            debugLog('No current player, trying to find one...');

            // Try to find any video element
            const videoElements = document.querySelectorAll('video');
            if (videoElements.length > 0) {
                currentPlayer = videoElements[0];
                debugLog('Found HTML5 video element:', currentPlayer);
            }

            // Try to find Plyr instances
            const plyrElements = document.querySelectorAll('.video-player');
            plyrElements.forEach(element => {
                if (element.plyr) {
                    currentPlayer = element.plyr;
                    debugLog('Found Plyr instance:', currentPlayer);
                }
            });

            // If still no player, skip this update
            if (!currentPlayer) {
                debugLog('Still no current player found, skipping update');
                return;
            }
        }

        try {
            // Get current time and duration based on player type
            let currentTime = 0;
            let duration = 0;

            if (currentPlayer instanceof YT.Player) {
                // YouTube iframe API player
                try {
                    currentTime = Math.floor(currentPlayer.getCurrentTime());
                    duration = Math.floor(currentPlayer.getDuration());
                    debugLog('YouTube API player time:', currentTime, 'duration:', duration);
                } catch (e) {
                    debugLog('Error getting YouTube player time:', e);
                    return;
                }
            }
            else if (currentPlayer.constructor && currentPlayer.constructor.name === 'Plyr') {
                // Plyr player instance
                currentTime = Math.floor(currentPlayer.currentTime);
                duration = Math.floor(currentPlayer.duration);
                debugLog('Plyr player time:', currentTime, 'duration:', duration);
            }
            else if (currentPlayer instanceof HTMLVideoElement) {
                // Native HTML5 video element
                currentTime = Math.floor(currentPlayer.currentTime);
                duration = Math.floor(currentPlayer.duration);
                debugLog('HTML5 video time:', currentTime, 'duration:', duration);
            }
            else {
                // Try generic approaches
                try {
                    if (typeof currentPlayer.getCurrentTime === 'function') {
                        currentTime = Math.floor(currentPlayer.getCurrentTime());
                        duration = Math.floor(currentPlayer.getDuration());
                    } else if (currentPlayer.currentTime !== undefined) {
                        currentTime = Math.floor(currentPlayer.currentTime);
                        duration = Math.floor(currentPlayer.duration);
                    }
                    debugLog('Generic player time:', currentTime, 'duration:', duration);
                } catch (e) {
                    debugLog('Error getting generic player time:', e);
                    return;
                }
            }

            // Skip if we don't have valid time data
            if (isNaN(currentTime) || isNaN(duration) || duration <= 0) {
                debugLog('Invalid time data, skipping update');
                return;
            }

            // Calculate progress percentage
            const progressPercent = (currentTime / duration) * 100;

            // Update UI first (for immediate feedback)
            updateProgressUI(progressPercent);

            // Only send to server if we have meaningful progress
            if (currentTime > 0 && duration > 0) {
                debugLog(`Sending progress to server: ${currentTime}s / ${duration}s (${progressPercent.toFixed(1)}%)`);

                const requestData = {
                    lecture_id: lectureId,
                    current_time: currentTime,
                    duration: duration
                };
                console.log('📤 Sending progress data:', requestData);

                isProgressUpdating = true;
                lastProgressUpdate = now;

                // Get CSRF token
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // Send progress to server
                fetch('{{ route("lecture-progress.update") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(requestData)
                })
                .then(response => {
                    console.log('📡 Server response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`Server responded with ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('📊 Server response data:', data);
                    if (data.success) {
                        debugLog('Progress updated successfully:', data);
                        console.log('✅ Progress saved to database successfully!');

                        // Update progress display
                        updateProgressDisplay(data.progress.progress_percent);

                        // Update course progress
                        updateCourseProgress(data.course_progress);

                        // Check if lecture is now marked as completed
                        if (data.lecture_completed && !userCompletedLecture) {
                            userCompletedLecture = true;
                            showCompletionMessage();
                        }
                    } else {
                        console.error('❌ Progress update returned error:', data);
                        debugLog('Progress update returned error:', data);
                    }
                })
                .catch(error => {
                    console.error('❌ Error updating progress:', error);
                    console.error('Full error details:', error.message);
                })
                .finally(() => {
                    isProgressUpdating = false;
                });
            } else {
                debugLog('Not enough meaningful progress to update server');
                isProgressUpdating = false;
            }
        } catch (e) {
            console.error('Error in updateProgress:', e);
            isProgressUpdating = false;
        }
    }

    // Function to update UI with progress
    function updateProgressUI(progressPercent) {
        // Update progress bar
        const progressBar = document.querySelector('.lecture-progress-bar');
        if (progressBar) {
            progressBar.style.width = progressPercent + '%';
            progressBar.setAttribute('aria-valuenow', progressPercent);
        }

        // Update progress text
        const progressDisplay = document.querySelector('.lecture-progress-display');
        if (progressDisplay) {
            progressDisplay.textContent = Math.round(progressPercent) + '%';
        }
    }

    // Function to load existing progress
    function loadProgress() {
        debugLog('Loading existing progress data');

        // Don't load if player isn't ready
        if (!currentPlayer) {
            debugLog('No current player, skipping loading progress');
            return;
        }

        // Get CSRF token
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Fetch progress from server
        fetch('{{ route("lecture-progress.get") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                lecture_id: lectureId
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Server responded with ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                debugLog('Loaded progress data:', data);

                // If there's saved progress and it's not at the beginning
                if (data.progress && data.progress.current_time > 0) {
                    // Ask user if they want to resume from where they left off
                    const resumeTime = data.progress.current_time;
                    const formattedTime = formatTime(resumeTime);

                    // Create resume dialog
                    const resumeDialog = document.createElement('div');
                    resumeDialog.className = 'resume-dialog';
                    resumeDialog.innerHTML = `
                        <div class="resume-dialog-content bg-light p-3 rounded shadow-sm position-absolute top-50 start-50 translate-middle" style="z-index: 1000; width: 300px;">
                            <p>You were at <strong>${formattedTime}</strong>. Would you like to resume from where you left off?</p>
                            <div class="d-flex justify-content-end gap-2">
                                <button class="btn btn-sm btn-outline-secondary resume-no">No, Start Over</button>
                                <button class="btn btn-sm btn-primary resume-yes">Yes, Resume</button>
                            </div>
                        </div>
                    `;

                    // Find video container and append dialog
                    const videoContainer = document.querySelector('.video-container') ||
                                          document.querySelector('.ratio-16x9');

                    if (videoContainer) {
                        videoContainer.style.position = 'relative';
                        videoContainer.appendChild(resumeDialog);

                        // Handle resume choice
                        resumeDialog.querySelector('.resume-yes').addEventListener('click', function() {
                            // Set time based on player type
                            if (currentPlayer instanceof YT.Player) {
                                currentPlayer.seekTo(resumeTime);
                            }
                            else if (currentPlayer.constructor && currentPlayer.constructor.name === 'Plyr') {
                                currentPlayer.currentTime = resumeTime;
                            }
                            else if (currentPlayer instanceof HTMLVideoElement) {
                                currentPlayer.currentTime = resumeTime;
                            }
                            else {
                                // Try generic approach
                                try {
                                    if (typeof currentPlayer.seekTo === 'function') {
                                        currentPlayer.seekTo(resumeTime);
                                    } else if (currentPlayer.currentTime !== undefined) {
                                        currentPlayer.currentTime = resumeTime;
                                    }
                                } catch (e) {
                                    debugLog('Error seeking to time:', e);
                                }
                            }

                            videoContainer.removeChild(resumeDialog);
                        });

                        resumeDialog.querySelector('.resume-no').addEventListener('click', function() {
                            // Set to beginning based on player type
                            if (currentPlayer instanceof YT.Player) {
                                currentPlayer.seekTo(0);
                            }
                            else if (currentPlayer.constructor && currentPlayer.constructor.name === 'Plyr') {
                                currentPlayer.currentTime = 0;
                            }
                            else if (currentPlayer instanceof HTMLVideoElement) {
                                currentPlayer.currentTime = 0;
                            }
                            else {
                                // Try generic approach
                                try {
                                    if (typeof currentPlayer.seekTo === 'function') {
                                        currentPlayer.seekTo(0);
                                    } else if (currentPlayer.currentTime !== undefined) {
                                        currentPlayer.currentTime = 0;
                                    }
                                } catch (e) {
                                    debugLog('Error seeking to beginning:', e);
                                }
                            }

                            videoContainer.removeChild(resumeDialog);
                        });

                        // Auto-hide after 10 seconds (user didn't choose)
                        setTimeout(() => {
                            if (videoContainer.contains(resumeDialog)) {
                                // Default to resuming if user doesn't choose
                                try {
                                    if (currentPlayer instanceof YT.Player) {
                                        currentPlayer.seekTo(resumeTime);
                                    }
                                    else if (currentPlayer.constructor && currentPlayer.constructor.name === 'Plyr') {
                                        currentPlayer.currentTime = resumeTime;
                                    }
                                    else if (currentPlayer instanceof HTMLVideoElement) {
                                        currentPlayer.currentTime = resumeTime;
                                    }
                                    else {
                                        // Try generic approach
                                        if (typeof currentPlayer.seekTo === 'function') {
                                            currentPlayer.seekTo(resumeTime);
                                        } else if (currentPlayer.currentTime !== undefined) {
                                            currentPlayer.currentTime = resumeTime;
                                        }
                                    }
                                } catch (e) {
                                    debugLog('Error auto-resuming:', e);
                                }

                                videoContainer.removeChild(resumeDialog);
                            }
                        }, 10000);
                    }
                }

                // Update progress display
                const progressToShow = data.progress ? data.progress.progress_percent : 0;
                console.log('📊 Loading progress from database:', progressToShow + '%');
                console.log('📊 Full progress data:', data.progress);
                updateProgressDisplay(progressToShow);

                // Update course progress
                updateCourseProgress(data.course_progress);

                // Check if lecture is already completed
                if (data.lecture_completed) {
                    userCompletedLecture = true;
                }
            } else {
                debugLog('Error loading progress:', data);
            }
        })
        .catch(error => {
            console.error('Error loading progress:', error);
        });
    }

    // Function to mark lecture as completed
    function markLectureCompleted() {
        // Don't mark again if already completed
        if (userCompletedLecture) {
            debugLog('Lecture already completed, skipping markLectureCompleted');
            return;
        }

        debugLog('Marking lecture as completed');

        // Get CSRF token
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Send completion to server
        fetch('{{ route("lecture-progress.complete") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                lecture_id: lectureId
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Server responded with ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                debugLog('Lecture marked as completed:', data);
                userCompletedLecture = true;

                // Show completion message
                showCompletionMessage();

                // Update course progress
                updateCourseProgress(data.course_progress);
            } else {
                debugLog('Error marking lecture as completed:', data);
            }
        })
        .catch(error => {
            console.error('Error marking lecture as completed:', error);
        });
    }

    // Function to update progress display
    function updateProgressDisplay(percent) {
        console.log('🎯 updateProgressDisplay called with:', percent + '%');
        console.log('🎯 Rounded percentage:', Math.round(percent) + '%');

        // Find progress display elements
        const progressDisplays = document.querySelectorAll('.lecture-progress-display');
        console.log('🎯 Found progress displays:', progressDisplays.length);

        progressDisplays.forEach((display, index) => {
            const newText = `${Math.round(percent)}%`;
            console.log(`🎯 Updating display ${index + 1} to:`, newText);
            display.textContent = newText;
        });

        // Update progress bars
        const progressBars = document.querySelectorAll('.lecture-progress-bar');
        console.log('🎯 Found progress bars:', progressBars.length);

        progressBars.forEach((bar, index) => {
            console.log(`🎯 Updating bar ${index + 1} width to:`, percent + '%');
            bar.style.width = `${percent}%`;
            bar.setAttribute('aria-valuenow', percent);

            // Update color based on completion
            if (percent >= 90) {
                bar.classList.remove('bg-primary');
                bar.classList.add('bg-success');
                console.log(`🎯 Bar ${index + 1} marked as completed (green)`);
            }
        });
    }

    // Function to update course progress
    function updateCourseProgress(courseProgress) {
        // Check if data exists
        if (!courseProgress) {
            debugLog('No course progress data provided');
            return;
        }

        debugLog('Updating course progress UI with:', courseProgress);

        // Update course progress display
        const courseProgressDisplays = document.querySelectorAll('.course-progress-display');
        courseProgressDisplays.forEach(display => {
            display.textContent = `${Math.round(courseProgress.percent)}%`;
        });

        // Update course progress bars
        const courseProgressBars = document.querySelectorAll('.course-progress-bar');
        courseProgressBars.forEach(bar => {
            bar.style.width = `${courseProgress.percent}%`;
            bar.setAttribute('aria-valuenow', courseProgress.percent);

            // Update color based on completion
            if (courseProgress.percent >= 90) {
                bar.classList.remove('bg-info');
                bar.classList.add('bg-success');
            }
        });

        // Update completed lectures count
        const lectureCountDisplays = document.querySelectorAll('.completed-lectures-display');
        lectureCountDisplays.forEach(display => {
            display.textContent = `${courseProgress.completed_lectures}/${courseProgress.total_lectures}`;
        });
    }

    // Function to show completion message
    function showCompletionMessage() {
        debugLog('Showing completion message');

        // Create completion message
        const completionMessage = document.createElement('div');
        completionMessage.className = 'completion-message';
        completionMessage.innerHTML = `
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                <div class="me-3">
                    <i class="fas fa-check-circle fa-2x"></i>
                </div>
                <div>
                    <h5 class="mb-0">Congratulations!</h5>
                    <p class="mb-0">You've completed this lecture. Continue to the next one to keep learning!</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;

        // Add to page
        const lectureCard = document.querySelector('.card-body');
        if (lectureCard) {
            lectureCard.insertBefore(completionMessage, lectureCard.firstChild);
        }
    }

    // Helper function to format time in MM:SS
    function formatTime(seconds) {
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = Math.floor(seconds % 60);
        return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
    }

    // Manual Plyr initialization and progress tracking
    function initializePlyrDirectly() {
        debugLog('🚀 Attempting direct Plyr initialization...');

        // Check if Plyr is available
        if (typeof Plyr === 'undefined') {
            debugLog('❌ Plyr not loaded yet, retrying...');
            setTimeout(initializePlyrDirectly, 1000);
            return;
        }

        debugLog('✅ Plyr is available, looking for video elements...');

        // Find video player elements
        const videoElements = document.querySelectorAll('.video-player');
        debugLog('📺 Found video elements:', videoElements.length);

        if (videoElements.length > 0) {
            videoElements.forEach((element, index) => {
                debugLog(`🎬 Processing video element ${index + 1}:`, element);

                // Check if already has Plyr instance
                if (element.plyr) {
                    debugLog('✅ Element already has Plyr instance:', element.plyr);
                    if (!players.includes(element.plyr)) {
                        players.push(element.plyr);
                        currentPlayer = element.plyr;
                        setupProgressTracking(element.plyr);
                    }
                } else {
                    debugLog('🔧 Creating new Plyr instance for element...');

                    try {
                        // Create new Plyr instance
                        const plyrInstance = new Plyr(element, {
                            controls: ['play-large', 'play', 'progress', 'current-time', 'mute', 'volume', 'fullscreen'],
                            youtube: {
                                noCookie: false,
                                rel: 0,
                                showinfo: 0,
                                iv_load_policy: 3,
                                modestbranding: 1
                            }
                        });

                        debugLog('✅ Plyr instance created:', plyrInstance);

                        // Add to players array
                        players.push(plyrInstance);
                        currentPlayer = plyrInstance;

                        // Set up progress tracking
                        setupProgressTracking(plyrInstance);

                        debugLog('🎯 Progress tracking set up for Plyr instance');

                    } catch (error) {
                        debugLog('❌ Error creating Plyr instance:', error);
                    }
                }
            });
        } else {
            debugLog('❌ No video elements found');
        }
    }

    // Wait a bit to allow page resources to load before initializing
    setTimeout(() => {
        debugLog('🚀 Starting player initialization...');

        // Try direct Plyr initialization first
        initializePlyrDirectly();

        // Also start the original detection method
        waitForPlayerInitialization();

        // Make a direct attempt to update progress every minute regardless of player state
        // This ensures we capture progress even if events aren't firing
        setInterval(() => {
            updateProgress(true);
        }, 60000);

        // Alternative progress tracking - try to get time from any available source
        setInterval(() => {
            if (!currentPlayer) {
                debugLog('🔍 Trying alternative progress detection...');

                // Try to find any video element with time data
                const videoElements = document.querySelectorAll('video');
                const iframes = document.querySelectorAll('iframe[src*="youtube"]');

                videoElements.forEach(video => {
                    if (video.currentTime && video.duration) {
                        debugLog('📹 Found HTML5 video with time data:', {
                            currentTime: video.currentTime,
                            duration: video.duration
                        });

                        // Use this video for progress tracking
                        if (!players.includes(video)) {
                            players.push(video);
                            currentPlayer = video;
                            setupProgressTracking(video);
                        }
                    }
                });

                // Check for Plyr instances in global scope
                if (window.Plyr && window.Plyr.instances) {
                    window.Plyr.instances.forEach(instance => {
                        if (instance && !players.includes(instance)) {
                            debugLog('🎭 Found global Plyr instance:', instance);
                            players.push(instance);
                            currentPlayer = instance;
                            setupProgressTracking(instance);
                        }
                    });
                }
            }
        }, 15000); // Every 15 seconds

        // Fallback: Try to manually detect and track progress every 10 seconds
        setInterval(() => {
            if (!currentPlayer || players.length === 0) {
                debugLog('🔄 Attempting manual player detection...');

                // Try direct Plyr initialization again
                initializePlyrDirectly();

                // Try to find any Plyr instances manually
                const plyrElements = document.querySelectorAll('.plyr--youtube, .video-player');
                plyrElements.forEach(element => {
                    if (element.plyr && !players.includes(element.plyr)) {
                        debugLog('🎯 Found new Plyr instance manually:', element.plyr);
                        players.push(element.plyr);
                        currentPlayer = element.plyr;
                        setupProgressTracking(element.plyr);
                    }
                });

                // Try to find YouTube iframes
                const youtubeIframes = document.querySelectorAll('iframe[src*="youtube"]');
                youtubeIframes.forEach(iframe => {
                    if (iframe.id && window.YT && window.YT.get) {
                        const player = window.YT.get(iframe.id);
                        if (player && !players.includes(player)) {
                            debugLog('🎯 Found new YouTube player manually:', player);
                            players.push(player);
                            currentPlayer = player;
                            setupProgressTracking(player);
                        }
                    }
                });
            } else {
                debugLog('✅ Current player status:', {
                    currentPlayer: !!currentPlayer,
                    playersCount: players.length,
                    playerType: currentPlayer ? currentPlayer.constructor.name : 'none'
                });
            }
        }, 10000); // Every 10 seconds
    }, 2000); // Wait 2 seconds for page to load
});

// ============================================================================
// 🎯 SIMPLE PROGRESS TRACKING (FALLBACK METHOD)
// ============================================================================

// Simple progress tracking that works with any video element
function startSimpleProgressTracking() {
    console.log('🎯 Starting simple progress tracking...');

    setInterval(() => {
        // Try to find any video element and track its progress
        const videoElements = document.querySelectorAll('video');
        const iframes = document.querySelectorAll('iframe[src*="youtube"]');

        let currentTime = 0;
        let duration = 0;
        let foundVideo = false;

        // Check HTML5 video elements
        videoElements.forEach(video => {
            if (video.currentTime && video.duration && video.duration > 0) {
                currentTime = Math.floor(video.currentTime);
                duration = Math.floor(video.duration);
                foundVideo = true;
                console.log('📹 HTML5 Video Progress:', currentTime, '/', duration);
            }
        });

        // Check for Plyr instances
        if (!foundVideo && window.Plyr && window.Plyr.instances) {
            window.Plyr.instances.forEach(instance => {
                if (instance && instance.currentTime && instance.duration && instance.duration > 0) {
                    currentTime = Math.floor(instance.currentTime);
                    duration = Math.floor(instance.duration);
                    foundVideo = true;
                    console.log('🎭 Plyr Progress:', currentTime, '/', duration);
                }
            });
        }

        // Check YouTube API if available
        if (!foundVideo && window.YT) {
            iframes.forEach(iframe => {
                if (iframe.id) {
                    try {
                        const player = window.YT.get(iframe.id);
                        if (player && typeof player.getCurrentTime === 'function') {
                            currentTime = Math.floor(player.getCurrentTime());
                            duration = Math.floor(player.getDuration());
                            if (duration > 0) {
                                foundVideo = true;
                                console.log('📺 YouTube Progress:', currentTime, '/', duration);
                            }
                        }
                    } catch (e) {
                        // Ignore errors
                    }
                }
            });
        }

        // If we found video progress, send it to server
        if (foundVideo && currentTime > 0 && duration > 0) {
            const progressPercent = (currentTime / duration) * 100;

            // Update UI
            const progressBar = document.querySelector('.lecture-progress-bar');
            if (progressBar) {
                progressBar.style.width = progressPercent + '%';
            }

            const progressDisplay = document.querySelector('.lecture-progress-display');
            if (progressDisplay) {
                progressDisplay.textContent = Math.round(progressPercent) + '%';
            }

            // Send to server (throttled)
            if (!window.lastSimpleProgressUpdate || (Date.now() - window.lastSimpleProgressUpdate > 10000)) {
                window.lastSimpleProgressUpdate = Date.now();

                console.log('📤 Sending simple progress to server:', progressPercent.toFixed(1) + '%');

                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch('{{ route("lecture-progress.update") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        lecture_id: lectureId,
                        current_time: currentTime,
                        duration: duration
                    })
                })
                .then(response => {
                    console.log('📡 Simple progress response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('📊 Simple progress response data:', data);
                    if (data.success) {
                        console.log('✅ Simple progress updated successfully');
                    } else {
                        console.error('❌ Simple progress update failed:', data);
                    }
                })
                .catch(error => {
                    console.error('❌ Simple progress error:', error);
                })
                .catch(error => {
                    console.log('❌ Simple progress update failed:', error);
                });
            }
        } else {
            console.log('🔍 No video progress found in simple tracking');
        }

    }, 5000); // Check every 5 seconds
}

// Start simple progress tracking after a delay
setTimeout(() => {
    startSimpleProgressTracking();
}, 5000);

// ============================================================================
// 🧪 MANUAL PROGRESS TESTING INTERFACE
// ============================================================================

// Add manual testing interface for debugging
setTimeout(() => {
    // Always show manual tester for debugging (remove this condition later)
    if (true || debugMode) {
        console.log('🧪 Adding manual progress testing interface...');

        const testInterface = document.createElement('div');
        testInterface.innerHTML = `
            <div id="manual-progress-tester" style="
                position: fixed;
                top: 10px;
                left: 10px;
                background: rgba(0,0,0,0.9);
                color: white;
                padding: 15px;
                border-radius: 8px;
                z-index: 9999;
                font-family: monospace;
                font-size: 12px;
                min-width: 300px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.5);
            ">
                <div style="font-weight: bold; margin-bottom: 10px; color: #4CAF50;">📊 Manual Progress Tester</div>
                <div>Lecture ID: ${lectureId}</div>
                <div>Current Time: <span id="manual-time">0</span>s</div>
                <div>Duration: <span id="manual-duration">300</span>s</div>
                <div>Progress: <span id="manual-progress">0</span>%</div>
                <div style="margin: 10px 0;">
                    <button onclick="window.manualProgressTest(30)" style="margin: 2px; padding: 5px 8px; background: #007bff; color: white; border: none; border-radius: 3px; cursor: pointer;">+30s</button>
                    <button onclick="window.manualProgressTest(60)" style="margin: 2px; padding: 5px 8px; background: #007bff; color: white; border: none; border-radius: 3px; cursor: pointer;">+60s</button>
                    <button onclick="window.manualProgressTest(120)" style="margin: 2px; padding: 5px 8px; background: #007bff; color: white; border: none; border-radius: 3px; cursor: pointer;">+2min</button>
                </div>
                <div style="margin: 10px 0;">
                    <button onclick="window.manualProgressSave()" style="margin: 2px; padding: 5px 8px; background: #28a745; color: white; border: none; border-radius: 3px; cursor: pointer;">💾 Save Progress</button>
                    <button onclick="window.manualProgressReset()" style="margin: 2px; padding: 5px 8px; background: #dc3545; color: white; border: none; border-radius: 3px; cursor: pointer;">🔄 Reset</button>
                </div>
                <div style="margin: 10px 0;">
                    <button onclick="document.getElementById('manual-progress-tester').style.display='none'" style="margin: 2px; padding: 5px 8px; background: #6c757d; color: white; border: none; border-radius: 3px; cursor: pointer;">❌ Hide</button>
                </div>
            </div>
        `;
        document.body.appendChild(testInterface);

        // Global variables for manual testing
        window.manualCurrentTime = 0;
        window.manualDuration = 300;

        // Manual progress functions
        window.manualProgressTest = function(seconds) {
            window.manualCurrentTime = Math.min(window.manualCurrentTime + seconds, window.manualDuration);
            updateManualDisplay();
            console.log(`🧪 Manual progress: ${window.manualCurrentTime}s / ${window.manualDuration}s`);
        };

        window.manualProgressReset = function() {
            window.manualCurrentTime = 0;
            updateManualDisplay();
            console.log('🔄 Manual progress reset');
        };

        window.manualProgressSave = function() {
            console.log('💾 Saving manual progress...');

            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const requestData = {
                lecture_id: lectureId,
                current_time: window.manualCurrentTime,
                duration: window.manualDuration
            };

            console.log('📤 Manual sending data:', requestData);

            fetch('{{ route("lecture-progress.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(requestData)
            })
            .then(response => {
                console.log('📡 Manual response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('📊 Manual response data:', data);
                if (data.success) {
                    console.log('✅ Manual progress saved successfully!');

                    // Show success message
                    const successMsg = document.createElement('div');
                    successMsg.style.cssText = 'position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: #28a745; color: white; padding: 20px; border-radius: 8px; z-index: 10000; font-weight: bold;';
                    successMsg.textContent = '✅ Progress Saved Successfully!';
                    document.body.appendChild(successMsg);
                    setTimeout(() => successMsg.remove(), 2000);
                } else {
                    console.error('❌ Manual progress save failed:', data);
                }
            })
            .catch(error => {
                console.error('❌ Manual progress error:', error);
            });
        };

        function updateManualDisplay() {
            const progressPercent = (window.manualCurrentTime / window.manualDuration) * 100;
            document.getElementById('manual-time').textContent = window.manualCurrentTime;
            document.getElementById('manual-progress').textContent = progressPercent.toFixed(1);
        }

        updateManualDisplay();
    }
}, 3000);

// ============================================================================
// 🛡️ ENHANCED PAGE SECURITY WITH RIGHT-CLICK PROTECTION
// ============================================================================

// Comprehensive right-click blocking for entire page
document.addEventListener('contextmenu', function(e) {
    e.preventDefault();
    e.stopPropagation();
    console.log('🚫 Right-click completely disabled on entire page');

    // Show security warning
    showSecurityWarning('Right-click is disabled for content protection');
    return false;
}, true);

// Enhanced keyboard shortcut blocking
document.addEventListener('keydown', function(e) {
    // Block all developer tools and inspection shortcuts
    const blockedKeys = [
        { key: 123, desc: 'F12 (Developer Tools)' },                    // F12
        { ctrl: true, shift: true, key: 73, desc: 'Ctrl+Shift+I' },     // Inspect Element
        { ctrl: true, shift: true, key: 74, desc: 'Ctrl+Shift+J' },     // Console
        { ctrl: true, shift: true, key: 67, desc: 'Ctrl+Shift+C' },     // Element Selector
        { ctrl: true, key: 85, desc: 'Ctrl+U (View Source)' },          // View Source
        { ctrl: true, key: 83, desc: 'Ctrl+S (Save Page)' },            // Save Page
        { ctrl: true, shift: true, key: 83, desc: 'Ctrl+Shift+S' },     // Save As
        { key: 116, desc: 'F5 (Refresh)' },                             // F5 Refresh
        { ctrl: true, key: 116, desc: 'Ctrl+F5' },                      // Hard Refresh
        { ctrl: true, shift: true, key: 116, desc: 'Ctrl+Shift+F5' },   // Hard Refresh
        { ctrl: true, key: 82, desc: 'Ctrl+R (Refresh)' },              // Ctrl+R
        { ctrl: true, shift: true, key: 82, desc: 'Ctrl+Shift+R' },     // Hard Refresh
    ];

    for (let blocked of blockedKeys) {
        if (e.keyCode === blocked.key &&
            (!blocked.ctrl || e.ctrlKey) &&
            (!blocked.shift || e.shiftKey) &&
            (!blocked.alt || e.altKey)) {

            e.preventDefault();
            e.stopPropagation();
            console.log(`🚫 Blocked: ${blocked.desc}`);
            showSecurityWarning(`${blocked.desc} is disabled for content protection`);
            return false;
        }
    }
});

// Block mouse combinations that could access context menus
document.addEventListener('mousedown', function(e) {
    // Block right mouse button (button 2)
    if (e.button === 2) {
        e.preventDefault();
        e.stopPropagation();
        console.log('🚫 Right mouse button blocked');
        return false;
    }

    // Block middle mouse button (button 1) - sometimes used for developer tools
    if (e.button === 1) {
        e.preventDefault();
        e.stopPropagation();
        console.log('🚫 Middle mouse button blocked');
        return false;
    }
});

// Prevent text selection on entire page
document.addEventListener('DOMContentLoaded', function() {
    document.body.style.userSelect = 'none';
    document.body.style.webkitUserSelect = 'none';
    document.body.style.mozUserSelect = 'none';
    document.body.style.msUserSelect = 'none';
    document.body.style.webkitTouchCallout = 'none';
    document.body.style.webkitUserDrag = 'none';
    document.body.style.webkitTapHighlightColor = 'transparent';
});

// Security warning display function
function showSecurityWarning(message) {
    // Remove existing warning if any
    const existingWarning = document.getElementById('security-warning');
    if (existingWarning) {
        existingWarning.remove();
    }

    // Create warning popup
    const warning = document.createElement('div');
    warning.id = 'security-warning';
    warning.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: linear-gradient(135deg, #ff4444, #cc0000);
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(255, 68, 68, 0.3);
        z-index: 10000;
        font-family: Arial, sans-serif;
        font-size: 14px;
        font-weight: bold;
        max-width: 300px;
        animation: slideIn 0.3s ease-out;
    `;

    warning.innerHTML = `
        <div style="display: flex; align-items: center; gap: 10px;">
            <span style="font-size: 18px;">🛡️</span>
            <div>
                <div style="font-size: 16px; margin-bottom: 5px;">Security Protection</div>
                <div style="font-size: 12px; opacity: 0.9;">${message}</div>
            </div>
        </div>
    `;

    document.body.appendChild(warning);

    // Auto remove after 3 seconds
    setTimeout(() => {
        if (warning && warning.parentNode) {
            warning.style.animation = 'slideOut 0.3s ease-in';
            setTimeout(() => {
                if (warning && warning.parentNode) {
                    warning.remove();
                }
            }, 300);
        }
    }, 3000);
}

// Add CSS animations for security warning
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// ============================================================================
// 🚫 ADVANCED SCREEN RECORDING PROTECTION
// ============================================================================

// Screen recording detection and prevention
let isRecordingDetected = false;
let recordingWarningShown = false;

// Method 1: Detect screen recording via media devices
async function detectScreenRecording() {
    try {
        // Check if screen capture is available
        if (navigator.mediaDevices && navigator.mediaDevices.getDisplayMedia) {
            // Monitor for screen capture attempts
            const originalGetDisplayMedia = navigator.mediaDevices.getDisplayMedia;

            navigator.mediaDevices.getDisplayMedia = function(...args) {
                console.log('🚫 Screen recording attempt detected!');
                showRecordingWarning();

                // Block the screen recording
                return Promise.reject(new Error('Screen recording is not allowed on this page'));
            };
        }
    } catch (error) {
        console.log('Screen recording detection setup failed:', error);
    }
}

// Method 2: Detect screen recording via visibility changes (improved - excludes Alt+Tab)
let visibilityChangeCount = 0;
let lastVisibilityChange = 0;

document.addEventListener('visibilitychange', function() {
    // Skip detection if video ended recently
    if (videoEndedRecently) {
        console.log('🎬 Video ended recently, skipping visibility change detection');
        return;
    }

    const now = Date.now();

    if (document.hidden) {
        // Don't count visibility changes during Alt+Tab
        if (!isAltTabbing) {
            lastVisibilityChange = now;
            visibilityChangeCount++;

            // Only trigger if there are multiple rapid visibility changes (suspicious)
            // AND it's not during Alt+Tab period AND video didn't end recently
            if (visibilityChangeCount > 7 && !recordingWarningShown && !isAltTabbing && !videoEndedRecently) { // Increased threshold from 5 to 7
                setTimeout(() => {
                    if (!document.hidden && !recordingWarningShown && !isAltTabbing && !videoEndedRecently) {
                        console.log('🚨 Multiple visibility changes detected - possible recording');
                        showRecordingWarning();
                        recordingWarningShown = true;
                    }
                }, 5000); // Increased delay from 3 seconds to 5 seconds
            }
        }
    } else {
        // Reset counter after some time of normal usage
        setTimeout(() => {
            visibilityChangeCount = Math.max(0, visibilityChangeCount - 1);
        }, 20000); // Increased reset time from 15 seconds to 20 seconds
    }
});

// Method 3: Improved focus change detection (excludes Alt+Tab completely)
let focusLostTime = 0;
let focusChangeCount = 0;
let isAltTabbing = false;
let altTabStartTime = 0;

// Detect Alt+Tab and other Alt combinations
document.addEventListener('keydown', function(e) {
    if (e.altKey && e.keyCode === 9) { // Alt+Tab
        isAltTabbing = true;
        altTabStartTime = Date.now();
        console.log('🔄 Alt+Tab detected - disabling recording detection');

        // Extended timeout for Alt+Tab session
        setTimeout(() => {
            isAltTabbing = false;
            console.log('🔄 Alt+Tab session ended');
        }, 5000); // Longer timeout for Alt+Tab
    }

    // Also detect other Alt combinations that might cause window switching
    if (e.altKey && (e.keyCode === 27 || e.keyCode === 18)) { // Alt+Esc, Alt key alone
        isAltTabbing = true;
        setTimeout(() => {
            isAltTabbing = false;
        }, 3000);
    }
});

// Also detect when Alt key is released to end Alt+Tab session sooner
document.addEventListener('keyup', function(e) {
    if (e.keyCode === 18 && isAltTabbing) { // Alt key released
        setTimeout(() => {
            isAltTabbing = false;
            console.log('🔄 Alt key released - ending Alt+Tab session');
        }, 1000); // Short delay after Alt release
    }
});

window.addEventListener('blur', function() {
    // Skip detection if video ended recently
    if (videoEndedRecently) {
        console.log('🎬 Video ended recently, skipping blur detection');
        return;
    }

    if (!isAltTabbing) {
        focusLostTime = Date.now();
        focusChangeCount++;
        console.log('🔍 Window blur detected (not Alt+Tab)');
    } else {
        console.log('🔄 Window blur during Alt+Tab - ignoring');
    }
});

window.addEventListener('focus', function() {
    // Skip detection if video ended recently
    if (videoEndedRecently) {
        console.log('🎬 Video ended recently, skipping focus detection');
        return;
    }

    if (focusLostTime > 0 && !isAltTabbing) {
        const timeLost = Date.now() - focusLostTime;

        // Only trigger if:
        // 1. Focus was lost for more than 30 seconds (increased from 15 seconds)
        // 2. Multiple focus changes occurred (more than 5, increased from 3)
        // 3. Not during any Alt+Tab session
        // 4. Video didn't end recently
        if (timeLost > 30000 && focusChangeCount > 5 && !recordingWarningShown && !videoEndedRecently) {
            console.log('🚨 Suspicious focus pattern detected - possible recording');
            showRecordingWarning();
            recordingWarningShown = true;
        } else {
            console.log(`🔍 Focus regained after ${timeLost}ms - normal behavior`);
        }

        // Reset counter periodically
        setTimeout(() => {
            focusChangeCount = Math.max(0, focusChangeCount - 1);
        }, 20000); // Even longer reset time
    } else if (isAltTabbing) {
        console.log('🔄 Focus regained during Alt+Tab - normal window switching');
    }

    focusLostTime = 0;
});

// Method 4: Prevent specific recording shortcuts (improved)
document.addEventListener('keydown', function(e) {
    // Block Windows Game Bar (Win + G) - but allow other Win combinations
    if ((e.metaKey || e.key === 'Meta') && e.keyCode === 71) {
        e.preventDefault();
        showRecordingWarning();
        console.log('🚫 Windows Game Bar shortcut blocked');
        return false;
    }

    // Block specific recording shortcuts (but not Alt+Tab)
    if (e.altKey && e.keyCode === 82 && !e.ctrlKey && !e.shiftKey) { // Alt+R only
        e.preventDefault();
        showRecordingWarning();
        console.log('🚫 Alt+R recording shortcut blocked');
        return false;
    }

    // Block Print Screen
    if (e.keyCode === 44) { // Print Screen
        e.preventDefault();
        showSecurityWarning('Screenshots are disabled for content protection');
        console.log('🚫 Print Screen blocked');
        return false;
    }

    // Block Ctrl+Shift+R (some recording software)
    if (e.ctrlKey && e.shiftKey && e.keyCode === 82) {
        e.preventDefault();
        showRecordingWarning();
        console.log('🚫 Ctrl+Shift+R recording shortcut blocked');
        return false;
    }
});

// Screen recording warning function
function showRecordingWarning() {
    // Remove existing warning if any
    const existingWarning = document.getElementById('recording-warning');
    if (existingWarning) {
        existingWarning.remove();
    }

    // Create recording warning popup
    const warning = document.createElement('div');
    warning.id = 'recording-warning';
    warning.style.cssText = `
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: linear-gradient(135deg, #ff0000, #cc0000);
        color: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(255, 0, 0, 0.5);
        z-index: 20000;
        font-family: Arial, sans-serif;
        text-align: center;
        max-width: 400px;
        animation: slideIn 0.5s ease-out;
        border: 3px solid #ffffff;
    `;

    warning.innerHTML = `
        <div style="font-size: 48px; margin-bottom: 20px;">⚠️</div>
        <h2 style="margin-bottom: 15px; color: white;">Content Protection Notice</h2>
        <p style="margin-bottom: 20px; font-size: 16px;">
            Our system detected unusual activity that may indicate screen recording.
        </p>
        <p style="margin-bottom: 25px; font-size: 14px; opacity: 0.9;">
            If you're not recording, this might be a false positive. Please refresh to continue watching.
        </p>
        <div style="margin-bottom: 20px;">
            <button onclick="location.reload()" style="
                background: white;
                color: #ff0000;
                border: none;
                padding: 12px 25px;
                border-radius: 8px;
                font-weight: bold;
                cursor: pointer;
                font-size: 16px;
                transition: all 0.3s ease;
                margin-right: 10px;
            " onmouseover="this.style.background='#f0f0f0'" onmouseout="this.style.background='white'">
                🔄 Refresh Page
            </button>
            <button onclick="document.getElementById('recording-warning').remove(); document.querySelector('.ratio-16x9').style.filter='none'; document.querySelector('.ratio-16x9').style.pointerEvents='auto';" style="
                background: transparent;
                color: white;
                border: 2px solid white;
                padding: 10px 20px;
                border-radius: 8px;
                font-weight: bold;
                cursor: pointer;
                font-size: 14px;
                transition: all 0.3s ease;
            " onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'">
                Continue Anyway
            </button>
        </div>
        <p style="font-size: 12px; opacity: 0.7; margin: 0;">
            Note: Recording copyrighted content may violate terms of service
        </p>
    `;

    document.body.appendChild(warning);

    // Also blur the video content
    const videoContainer = document.querySelector('.ratio-16x9');
    if (videoContainer) {
        videoContainer.style.filter = 'blur(20px)';
        videoContainer.style.pointerEvents = 'none';
    }

    // Auto-dismiss warning after 30 seconds if user doesn't interact
    setTimeout(() => {
        const warningElement = document.getElementById('recording-warning');
        if (warningElement) {
            console.log('⏰ Auto-dismissing recording warning after 30 seconds');
            warningElement.remove();
            if (videoContainer) {
                videoContainer.style.filter = 'none';
                videoContainer.style.pointerEvents = 'auto';
            }
            // Reset the warning flag so it can trigger again if needed
            recordingWarningShown = false;
        }
    }, 30000); // 30 seconds
}

// Method 5: Advanced recording detection via user behavior patterns (improved)
let suspiciousActivityScore = 0;
let lastActivityTime = Date.now();
let videoEndedRecently = false;

function monitorSuspiciousActivity() {
    // Monitor for patterns that indicate recording software
    setInterval(() => {
        const now = Date.now();
        const timeSinceLastActivity = now - lastActivityTime;

        // Don't trigger if video ended recently (user might be inactive after video ends)
        if (videoEndedRecently) {
            console.log('🎬 Video ended recently, skipping suspicious activity detection');
            return;
        }

        // If user is inactive for long periods but page is still focused (suspicious)
        // But only if it's been a while since video ended
        if (!document.hidden && timeSinceLastActivity > 60000) { // Increased from 30 seconds to 60 seconds
            suspiciousActivityScore += 1;
        }

        // Reset score if user is actively using the page
        if (timeSinceLastActivity < 10000) { // Increased from 5 seconds to 10 seconds
            suspiciousActivityScore = Math.max(0, suspiciousActivityScore - 1);
        }

        // Trigger warning only if score is very high and no recent video activity
        if (suspiciousActivityScore > 8 && !recordingWarningShown && !videoEndedRecently) { // Increased threshold from 5 to 8
            console.log('🚨 High suspicious activity score detected:', suspiciousActivityScore);
            showRecordingWarning();
            recordingWarningShown = true;
        }

        lastActivityTime = now;
    }, 15000); // Check every 15 seconds (increased from 10 seconds)
}

// Track user activity to reset suspicious activity
document.addEventListener('mousemove', () => {
    lastActivityTime = Date.now();
});

document.addEventListener('keydown', () => {
    lastActivityTime = Date.now();
});

document.addEventListener('click', () => {
    lastActivityTime = Date.now();
});

// Method 6: Detect recording software by checking for specific processes (limited browser capability)
function detectRecordingProcesses() {
    // This is a placeholder for more advanced detection
    // In a real implementation, you might use browser APIs to detect
    // performance changes that indicate recording software

    setInterval(() => {
        // Check for performance degradation that might indicate recording
        if (performance.memory) {
            const memoryUsage = performance.memory.usedJSHeapSize / performance.memory.totalJSHeapSize;

            // If memory usage is consistently high, might indicate recording
            if (memoryUsage > 0.9 && !recordingWarningShown) {
                suspiciousActivityScore += 2;
            }
        }
    }, 15000);
}

// Initialize all protection methods
document.addEventListener('DOMContentLoaded', function() {
    detectScreenRecording();
    monitorSuspiciousActivity();
    detectRecordingProcesses();

    console.log('🛡️ Advanced screen recording protection activated!');
    console.log('ℹ️ Alt+Tab and normal window switching will not trigger warnings');
});

console.log('🔒 Comprehensive security system with screen recording protection loaded and active!');
</script>


    </div>
</div>
@endsection
