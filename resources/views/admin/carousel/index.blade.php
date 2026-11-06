@extends('admin.layout')

@section('header', 'Banner Management')

@section('actions')
    <div class="d-flex gap-2 align-items-center flex-wrap">
        <form method="GET" action="{{ route('admin.banners.index') }}">
            <select name="page_key" class="pf-select" onchange="this.form.submit()">
                <option value="">All Pages</option>
                @foreach(\App\Models\CarouselSlide::pageOptions() as $value => $label)
                    <option value="{{ $value }}" {{ ($pageKey ?? '') === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </form>
        <a href="{{ route('admin.banners.create') }}" class="pf-btn-gold">
            <i class="fas fa-plus"></i> Add New Banner
        </a>
    </div>
@endsection

@section('content')
<style>
    .pf-select {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(212,166,88,0.3);
        border-radius: 12px;
        color: #f8e7d0;
        padding: 9px 16px;
        font-size: 0.9rem;
        outline: none;
        cursor: pointer;
        transition: border-color 0.2s;
    }
    .pf-select option { background: #1a1a1a; color: #f8e7d0; }
    .pf-select:focus { border-color: #d4a658; }

    .pf-btn-gold {
        background: linear-gradient(135deg, #d4a658, #9d6f20);
        color: #111 !important;
        font-weight: 700;
        font-size: 0.88rem;
        letter-spacing: 0.04em;
        text-decoration: none;
        padding: 10px 20px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
    }
    .pf-btn-gold:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(212,166,88,0.35);
        color: #000 !important;
    }

    .pf-table-wrap {
        overflow-x: auto;
        border-radius: 18px;
        border: 1px solid rgba(212,166,88,0.14);
    }
    .pf-table {
        width: 100%;
        border-collapse: collapse;
        color: #f8e7d0;
        font-size: 0.92rem;
    }
    .pf-table thead th {
        background: rgba(212,166,88,0.08);
        color: #d4a658;
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        font-weight: 700;
        padding: 14px 16px;
        border-bottom: 1px solid rgba(212,166,88,0.18);
        white-space: nowrap;
    }
    .pf-table tbody tr {
        border-bottom: 1px solid rgba(255,255,255,0.05);
        transition: background 0.15s;
    }
    .pf-table tbody tr:last-child { border-bottom: none; }
    .pf-table tbody tr:hover { background: rgba(212,166,88,0.05); }
    .pf-table td {
        padding: 14px 16px;
        vertical-align: middle;
    }

    .pf-badge-page {
        background: rgba(212,166,88,0.15);
        color: #d4a658;
        border: 1px solid rgba(212,166,88,0.3);
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        padding: 4px 10px;
        border-radius: 8px;
        white-space: nowrap;
    }
    .pf-badge-active {
        background: rgba(79,200,100,0.15);
        color: #5fcf6e;
        border: 1px solid rgba(79,200,100,0.3);
        font-size: 0.75rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 8px;
    }
    .pf-badge-inactive {
        background: rgba(255,255,255,0.06);
        color: rgba(248,231,208,0.5);
        border: 1px solid rgba(255,255,255,0.1);
        font-size: 0.75rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 8px;
    }

    .pf-thumb {
        width: 72px;
        height: 54px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid rgba(212,166,88,0.2);
    }

    .pf-drag-handle {
        cursor: grab;
        color: rgba(212,166,88,0.45);
        font-size: 1rem;
        padding: 6px;
        transition: color 0.2s;
    }
    .pf-drag-handle:hover { color: #d4a658; }

    .pf-btn-edit {
        background: rgba(212,166,88,0.12);
        color: #d4a658;
        border: 1px solid rgba(212,166,88,0.3);
        border-radius: 9px;
        padding: 7px 12px;
        font-size: 0.82rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.2s;
        cursor: pointer;
    }
    .pf-btn-edit:hover {
        background: rgba(212,166,88,0.22);
        color: #f4d7ab;
        border-color: #d4a658;
    }
    .pf-btn-delete {
        background: rgba(220,53,69,0.12);
        color: #f07080;
        border: 1px solid rgba(220,53,69,0.3);
        border-radius: 9px;
        padding: 7px 12px;
        font-size: 0.82rem;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.2s;
        cursor: pointer;
    }
    .pf-btn-delete:hover {
        background: rgba(220,53,69,0.22);
        color: #ff8090;
        border-color: #dc3545;
    }

    .pf-empty {
        text-align: center;
        padding: 60px 20px;
        color: rgba(248,231,208,0.5);
    }
    .pf-empty i { font-size: 3rem; margin-bottom: 16px; color: rgba(212,166,88,0.3); display: block; }
    .pf-empty p { margin-bottom: 20px; font-size: 1.05rem; }

    .pf-info-note {
        margin-top: 16px;
        padding: 12px 16px;
        background: rgba(212,166,88,0.06);
        border: 1px solid rgba(212,166,88,0.14);
        border-radius: 12px;
        color: rgba(248,231,208,0.65);
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .pf-info-note i { color: #d4a658; }
</style>

@if ($slides->count() > 0)
    <div class="pf-table-wrap">
        <table class="pf-table" id="sortable-carousel">
            <thead>
                <tr>
                    <th width="50" class="text-center"><i class="fas fa-grip-vertical"></i></th>
                    <th>Page</th>
                    <th>Preview</th>
                    <th>Title</th>
                    <th>Button Text</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody id="carousel-list">
                @foreach ($slides as $slide)
                    <tr data-id="{{ $slide->id }}">
                        <td class="text-center">
                            <span class="pf-drag-handle drag-handle"><i class="fas fa-grip-vertical"></i></span>
                        </td>
                        <td>
                            <span class="pf-badge-page">
                                {{ \App\Models\CarouselSlide::pageOptions()[$slide->page_key] ?? ucfirst(str_replace('-', ' ', $slide->page_key)) }}
                            </span>
                        </td>
                        <td>
                            @if($slide->image_url)
                                <img src="{{ $slide->image_url }}" alt="{{ $slide->title ?: 'One Stop Mart banner' }}" class="pf-thumb" style="object-fit: contain;">
                            @elseif(filled($slide->image_name))
                                <div class="pf-thumb-placeholder d-flex flex-column align-items-center justify-content-center" style="width: 72px; height: 54px; background: rgba(220, 53, 69, 0.1); border: 1px dashed #f07080; border-radius: 10px; color: #f07080; font-size: 0.65rem; padding: 2px; text-align: center; line-height: 1.1;">
                                    <i class="fas fa-exclamation-triangle" style="font-size: 0.8rem; margin-bottom: 2px; color: #f07080;"></i>
                                    <span>Image missing</span>
                                </div>
                            @else
                                <div class="pf-thumb-placeholder d-flex flex-column align-items-center justify-content-center" style="width: 72px; height: 54px; background: rgba(255,255,255,0.05); border: 1px dashed rgba(248,231,208,0.2); border-radius: 10px; color: rgba(248,231,208,0.4); font-size: 0.65rem; padding: 2px; text-align: center; line-height: 1.1;">
                                    <i class="fas fa-image" style="font-size: 0.8rem; margin-bottom: 2px;"></i>
                                    <span>No image</span>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div style="font-weight:600; color:#f8e7d0;">{{ $slide->title }}</div>
                            <div style="font-size:0.78rem; color:rgba(248,231,208,0.5); margin-top:3px;">Path: <code style="color: #d4a658; font-size:0.75rem; word-break: break-all;">{{ $slide->image_name }}</code></div>
                            <div style="font-size:0.78rem; color:rgba(248,231,208,0.5); margin-top:3px;">Order: {{ $slide->order }}</div>
                        </td>
                        <td style="color:rgba(248,231,208,0.7);">
                            {{ $slide->cta_text ?: '—' }}
                        </td>
                        <td class="text-center">
                            @if ($slide->is_active)
                                @if (!$slide->hasVisibleContent())
                                    <span class="pf-badge-inactive" style="background: rgba(220,53,69,0.15); color: #f07080; border: 1px solid rgba(220,53,69,0.3);"><i class="fas fa-exclamation-triangle" style="font-size:0.5rem; vertical-align:middle;"></i> Active (Empty)</span>
                                    <div style="font-size: 0.72rem; color: #f07080; margin-top: 4px; max-width: 150px; line-height: 1.2; text-align: center;">This banner has no visible content and will not appear on the storefront.</div>
                                @else
                                    <span class="pf-badge-active"><i class="fas fa-circle" style="font-size:0.5rem; vertical-align:middle;"></i> Active</span>
                                @endif
                            @else
                                <span class="pf-badge-inactive"><i class="fas fa-circle" style="font-size:0.5rem; vertical-align:middle;"></i> Inactive</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('admin.banners.edit', $slide->id) }}" class="pf-btn-edit" title="Edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('admin.banners.destroy', $slide->id) }}" style="display:inline;"
                                    onsubmit="return confirm('Delete this banner? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="pf-btn-delete" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pf-info-note">
        <i class="fas fa-grip-vertical"></i>
        Drag the handle to reorder banners. The first banner loads with highest priority.
    </div>
@else
    <div class="pf-empty">
        <i class="fas fa-images"></i>
        <p>No banners found{{ $pageKey ? ' for this page' : '' }}.</p>
        <a href="{{ route('admin.banners.create') }}" class="pf-btn-gold">
            <i class="fas fa-plus"></i> Create First Banner
        </a>
    </div>
@endif

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const listEl = document.getElementById('carousel-list');
        if (!listEl) return;

        Sortable.create(listEl, {
            handle: '.drag-handle',
            animation: 150,
            ghostClass: 'pf-ghost',
            onEnd: function () {
                const slides = [];
                document.querySelectorAll('#carousel-list tr').forEach((row, index) => {
                    slides.push({ id: row.dataset.id, order: index + 1 });
                    const orderCell = row.querySelector('.order-num');
                    if (orderCell) orderCell.textContent = 'Order: ' + (index + 1);
                });

                fetch('{{ route('admin.banners.reorder') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ slides })
                })
                .then(r => r.json())
                .then(data => { if (!data.success) alert('Reorder failed: ' + data.message); })
                .catch(() => alert('Error reordering banners.'));
            }
        });
    });
</script>
@endsection
