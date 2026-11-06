@extends('polani.layout')

@section('title', 'Quiz Results — One Stop Mart')

@push('styles')
  <link rel="stylesheet" href="{{ asset('one-stop-mart/css/customer-account.css') }}">
@endpush

@section('content')
  <div class="osm-customer-account-page">
    <div class="osm-account-container">
      
      <!-- Breadcrumbs -->
      <nav class="osm-account-breadcrumb" aria-label="breadcrumb">
        <a href="{{ route('home') }}">Home</a>
        <span>/</span>
        <span>Quiz Results</span>
      </nav>

      <!-- Layout Grid -->
      <div class="osm-account-shell">
        
        <!-- Sidebar Navigation -->
        @include('polani.partials.account-sidebar')

        <!-- Main Account Content -->
        <main class="osm-account-main">
          
          <!-- Summary Header -->
          <div class="osm-account-card">
            <div class="osm-account-header">
              <h1 class="osm-account-title">My Quiz Results</h1>
              <p class="osm-account-subtitle">Track your quiz performance and progress</p>
            </div>

            <!-- Stats Grid -->
            <div class="osm-account-stats-grid">
              <div class="osm-account-stat-card">
                <div>
                  <div class="osm-account-stat-num">{{ $quizStats['total'] }}</div>
                  <div class="osm-account-stat-label">Total Attempts</div>
                </div>
                <div class="osm-account-stat-icon">
                  <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
              </div>
              <div class="osm-account-stat-card">
                <div>
                  <div class="osm-account-stat-num" style="color: #15803d;">{{ $quizStats['passed'] }}</div>
                  <div class="osm-account-stat-label">Passed</div>
                </div>
                <div class="osm-account-stat-icon" style="background-color: #dcfce7; color: #15803d;">
                  <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
              </div>
              <div class="osm-account-stat-card">
                <div>
                  <div class="osm-account-stat-num" style="color: #b91c1c;">{{ $quizStats['failed'] }}</div>
                  <div class="osm-account-stat-label">Failed</div>
                </div>
                <div class="osm-account-stat-icon" style="background-color: #fee2e2; color: #b91c1c;">
                  <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
              </div>
              <div class="osm-account-stat-card">
                <div>
                  <div class="osm-account-stat-num" style="color: #b45309;">{{ $quizStats['pending'] }}</div>
                  <div class="osm-account-stat-label">Pending Review</div>
                </div>
                <div class="osm-account-stat-icon" style="background-color: #fef3c7; color: #d97706;">
                  <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
              </div>
            </div>

            <!-- Quiz Attempts Table -->
            <h2 class="osm-account-section-title">All Quiz Attempts</h2>
            @if($quizAttempts->count() > 0)
              <div class="osm-table-wrapper">
                <table class="osm-table">
                  <thead>
                    <tr>
                      <th>Quiz</th>
                      <th>Course</th>
                      <th>Score</th>
                      <th>Status</th>
                      <th>Date</th>
                      <th style="text-align: right;">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($quizAttempts as $attempt)
                      <tr>
                        <td>
                          <div style="font-weight: 700; color: #111827;">{{ $attempt->quiz->title }}</div>
                          <div style="font-size: 12px; color: #6b7280;">{{ $attempt->quiz->lecture->lecture_title }}</div>
                        </td>
                        <td>
                          @if($attempt->quiz->lecture->course)
                            {{ $attempt->quiz->lecture->course->name }}
                          @else
                            <span class="osm-badge osm-badge--grey">Standalone</span>
                          @endif
                        </td>
                        <td>
                          <strong>{{ $attempt->score ?? 0 }}/{{ $attempt->total_points ?? 0 }}</strong>
                          <span style="font-size: 12px; color: #6b7280;">({{ $attempt->percentage_score ?? 0 }}%)</span>
                        </td>
                        <td>
                          @if($attempt->isPassed())
                            <span class="osm-badge osm-badge--green">Passed</span>
                          @elseif($attempt->isFailed())
                            <span class="osm-badge osm-badge--red">Failed</span>
                          @elseif($attempt->isPendingReview())
                            <span class="osm-badge osm-badge--amber">Pending Review</span>
                          @else
                            <span class="osm-badge osm-badge--grey">In Progress</span>
                          @endif
                        </td>
                        <td>
                          {{ $attempt->completed_at ? $attempt->completed_at->format('M d, Y H:i') : 'In Progress' }}
                        </td>
                        <td style="text-align: right;">
                          @if($attempt->isCompleted())
                            <a href="{{ route('quiz.result', [
                                'course' => $attempt->quiz->lecture->course_id ?? 0,
                                'lecture' => $attempt->quiz->lecture->id,
                                'quiz' => $attempt->quiz->id,
                                'attempt' => $attempt->id
                              ]) }}" class="osm-btn osm-btn--secondary osm-btn--sm">
                              View Result
                            </a>
                            
                            @if($attempt->isPassed())
                              @php
                                $user = Auth::user();
                                $lecture = $attempt->quiz->lecture;
                                $courseId = $lecture->course_id;
                                $query = $user->certificates()->where('lecture_id', $lecture->id);
                                
                                if ($courseId) {
                                    $query->where('course_id', $courseId);
                                } else {
                                    $query->whereNull('course_id');
                                }
                                
                                $hasLectureCertificate = $query->exists();
                              @endphp
                              
                              @if($hasLectureCertificate)
                                <a href="{{ route('user.certificates') }}" class="osm-btn osm-btn--primary osm-btn--sm" style="margin-left: 5px;">
                                  Certificate
                                </a>
                              @endif
                            @endif
                          @else
                            <a href="{{ route('quiz.show', [
                                'course' => $attempt->quiz->lecture->course_id ?? 0,
                                'lecture' => $attempt->quiz->lecture->id,
                                'quiz' => $attempt->quiz->id
                              ]) }}" class="osm-btn osm-btn--primary osm-btn--sm">
                              Continue
                            </a>
                          @endif
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>

              <!-- Pagination -->
              @if($quizAttempts->hasPages())
                <div style="margin-top: 20px; display: flex; justify-content: center;">
                  {{ $quizAttempts->links() }}
                </div>
              @endif
            @else
              <div class="osm-empty-state">
                <span class="osm-empty-icon" aria-hidden="true">📝</span>
                <p>You have not made any quiz attempts yet. Complete your lectures to unlock quizzes.</p>
                <a href="{{ route('user.dashboard') }}" class="osm-btn osm-btn--primary" style="margin-top: 15px;">
                  Go to dashboard
                </a>
              </div>
            @endif
          </div>

        </main>

      </div>

    </div>
  </div>
@endsection
