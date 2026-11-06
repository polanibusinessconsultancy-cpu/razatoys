@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-courses.css') }}">
@endsection

@extends('layouts.app')

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
@endphp

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Course Management</h1>
        <a href="{{ route('admin.course.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Add New Course
        </a>
    </div>

    <!-- Error Messages -->
    @if(isset($error))
        <div class="alert alert-danger mb-3">
            <h5>Error occurred:</h5>
            <p>{{ $error }}</p>
        </div>
    @endif

    <!-- Debug information -->
    @if(isset($debug_message))
        <div class="alert alert-warning mb-3">
            <h5>Debug Information:</h5>
            <p>{{ $debug_message }}</p>
        </div>
    @endif

    <div class="alert alert-info mb-3">
        <p>Total courses: {{ count($courses ?? []) }}</p>
        @if(isset($courses) && count($courses) > 0)
            <p>First course: {{ $courses->first()->name }}</p>
        @endif
    </div>

    <div class="card shadow">
        <div class="card-header bg-light">
            <h5 class="mb-0">All Courses</h5>
        </div>
        <div class="card-body">
            @if(isset($courses) && count($courses) > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="80">Image</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Type</th>
                                <th>Lectures</th>
                                <th>Features</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($courses as $course)
                                <tr>
                                    <td>
                                        <img src="{{ $course->image_path ? Storage::url($course->image_path) : 'https://via.placeholder.com/50x50' }}"
                                            alt="{{ $course->name }}"
                                            class="img-thumbnail course-thumbnail-50">
                                    </td>
                                    <td>{{ $course->name }}</td>
                                    <td>
                                        @if($course->is_free)
                                            <span class="badge bg-success">FREE</span>
                                        @else
                                            Rs {{ number_format($course->weekly_price, 2) }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($course->is_free)
                                            <span class="badge bg-info">Free Course</span>
                                        @else
                                            <span class="badge bg-primary">Paid Course</span>
                                        @endif
                                    </td>
                                    <td>{{ $course->lectures_count ?? count($course->lectures ?? []) }}</td>
                                    <td>
                                        <span class="badge bg-primary me-1">{{ $course->learn_features_count ?? 0 }} learning points</span>
                                        <span class="badge bg-info">{{ $course->requirement_features_count ?? 0 }} requirements</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.course.edit', $course->id) }}" class="btn btn-sm btn-primary me-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('course.detail', $course->id) }}" class="btn btn-sm btn-info" target="_blank">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info">
                    No courses have been added yet. Click the "Add New Course" button to create your first course.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
