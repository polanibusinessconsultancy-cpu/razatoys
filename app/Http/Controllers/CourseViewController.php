<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lecture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseViewController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show course overview
     */
    public function showCourse(Course $course)
    {
        // Check if user has access to this course
        if (!$this->userHasAccess($course)) {
            return redirect()->route('user.dashboard')
                ->with('error', 'You do not have access to this course.');
        }

        // Get first lecture as default if available
        $lecture = $course->lectures->first();
        $nextItemData = $lecture ? $this->getNextItem($course, 'lecture', $lecture->id) : ['item' => null, 'type' => null];

        return view('course.lecture-view', [
            'course' => $course,
            'lecture' => $lecture,
            'previousItem' => null,
            'nextItem' => $nextItemData['item'],
            'previousItemType' => null,
            'nextItemType' => $nextItemData['type']
        ]);
    }

    /**
     * Show purchased course detail page with sidebar navigation
     */
    public function showPurchasedCourse(Course $course)
    {
        $user = auth()->user();
        $hasCourseAccess = false;

        // Check if user is an admin in admin context
        $isAdminInAdminContext = $isAdminView = false;
        if ($user->role === 'admin' || $user->roles()->where('name', 'admin')->exists()) {
            $isAdminInAdminContext = true;
        }

        // Check if user has full course access
        $hasFullCourseAccess = \App\Models\UserCourse::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('status', 'active')
            ->whereNull('lecture_id')
            ->exists();

        // Check if user has purchased at least one lecture from this course
        // We need to query the course lectures first, then check if the user has any of them
        $courseLectureIds = $course->lectures()->pluck('id')->toArray();

        $purchasedLectureIds = \App\Models\UserCourse::where('user_id', $user->id)
            ->where('status', 'active')
            ->whereNotNull('lecture_id')
            ->whereIn('lecture_id', $courseLectureIds)
            ->pluck('lecture_id')
            ->toArray();

        // If admin in admin context or user has course access (full or individual lectures), show relevant content
        // Also allow admin/superadmin access even in user context
        $isAdminOrSuperAdmin = $user->isAdmin();
        if ($isAdminInAdminContext || $isAdminOrSuperAdmin || $hasFullCourseAccess || !empty($purchasedLectureIds)) {
            // We already checked for full course access above
            $hasCourseAccess = $hasFullCourseAccess;

            if (($isAdminInAdminContext && $isAdminView) || $isAdminOrSuperAdmin) {
                // Admin in admin view or admin/superadmin in any context can see all lectures
                $lectures = $course->lectures()->orderBy('id')->get();
            } else if ($hasCourseAccess) {
                // User has full course access
                $lectures = $course->lectures()->orderBy('id')->get();
            } else {
                // User only has access to specific lectures
                $lectures = $course->lectures()
                    ->whereIn('id', $purchasedLectureIds)
                    ->orderBy('id')
                    ->get();
            }

            // Get related questions for admins
            $adminQuestions = null;
            if ($isAdminOrSuperAdmin) {
                $adminQuestions = $this->getRelatedQuestionsForAdmin($user, $course->id);
            }

            return view('course.purchased-course-detail', [
                'course' => $course,
                'lectures' => $lectures,
                'adminQuestions' => $adminQuestions,
                'isAdmin' => $isAdminOrSuperAdmin
            ]);
        } else {
            // No access to any lecture in this course
            return redirect()->route('user.dashboard')
                ->with('error', 'You do not have access to this course or any of its lectures.');
        }
    }

    /**
     * Show specific lecture
     */
    public function showLecture(Course $course, Lecture $lecture)
    {
        // Check if user has access to this lecture
        if (!$this->userHasAccessToLecture($course, $lecture)) {
            return redirect()->route('user.dashboard')
                ->with('error', 'You do not have access to this lecture.');
        }

        $previousItem = $this->getPreviousItem($course, 'lecture', $lecture->id);
        $nextItem = $this->getNextItem($course, 'lecture', $lecture->id);

        return view('course.lecture-view', [
            'course' => $course,
            'lecture' => $lecture,
            'previousItem' => $previousItem['item'],
            'nextItem' => $nextItem['item'],
            'previousItemType' => $previousItem['type'],
            'nextItemType' => $nextItem['type']
        ]);
    }

    /**
     * Get lecture content via AJAX
     */
    public function getLectureContent(Request $request, Course $course, Lecture $lecture)
    {
        $user = auth()->user();

        // First check if lecture actually belongs to the course
        if ($lecture->course_id != $course->id) {
            return response()->json([
                'success' => false,
                'message' => 'Lecture not found in this course.'
            ], 404);
        }

        // Check if user is admin or superadmin
        $isAdmin = $user->isAdmin();

        // Direct check if user has access to this specific lecture (without requiring course_id)
        $hasSpecificLectureAccess = \App\Models\UserCourse::where('user_id', $user->id)
            ->where('lecture_id', $lecture->id)
            ->where('status', 'active')
            ->exists();

        // Check if user has full course access
        $hasFullCourseAccess = \App\Models\UserCourse::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->whereNull('lecture_id')
            ->where('status', 'active')
            ->exists();

        // If no access at all, return error (but allow admin/superadmin full access)
        if (!$hasSpecificLectureAccess && !$hasFullCourseAccess && !$isAdmin) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have access to this lecture.'
            ], 403);
        }

        // Process YouTube URL if present
        $youtubeData = null;
        if ($lecture->youtube_url) {
            // Extract YouTube ID
            $youtubeId = null;
            $patterns = [
                '/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', // youtube.com/watch?v=ID
                '/youtu\.be\/([a-zA-Z0-9_-]+)/'             // youtu.be/ID
            ];

            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $lecture->youtube_url, $matches)) {
                    $youtubeId = $matches[1];
                    break;
                }
            }

            // Base64 encode the YouTube ID
            $encryptedYoutubeId = $youtubeId ? base64_encode($youtubeId) : null;

            $youtubeData = [
                'provider' => 'youtube',
                'encrypted_id' => $encryptedYoutubeId
            ];
        }

        return response()->json([
            'success' => true,
            'lecture' => $lecture,
            'youtubeData' => $youtubeData, // Send YouTube data for Plyr.js
            'videoPath' => $lecture->video_path ? Storage::url($lecture->video_path) : null,
            'description' => $lecture->description
        ]);
    }

    /**
     * Show standalone lecture detail page (without course)
     */
    public function showStandaloneLecture(Lecture $lecture, Request $request)
    {
        $user = auth()->user();

        // Check if user has access to this standalone lecture
        $hasLectureAccess = \App\Models\UserCourse::where('user_id', $user->id)
            ->where('lecture_id', $lecture->id)
            ->where('status', 'active')
            ->exists();

        // Check if user is admin or superadmin
        $isAdmin = $user->isAdmin();

        // If no access at all, redirect (but allow admin/superadmin full access)
        if (!$hasLectureAccess && !$isAdmin) {
            return redirect()->route('user.dashboard')
                ->with('error', 'You do not have access to this lecture.');
        }

        // Check if we're viewing the PDF version of the lecture
        $isViewingPdf = $request->query('view') === 'pdf';

        // Encrypt YouTube video ID with Base64 if present
        if ($lecture->youtube_url) {
            // Extract YouTube ID from URL
            $youtubeId = null;
            $patterns = [
                '/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', // youtube.com/watch?v=ID
                '/youtu\.be\/([a-zA-Z0-9_-]+)/'             // youtu.be/ID
            ];

            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $lecture->youtube_url, $matches)) {
                    $youtubeId = $matches[1];
                    break;
                }
            }

            // Base64 encode the YouTube ID
            $encryptedYoutubeId = $youtubeId ? base64_encode($youtubeId) : null;

            // Store the encrypted ID
            $lecture->encrypted_youtube_id = $encryptedYoutubeId;
        }

        return view('course.purchased-lecture-detail', [
            'course' => null,
            'lecture' => $lecture,
            'lectures' => collect([$lecture]),
            'isViewingPdf' => $isViewingPdf,
            'previousItem' => null,
            'nextItem' => null,
            'adminQuestions' => null,
            'isAdmin' => $isAdmin
        ]);
    }

    /**
     * Show purchased lecture detail page
     */
    public function showPurchasedLecture(Course $course, Lecture $lecture, Request $request)
    {
        $user = auth()->user();

        // Direct check if user has access to this specific lecture (without requiring course_id)
        $hasSpecificLectureAccess = \App\Models\UserCourse::where('user_id', $user->id)
            ->where('lecture_id', $lecture->id)
            ->where('status', 'active')
            ->exists();

        // Check if user has full course access
        $hasFullCourseAccess = \App\Models\UserCourse::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->whereNull('lecture_id')
            ->where('status', 'active')
            ->exists();

        // Check if user is admin or superadmin
        $isAdmin = $user->isAdmin();

        // If no access at all, redirect (but allow admin/superadmin full access)
        if (!$hasSpecificLectureAccess && !$hasFullCourseAccess && !$isAdmin) {
            return redirect()->route('user.dashboard')
                ->with('error', 'You do not have access to this lecture.');
        }

        // Show lectures based on user access level
        if ($hasFullCourseAccess || $isAdmin) {
            // If user has full course access OR is admin/superadmin, show all lectures
            $lectures = $course->lectures()->orderBy('id')->get();
        } else {
            // Get only purchased individual lectures - without requiring course_id match
            $purchasedLectureIds = \App\Models\UserCourse::where('user_id', $user->id)
                ->where('status', 'active')
                ->whereNotNull('lecture_id')
                ->pluck('lecture_id')
                ->toArray();

            // Filter to only keep lectures in the current course
            $lectures = $course->lectures()
                ->whereIn('id', $purchasedLectureIds)
                ->orderBy('id')
                ->get();
        }

        // We need to make sure the current lecture is included in navigation even if not in $lectures
        if (!$lectures->contains('id', $lecture->id) && ($hasSpecificLectureAccess || $isAdmin)) {
            // Add the current lecture to the collection
            $lectures->push($lecture);
            $lectures = $lectures->sortBy('id')->values();
        }

        // Check if we're viewing the PDF version of the lecture
        $isViewingPdf = $request->query('view') === 'pdf';

        // Create a combined navigation array with both videos and PDFs in the desired sequence
        $navigationItems = [];
        foreach ($lectures as $lec) {
            // Add video item
            $navigationItems[] = [
                'type' => 'video',
                'lecture' => $lec
            ];

            // Add PDF item if available
            if ($lec->pdf_file_path) {
                $navigationItems[] = [
                    'type' => 'pdf',
                    'lecture' => $lec
                ];
            }
        }

        // Get current position in the navigation
        $currentPosition = null;
        foreach ($navigationItems as $index => $item) {
            if ($item['lecture']->id === $lecture->id &&
                ($isViewingPdf ? $item['type'] === 'pdf' : $item['type'] === 'video')) {
                $currentPosition = $index;
                break;
            }
        }

        // Get previous and next items
        $previousItem = null;
        $nextItem = null;

        if ($currentPosition !== null) {
            if ($currentPosition > 0) {
                $previousItem = $navigationItems[$currentPosition - 1];
            }

            if ($currentPosition < count($navigationItems) - 1) {
                $nextItem = $navigationItems[$currentPosition + 1];
            }
        }

        // Get related questions for admins
        $adminQuestions = null;
        if ($isAdmin) {
            $adminQuestions = $this->getRelatedQuestionsForAdmin($user, $course->id, $lecture->id);
        }

        // Encrypt YouTube video ID with Base64 if present
        if ($lecture->youtube_url) {
            // Extract YouTube ID from URL
            $youtubeId = null;
            $patterns = [
                '/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', // youtube.com/watch?v=ID
                '/youtu\.be\/([a-zA-Z0-9_-]+)/'             // youtu.be/ID
            ];

            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $lecture->youtube_url, $matches)) {
                    $youtubeId = $matches[1];
                    break;
                }
            }

            // Base64 encode the YouTube ID
            $encryptedYoutubeId = $youtubeId ? base64_encode($youtubeId) : null;

            // Store the encrypted ID
            $lecture->encrypted_youtube_id = $encryptedYoutubeId;
        }

        return view('course.purchased-lecture-detail', [
            'course' => $course,
            'lecture' => $lecture,
            'lectures' => $lectures,
            'isViewingPdf' => $isViewingPdf,
            'previousItem' => $previousItem,
            'nextItem' => $nextItem,
            'adminQuestions' => $adminQuestions,
            'isAdmin' => $isAdmin
        ]);
    }

    /**
     * Get related questions for admin users
     */
    private function getRelatedQuestionsForAdmin($user, $courseId, $lectureId = null)
    {
        // If admin, show all questions for this course/lecture
        if ($user->isAdmin()) {
            $query = \App\Models\Question::with(['user', 'course', 'lecture', 'attachments', 'answers.user', 'answers.attachments'])
                ->where('course_id', $courseId)
                ->orderBy('created_at', 'desc');

            if ($lectureId) {
                $query->where('lecture_id', $lectureId);
            }

            return $query->get();
        } else {
            // Regular admin only sees questions from their assigned users
            $assignedUserIds = \App\Models\AdminUserAssignment::where('admin_id', $user->id)
                ->pluck('user_id')
                ->toArray();

            $query = \App\Models\Question::with(['user', 'course', 'lecture', 'attachments', 'answers.user', 'answers.attachments'])
                ->whereIn('user_id', $assignedUserIds)
                ->where('course_id', $courseId)
                ->orderBy('created_at', 'desc');

            if ($lectureId) {
                $query->where('lecture_id', $lectureId);
            }

            return $query->get();
        }
    }

    /**
     * Check if user has access to course
     *
     * @param  \App\Models\Course  $course
     * @param  bool  $isAdminView  Whether to consider admin role for access (default: true)
     * @return bool
     */
    private function userHasAccess($course, $isAdminView = true)
    {
        // Consider admin role only in admin views or marketing views
        if ($isAdminView && (auth()->user()->role === 'admin' || auth()->user()->roles()->where('name', 'admin')->exists())) {
            return true;
        }

        // Check if the user has purchased this course
        return \App\Models\UserCourse::where('user_id', auth()->id())
            ->where('course_id', $course->id)
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Check if user has access to a specific lecture
     *
     * @param  \App\Models\Course  $course
     * @param  \App\Models\Lecture  $lecture
     * @param  bool  $isAdminView  Whether to consider admin role for access (default: true)
     * @return bool
     */
    private function userHasAccessToLecture($course, $lecture, $isAdminView = true)
    {
        $user = auth()->user();

        // First check if lecture actually belongs to the course
        if ($lecture->course_id != $course->id) {
            return false;
        }

        // Consider admin role only in admin views or marketing views
        if ($isAdminView && $user->isAdmin()) {
            return true;
        }

        // First directly check if user has specific lecture access - with course_id
        $hasLectureAccess = \App\Models\UserCourse::where('user_id', $user->id)
            ->where('lecture_id', $lecture->id)
            ->where('status', 'active')
            ->exists();

        if ($hasLectureAccess) {
            return true;
        }

        // Then check if user has full course access
        return \App\Models\UserCourse::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->whereNull('lecture_id')
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Get previous item in course navigation
     */
    private function getPreviousItem($course, $currentType, $currentId)
    {
        $result = ['item' => null, 'type' => null];

        if ($currentType == 'lecture') {
            // Get all lectures ordered by id
            $lectures = $course->lectures()->orderBy('id')->get();
            $currentIndex = $lectures->search(function($item) use ($currentId) {
                return $item->id == $currentId;
            });

            if ($currentIndex > 0) {
                // There's a previous lecture
                $result['item'] = $lectures[$currentIndex - 1];
                $result['type'] = 'lecture';
            }
        }

        return $result;
    }

    /**
     * Get next item in course navigation
     */
    private function getNextItem($course, $currentType, $currentId)
    {
        $result = ['item' => null, 'type' => null];

        if ($currentType == 'lecture') {
            // Get all lectures ordered by id
            $lectures = $course->lectures()->orderBy('id')->get();
            $currentIndex = $lectures->search(function($item) use ($currentId) {
                return $item->id == $currentId;
            });

            if ($currentIndex < $lectures->count() - 1) {
                // There's a next lecture
                $result['item'] = $lectures[$currentIndex + 1];
                $result['type'] = 'lecture';
            }
        }

        return $result;
    }
}
