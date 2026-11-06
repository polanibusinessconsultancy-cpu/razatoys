<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Course;
use App\Models\Lecture;
use App\Models\UserCourse;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
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
     * Display the user dashboard with purchased courses.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        // Get all active course access records for the user
        // Note: For user dashboard, we show only purchased courses regardless of user role
        $activeCourses = UserCourse::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('course_id', '!=', null)
            ->whereNull('lecture_id') // Explicitly filter for full course access
            ->with('course', 'order')
            ->get();

        // Get all active lecture access records for the user
        // Again, for user dashboard, we show only purchased lectures regardless of user role
        $activeLectures = UserCourse::where('user_id', $user->id)
            ->where('status', 'active')
            ->where('lecture_id', '!=', null)
            ->with('lecture', 'lecture.course', 'order')
            ->get();

        // Get all pending orders for the current user
        $pendingOrders = Order::where('user_id', $user->id)
            ->where('status', 'pending')
            ->latest()
            ->get();

        // Format purchased items for the view with proper filtering to avoid duplicates
        $purchasedItems = [];
        $purchasedFullCourseIds = [];

        // First, collect course IDs where user has purchased the FULL course
        foreach ($activeCourses as $access) {
            if ($access->course) {
                $purchasedFullCourseIds[] = $access->course->id;
            }
        }

        // Add all full courses
        foreach ($activeCourses as $access) {
            if ($access->course) {
                $purchasedItems[] = [
                    'type' => 'course',
                    'item' => $access->course,
                    'order_date' => $access->created_at,
                    'expires_at' => $access->expires_at
                ];
            }
        }

        // Add individual lectures ONLY if user hasn't purchased the full course
        // Standalone lectures (course_id is null) are always shown
        foreach ($activeLectures as $access) {
            if ($access->lecture) {
                // Show standalone lectures or lectures whose course wasn't fully purchased
                if (!$access->lecture->course_id || !in_array($access->lecture->course_id, $purchasedFullCourseIds)) {
                    $purchasedItems[] = [
                        'type' => 'lecture',
                        'item' => $access->lecture,
                        'order_date' => $access->created_at,
                        'expires_at' => $access->expires_at
                    ];
                }
            }
        }

        // Extract pending items from pending orders
        $pendingItems = [];

        foreach ($pendingOrders as $order) {
            $cartItems = json_decode($order->cart_items, true);
            if (is_array($cartItems)) {
                foreach ($cartItems as $item) {
                    if (isset($item['course_id'])) {
                        $course = Course::find($item['course_id']);
                        if ($course && $course->product_kind === 'course') {
                            $pendingItems[] = [
                                'type' => 'course',
                                'item' => $course,
                                'order_date' => $order->created_at,
                                'order_id' => $order->id
                            ];
                        }
                    }
 elseif (isset($item['lecture_id'])) {
                        $lecture = Lecture::find($item['lecture_id']);
                        if ($lecture) {
                            $pendingItems[] = [
                                'type' => 'lecture',
                                'item' => $lecture,
                                'order_date' => $order->created_at,
                                'order_id' => $order->id
                            ];
                        }
                    }
                }
            }
        }

        // Get quiz attempts for the user (exclude if user is admin)
        $quizAttempts = collect();
        $quizStats = [
            'total' => 0,
            'passed' => 0,
            'failed' => 0,
            'pending' => 0,
        ];

        // Only show quiz attempts for non-admin users
        if (!in_array($user->role, ['Admin', 'Super Admin'])) {
            $quizAttempts = QuizAttempt::where('user_id', $user->id)
                ->with(['quiz.lecture.course', 'quiz.lecture'])
                ->orderBy('created_at', 'desc')
                ->get();

            // Calculate quiz statistics
            $quizStats = [
                'total' => $quizAttempts->count(),
                'passed' => $quizAttempts->where('status', 'passed')->count(),
                'failed' => $quizAttempts->where('status', 'failed')->count(),
                'pending' => $quizAttempts->where('status', 'pending_review')->count(),
            ];
        }

        return view('user.dashboard', compact('purchasedItems', 'pendingItems', 'quizAttempts', 'quizStats'));
    }

    /**
     * Display all quiz results for the user.
     *
     * @return \Illuminate\View\View
     */
    public function quizResults()
    {
        $user = Auth::user();

        // Prevent admins from viewing quiz results
        if (in_array($user->role, ['Admin', 'Super Admin'])) {
            return redirect()->route('user.dashboard')
                ->with('error', 'Administrators do not have quiz results. Quiz attempts are for students only.');
        }

        // Get all quiz attempts for the user with pagination
        $quizAttempts = QuizAttempt::where('user_id', $user->id)
            ->with(['quiz.lecture.course', 'quiz.lecture'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Calculate quiz statistics
        $quizStats = [
            'total' => QuizAttempt::where('user_id', $user->id)->count(),
            'passed' => QuizAttempt::where('user_id', $user->id)->where('status', 'passed')->count(),
            'failed' => QuizAttempt::where('user_id', $user->id)->where('status', 'failed')->count(),
            'pending' => QuizAttempt::where('user_id', $user->id)->where('status', 'pending_review')->count(),
        ];

        return view('user.quiz-results', compact('quizAttempts', 'quizStats'));
    }
}
