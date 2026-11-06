@extends('admin.layout')

@section('title', 'View Message #' . $message->id . ' — Admin')

@section('header', 'Contact Message Details')

@section('actions')
    <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Back to Messages
    </a>
@endsection

@section('content')
    <div style="background:#fff;border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,0.05);padding:30px;max-width:900px;margin:0 auto;">
        
        @if(session('success'))
            <div class="alert alert-success" style="border-radius:8px;margin-bottom:20px;">
                {{ session('success') }}
            </div>
        @endif

        <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:15px;border-bottom:1px solid #eee;padding-bottom:20px;margin-bottom:24px;">
            <div>
                <h3 style="margin:0 0 6px 0;font-weight:800;color:#111;">{{ $message->subject }}</h3>
                <span style="font-size:0.9rem;color:#666;">
                    Received on {{ $message->created_at->format('F j, Y \a\t g:i A') }}
                </span>
            </div>
            
            <form action="{{ route('admin.contact-messages.update-status', $message->id) }}" method="POST" style="display:flex;align-items:center;gap:10px;">
                @csrf
                @method('PATCH')
                <label for="status-select" style="font-weight:bold;margin:0;font-size:0.9rem;">Status:</label>
                <select name="status" id="status-select" class="form-select form-select-sm" style="width:130px;" onchange="this.form.submit()">
                    <option value="new" {{ $message->status === 'new' ? 'selected' : '' }}>New</option>
                    <option value="read" {{ $message->status === 'read' ? 'selected' : '' }}>Read</option>
                    <option value="replied" {{ $message->status === 'replied' ? 'selected' : '' }}>Replied</option>
                    <option value="closed" {{ $message->status === 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </form>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(240px, 1fr));gap:20px;background:#f8f9fa;padding:20px;border-radius:8px;margin-bottom:24px;">
            <div>
                <span style="font-size:0.75rem;text-transform:uppercase;font-weight:700;color:#888;display:block;margin-bottom:4px;">Sender Name</span>
                <strong style="font-size:1.05rem;color:#111;">{{ $message->name }}</strong>
            </div>
            <div>
                <span style="font-size:0.75rem;text-transform:uppercase;font-weight:700;color:#888;display:block;margin-bottom:4px;">Sender Email</span>
                <a href="mailto:{{ $message->email }}?subject={{ rawurlencode('Re: ' . $message->subject) }}" style="font-size:1.05rem;font-weight:700;color:#2563eb;text-decoration:none;">
                    {{ $message->email }} <i class="fas fa-external-link-alt" style="font-size:0.8rem;"></i>
                </a>
            </div>
            <div>
                <span style="font-size:0.75rem;text-transform:uppercase;font-weight:700;color:#888;display:block;margin-bottom:4px;">IP Address</span>
                <span style="font-size:0.95rem;color:#555;">{{ $message->ip_address ?: 'Not recorded' }}</span>
            </div>
        </div>

        <div style="margin-bottom:30px;">
            <h5 style="font-weight:700;color:#111;margin-bottom:12px;">Message Content:</h5>
            <div style="background:#fff;border:1px solid #e2e8f0;border-radius:8px;padding:24px;line-height:1.7;white-space:pre-wrap;color:#2d3748;font-size:1rem;">{{ $message->message }}</div>
        </div>

        <div style="display:flex;justify-content:space-between;align-items:center;padding-top:20px;border-top:1px solid #eee;">
            <a href="mailto:{{ $message->email }}?subject={{ rawurlencode('Re: ' . $message->subject) }}" class="btn btn-primary">
                <i class="fas fa-reply"></i> Reply via Email
            </a>
            
            <form action="{{ route('admin.contact-messages.destroy', $message->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this message?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Delete Message
                </button>
            </form>
        </div>
    </div>
@endsection
