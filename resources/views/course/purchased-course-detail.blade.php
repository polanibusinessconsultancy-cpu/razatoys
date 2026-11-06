@extends('polani.layout')

@section('title', $course->name . ' — One Stop Mart')

@push('styles')
    <link href="{{ asset('css/course-detail.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('one-stop-mart/css/customer-account.css') }}">
    <meta name="user-id" content="{{ auth()->id() }}">
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
                                                @foreach($lectures as $lecture)
                                                    <a href="{{ route('user.lecture.purchased', ['course' => $course->id, 'lecture' => $lecture->id]) }}"
                                                       class="list-group-item list-group-item-action d-flex align-items-center lecture-link">
                                                        <i class="fas fa-play-circle me-2"></i>
                                                        <div>
                                                            <span>{{ $lecture->name }}</span>
                                                            <small class="d-block text-muted">{{ $lecture->duration ?? 'N/A' }}</small>
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
                        <li class="breadcrumb-item active">{{ $course->name }}</li>
                    </ol>
                </nav>

                <!-- Lecture Player Section -->
             
                <!-- Course Overview -->
                <div id="course-overview-section" class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-4 mb-md-0">
                                <img src="{{ $course->image_path ? Storage::url($course->image_path) : 'https://via.placeholder.com/300x200' }}"
                                    alt="{{ $course->name }}"
                                    class="img-fluid rounded mb-3">

                                <div class="d-grid gap-2">
                                    @if($lectures->count() > 0)
                                        <a href="{{ route('user.lecture.purchased', ['course' => $course->id, 'lecture' => $lectures->first()->id]) }}"
                                           class="btn btn-primary start-course">
                                            <i class="fas fa-play-circle me-2"></i> Start Learning
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-8">
                                <h1 class="h3 mb-3">{{ $course->name }}</h1>

                                <div class="mb-4">
                                    <div class="d-flex flex-wrap align-items-center mb-2">
                                        <span class="me-3 mb-2">
                                            <i class="fas fa-book me-1"></i> {{ $lectures->count() }} lectures
                                        </span>
                                        <span class="me-3 mb-2">
                                            <i class="fas fa-clock me-1"></i>
                                            @php
                                                $totalDuration = 0;
                                                foreach($lectures as $lec) {
                                                    if($lec->duration) {
                                                        // Parse duration like "01:51" or "1:51:30"
                                                        $parts = explode(':', $lec->duration);
                                                        if(count($parts) == 2) {
                                                            $totalDuration += (int)$parts[0] * 60 + (int)$parts[1]; // MM:SS
                                                        } elseif(count($parts) == 3) {
                                                            $totalDuration += (int)$parts[0] * 3600 + (int)$parts[1] * 60 + (int)$parts[2]; // HH:MM:SS
                                                        }
                                                    }
                                                }
                                                $hours = floor($totalDuration / 3600);
                                                $minutes = floor(($totalDuration % 3600) / 60);
                                                $totalFormatted = $hours > 0 ? "{$hours}h {$minutes}m" : "{$minutes}m";
                                            @endphp
                                            {{ $totalFormatted }} total
                                        </span>
                                    </div>
                                </div>

                                <div class="course-description mb-4">
                                    <h5 class="mb-3">About This Course</h5>
                                    <div class="p-3 bg-light rounded">
                                        {{ $course->description }}
                                    </div>
                                </div>

                                <!-- Course Features -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="mb-3">What You'll Learn</h5>
                                        <ul class="list-group list-group-flush mb-4">
                                            @foreach($course->getFeaturesByType('learn')->take(5) as $feature)
                                                <li class="list-group-item bg-transparent px-0">
                                                    <i class="fas fa-check-circle text-success me-2"></i> {{ $feature->feature_text }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>

                                    <div class="col-md-6">
                                        <h5 class="mb-3">Requirements</h5>
                                        <ul class="list-group list-group-flush mb-4">
                                            @foreach($course->getFeaturesByType('requirement')->take(3) as $feature)
                                                <li class="list-group-item bg-transparent px-0">
                                                    <i class="fas fa-circle text-primary me-2"></i> {{ $feature->feature_text }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Course Content Overview -->
                <div id="course-content-section" class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Course Content</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Lecture</th>
                                            <th scope="col">Duration</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($lectures as $index => $lecture)
                                            <tr>
                                                <th scope="row">{{ $index + 1 }}</th>
                                                <td>{{ $lecture->name }}</td>
                                                <td>{{ $lecture->duration ?? 'N/A' }}</td>
                                                <td>
                                                    <a href="{{ route('user.lecture.purchased', ['course' => $course->id, 'lecture' => $lecture->id]) }}"
                                                       class="btn btn-sm btn-primary">
                                                        <i class="fas fa-play-circle me-1"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rating Section -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Rate This Course</h5>
                    </div>
                    <div class="card-body">
                        <div id="user-rating-section">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="course-rating-stats mb-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <h3 class="mb-0 me-2" id="average-rating">{{ number_format($course->average_rating, 1) }}</h3>
                                            <div class="star-rating" id="average-star-display" data-rating="{{ $course->average_rating }}">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $course->average_rating)
                                                        <i class="fas fa-star text-warning"></i>
                                                    @elseif ($i - 0.5 <= $course->average_rating)
                                                        <i class="fas fa-star-half-alt text-warning"></i>
                                                    @else
                                                        <i class="far fa-star text-warning"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <small class="text-muted ms-2">({{ $course->rating_count }} ratings)</small>
                                        </div>
                                    </div>

                                    <!-- Show existing ratings -->
                                    <div id="ratings-container">
                                        <!-- Ratings will be loaded here via Ajax -->
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="rate-course-form">
                                        <h6 class="mb-3">Share your experience with this course</h6>

                                        <div class="rating-input mb-3">
                                            <label class="form-label">Your Rating <span class="text-danger">*</span></label>
                                            <div class="star-rating-input">
                                                <i class="far fa-star fs-4 rating-star" data-value="1" style="cursor: pointer;"></i>
                                                <i class="far fa-star fs-4 rating-star" data-value="2" style="cursor: pointer;"></i>
                                                <i class="far fa-star fs-4 rating-star" data-value="3" style="cursor: pointer;"></i>
                                                <i class="far fa-star fs-4 rating-star" data-value="4" style="cursor: pointer;"></i>
                                                <i class="far fa-star fs-4 rating-star" data-value="5" style="cursor: pointer;"></i>
                                                <input type="hidden" id="rating-value" value="0">
                                            </div>
                                            <small class="form-text text-muted">
                                                Selected: <span id="rating-display">None</span>
                                            </small>
                                            <div id="rating-error" class="text-danger small mt-2" style="display: none;"></div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="rating-comment" class="form-label">Your Comment (Optional)</label>
                                            <textarea id="rating-comment" class="form-control" rows="3" placeholder="What did you like or dislike about this course?"></textarea>
                                        </div>

                                        <div class="d-grid">
                                            <button type="button" id="submit-rating" class="btn btn-primary">
                                                <i class="fas fa-star me-2"></i> Submit Rating
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Q&A Section -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Ask a Question</h5>
                    </div>
                    <div class="card-body">
                        @if(!isset($isAdmin) || !$isAdmin)
                        <!-- Question Limits Display -->
                        <div id="question-limits-display" class="alert alert-info mb-3" style="display: none;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Questions Remaining:</strong>
                                    <span id="lecture-questions-remaining" class="badge bg-primary">-</span> for this lecture
                                    <span id="course-questions-remaining" class="badge bg-success">-</span> for this course
                                </div>
                            </div>
                        </div>

                        <form id="question-form" class="mb-4">
                                <div class="mb-3">
                                <label for="question-content" class="form-label">Ask a question about this course <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="question-content" rows="3" placeholder="What would you like to know? (minimum 5 characters)" minlength="5" required></textarea>
                                <small class="form-text text-muted">
                                    <span id="char-count">0</span>/5 minimum characters
                                </small>
                                <div id="question-error" class="text-danger small mt-2" style="display: none;"></div>
                            </div>

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

                                <div class="d-flex justify-content-end">
                                <button type="button" id="submit-question" class="btn btn-primary">
                                        <i class="fas fa-paper-plane me-2"></i> Submit Question
                                    </button>
                                </div>
                        </form>
                        @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> As an admin, you can view and respond to student questions, but cannot submit questions yourself.
                        </div>
                        @endif

                        <div id="questions-container" class="chat-container">
                            <div class="text-center py-4 text-muted" id="no-questions-message">
                                <i class="fas fa-comments fa-2x mb-3"></i>
                                <p>No questions yet about this course. Be the first to ask!</p>
                            </div>
                            <!-- Questions will be dynamically loaded here -->
                        </div>
                            </div>
                        </div>

                <!-- Admin Questions Section - Only visible to admins -->
                @if(isset($isAdmin) && $isAdmin && isset($adminQuestions) && $adminQuestions->count() > 0)
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">{{ Auth::user()->isAdmin() ? 'All Student Questions for this Course' : 'My Assigned Students Questions for this Course' }}</h5>
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
                                        @if($question->lecture)
                                        <a href="{{ route('user.lecture.purchased', ['course' => $course->id, 'lecture' => $question->lecture_id]) }}" class="btn btn-sm btn-info ms-2">
                                            <i class="fas fa-video me-1"></i> View Lecture
                                        </a>
                                        @endif
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
                                                                <img src="{{ Storage::url($attachment->file_path) }}" alt="Attachment" class="img-thumbnail img-thumbnail-fixed">
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

                                    <!-- Question metadata -->
                                    @if($question->lecture)
                                    <div class="question-metadata mt-2 mb-3">
                                        <span class="badge bg-light text-dark">
                                            <i class="fas fa-video me-1"></i> Lecture: {{ $question->lecture->name }}
                                        </span>
            </div>
                                    @endif

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
                                                                <img src="{{ Storage::url($attachment->file_path) }}" class="img-thumbnail img-thumbnail-fixed">
                                                            </a>
                                                        </div>
                                                    @elseif($attachment->file_type == 'pdf')
                                                        <div class="mb-2">
                                                            <a href="{{ Storage::url($attachment->file_path) }}" target="_blank" class="btn btn-sm btn-outline-danger">
                                                                <i class="fas fa-file-pdf me-1"></i> {{ $attachment->original_name }}
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
                                        @livewire('admin.answer-form', ['questionId' => $question->id], key('course-answer-form-'.$question->id))
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
        <script src="{{ asset('js/course-detail.min.js') }}"></script>
        <script>
            // Initialize course-specific data
            document.addEventListener('DOMContentLoaded', function() {
                // Set course ID for JavaScript functions
                currentCourseId = {{ $course->id }};

                // Initialize lecture navigation
                const lectureItems = document.querySelectorAll('.lecture-item');
                lectureItems.forEach(function(item) {
                    item.addEventListener('click', function(e) {
                        e.preventDefault();
                        const lectureId = this.dataset.lectureId;
                        if (lectureId) {
                            loadLecture(lectureId);
                        }
                    });
                });

                // Load first lecture if available
                if (lectureItems.length > 0) {
                    const firstLecture = lectureItems[0];
                    const firstLectureId = firstLecture.dataset.lectureId;
                    if (firstLectureId) {
                        loadLecture(firstLectureId);
                    }
                }
            });

            // Lecture loading function
            function loadLecture(lectureId) {
                currentLectureId = lectureId;

                // Fetch lecture content via AJAX
                fetch(`/my-course/${currentCourseId}/lecture-content/${lectureId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update lecture content
                            document.getElementById('lecture-title').innerText = data.lecture.name;
                            document.getElementById('lecture-description').innerHTML = data.description;

                            if (data.videoUrl) {
                                document.getElementById('lecture-video').src = data.videoUrl;
                            } else {
                                document.getElementById('lecture-video').innerHTML = '<div class="d-flex align-items-center justify-content-center bg-light h-100"><p class="text-muted">No video available for this lecture</p></div>';
                            }

                            // Show lecture player section
                            const playerSection = document.getElementById('lecture-player-section');
                            playerSection.classList.remove('lecture-player-hidden');

                            // Update active lecture in sidebar
                            document.querySelectorAll('.lecture-item').forEach(item => {
                                item.classList.remove('active');
                            });
                            document.querySelector(`[data-lecture-id="${lectureId}"]`).classList.add('active');

                            // Update navigation buttons
                            updateNavigationButtons(lectureId);

                            // Re-initialize video protection for new content
                            initializeVideoProtection();
                        }
                    })
                    .catch(error => {
                        console.error('Error loading lecture:', error);
                    });
            }

            // Update navigation buttons
            function updateNavigationButtons(currentLectureId) {
                const lectureItems = Array.from(document.querySelectorAll('.lecture-item'));
                const currentIndex = lectureItems.findIndex(item => item.dataset.lectureId === currentLectureId);

                const prevBtn = document.getElementById('prev-lecture');
                const nextBtn = document.getElementById('next-lecture');

                // Previous button
                if (currentIndex > 0) {
                    prevBtn.classList.remove('nav-button-hidden');
                    prevBtn.removeEventListener('click', prevBtn._clickHandler);
                    prevBtn._clickHandler = () => loadLecture(lectureItems[currentIndex - 1].dataset.lectureId);
                    prevBtn.addEventListener('click', prevBtn._clickHandler);
                } else {
                    prevBtn.classList.add('nav-button-hidden');
                }

                // Next button
                if (currentIndex < lectureItems.length - 1) {
                    nextBtn.classList.remove('nav-button-hidden');
                    nextBtn.removeEventListener('click', nextBtn._clickHandler);
                    nextBtn._clickHandler = () => loadLecture(lectureItems[currentIndex + 1].dataset.lectureId);
                    nextBtn.addEventListener('click', nextBtn._clickHandler);
                } else {
                    nextBtn.classList.add('nav-button-hidden');
                }
            }
        </script>
    @endpush
    </div>
</div>
@endsection
