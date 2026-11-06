<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-md-6">
                <h1 class="h3 mb-0 text-gray-800">Course Details</h1>
                <p class="mb-0">Viewing course: {{ $course->name }}</p>
            </div>
            <div class="col-md-6 text-end">
                <div class="btn-group" role="group">
                    <a href="{{ route('admin.courses') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Courses
                    </a>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Course Information</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th width="200">Name</th>
                            <td>{{ $course->name }}</td>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <td>{{ $course->description ?: 'No description provided' }}</td>
                        </tr>
                        <tr>
                            <th>Category</th>
                            <td>{{ $course->category ? $course->category->name : 'Uncategorized' }}</td>
                        </tr>
                        <tr>
                            <th>Price</th>
                            <td>{{ $course->price_formatted }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if($course->is_published)
                                    <span class="badge bg-success">Published</span>
                                @else
                                    <span class="badge bg-warning">Draft</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Created</th>
                            <td>{{ $course->created_at->format('F j, Y, g:i a') }}</td>
                        </tr>
                        <tr>
                            <th>Last Updated</th>
                            <td>{{ $course->updated_at->format('F j, Y, g:i a') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Course Lectures</h6>
            </div>
            <div class="card-body">
                @if($course->lectures->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-video fa-3x text-muted mb-3"></i>
                        <p class="mb-0">No lectures found for this course yet.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="5%">Order</th>
                                    <th>Name</th>
                                    <th width="15%">Duration</th>
                                    <th width="15%">Created</th>
                                    <th width="15%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($course->lectures->sortBy('order') as $lecture)
                                    <tr>
                                        <td class="text-center">{{ $lecture->order }}</td>
                                        <td>{{ $lecture->name }}</td>
                                        <td>{{ $lecture->duration_formatted }}</td>
                                        <td>{{ $lecture->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.lectures.show', $lecture) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout> 