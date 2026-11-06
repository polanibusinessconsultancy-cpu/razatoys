@extends('admin.layout')

@section('title', 'Courses Management')

@section('header', 'Courses Management')

@section('actions')
    <a href="{{ route('admin.course.create') }}" class="btn btn-sm btn-primary">
        <i class="fas fa-plus"></i> Add New Course
    </a>
@endsection

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold">All Courses</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Purchase Model</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($courses as $course)
                            <tr>
                                <td>{{ $course->id }}</td>
                                <td>
                                    <img src="{{ Storage::url($course->image_path) }}" alt="{{ $course->name }}" class="img-thumbnail" width="50">
                                </td>
                                <td>{{ $course->name }}</td>
                                <td>{{ $course->category ? $course->category->name : 'Uncategorized' }}</td>
                                <td>Rs {{ number_format($course->weekly_price, 2) }}</td>
                                <td>
                                    @if($course->isPurchaseRestricted())
                                        <span class="badge bg-danger">
                                            <i class="fas fa-lock me-1"></i>Restricted
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            <i class="fas fa-unlock me-1"></i>Flexible
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('course.detail', $course->id) }}" class="btn btn-info btn-sm" target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.course.create') }}?edit={{ $course->id }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $course->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No courses found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $courses->links() }}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.delete-btn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const courseId = this.getAttribute('data-id');

                if (confirm('Are you sure you want to delete this course? This action cannot be undone.')) {
                    // Submit delete request
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/course/${courseId}`;
                    form.style.display = 'none';

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';

                    const method = document.createElement('input');
                    method.type = 'hidden';
                    method.name = '_method';
                    method.value = 'DELETE';

                    form.appendChild(csrfToken);
                    form.appendChild(method);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
