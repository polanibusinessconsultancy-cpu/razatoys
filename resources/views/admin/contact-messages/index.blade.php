@extends('admin.layout')

@section('title', 'Contact Messages — Admin')

@section('header', 'Customer Contact Messages')

@section('content')
    <div class="pf-table-wrap" style="background:#fff;border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,0.05);padding:20px;">
        
        <!-- Header & Status Filter Pills -->
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:15px;margin-bottom:20px;padding-bottom:15px;border-bottom:1px solid #eee;">
            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-sm {{ empty($status) ? 'btn-primary' : 'btn-outline-secondary' }}" style="border-radius:20px;font-weight:600;">
                    All ({{ $statusCounts['all'] }})
                </a>
                <a href="{{ route('admin.contact-messages.index', ['status' => 'new']) }}" class="btn btn-sm {{ $status === 'new' ? 'btn-danger' : 'btn-outline-danger' }}" style="border-radius:20px;font-weight:600;">
                    New ({{ $statusCounts['new'] }})
                </a>
                <a href="{{ route('admin.contact-messages.index', ['status' => 'read']) }}" class="btn btn-sm {{ $status === 'read' ? 'btn-info' : 'btn-outline-info' }}" style="border-radius:20px;font-weight:600;">
                    Read ({{ $statusCounts['read'] }})
                </a>
                <a href="{{ route('admin.contact-messages.index', ['status' => 'replied']) }}" class="btn btn-sm {{ $status === 'replied' ? 'btn-success' : 'btn-outline-success' }}" style="border-radius:20px;font-weight:600;">
                    Replied ({{ $statusCounts['replied'] }})
                </a>
                <a href="{{ route('admin.contact-messages.index', ['status' => 'closed']) }}" class="btn btn-sm {{ $status === 'closed' ? 'btn-secondary' : 'btn-outline-secondary' }}" style="border-radius:20px;font-weight:600;">
                    Closed ({{ $statusCounts['closed'] }})
                </a>
            </div>
            <span class="text-muted" style="font-size:0.9rem;">
                Showing {{ $messages->count() }} of {{ $messages->total() }} messages
            </span>
        </div>

        @if(session('success'))
            <div class="alert alert-success" style="border-radius:8px;margin-bottom:20px;">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle" width="100%">
                <thead style="background:#f8f9fa;">
                    <tr>
                        <th style="width:60px;">ID</th>
                        <th>Sender Name</th>
                        <th>Email Address</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Submitted At</th>
                        <th style="width:140px;text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($messages as $msg)
                        <tr style="{{ $msg->status === 'new' ? 'font-weight:700;background:rgba(220,53,69,0.03);' : '' }}">
                            <td>#{{ $msg->id }}</td>
                            <td>{{ $msg->name }}</td>
                            <td><a href="mailto:{{ $msg->email }}">{{ $msg->email }}</a></td>
                            <td>{{ Str::limit($msg->subject, 40) }}</td>
                            <td>
                                @if($msg->status === 'new')
                                    <span class="badge bg-danger text-white">NEW</span>
                                @elseif($msg->status === 'read')
                                    <span class="badge bg-info text-white">READ</span>
                                @elseif($msg->status === 'replied')
                                    <span class="badge bg-success text-white">REPLIED</span>
                                @else
                                    <span class="badge bg-secondary text-white">CLOSED</span>
                                @endif
                            </td>
                            <td style="font-size:0.85rem;color:#666;">
                                {{ $msg->created_at->format('M d, Y g:i A') }}
                            </td>
                            <td style="text-align:right;">
                                <div style="display:flex;gap:6px;justify-content:flex-end;">
                                    <a href="{{ route('admin.contact-messages.show', $msg->id) }}" class="btn btn-sm btn-primary" title="View Message">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <form action="{{ route('admin.contact-messages.destroy', $msg->id) }}" method="POST" onsubmit="return confirm('Delete this message?');" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center;padding:40px;color:#888;">
                                No contact messages found in this view.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:20px;">
            {{ $messages->links() }}
        </div>
    </div>
@endsection
