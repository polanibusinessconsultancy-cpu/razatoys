@extends('admin.layout')

@section('header', 'Edit Banner')

@section('actions')
    <a href="{{ route('admin.banners.index') }}" class="pf-btn-outline">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>
@endsection

@section('content')
<style>
    .pf-btn-gold {
        background: linear-gradient(135deg, #d4a658, #9d6f20);
        color: #ffffff !important;
        font-weight: 700;
        font-size: 0.88rem;
        letter-spacing: 0.04em;
        text-decoration: none;
        padding: 11px 24px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .pf-btn-gold:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(212,166,88,0.35);
        color: #ffffff !important;
    }
    .pf-btn-outline {
        background: #ffffff;
        color: #334155 !important;
        border: 1px solid #cbd5e1;
        font-size: 0.88rem;
        text-decoration: none;
        padding: 10px 20px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .pf-btn-outline:hover {
        border-color: #d4a658;
        background: #f8fafc;
        color: #0f172a !important;
    }
    .pf-btn-cancel {
        background: #ffffff;
        color: #64748b !important;
        border: 1px solid #cbd5e1;
        font-size: 0.88rem;
        text-decoration: none;
        padding: 11px 20px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        cursor: pointer;
    }
    .pf-btn-cancel:hover { background: #f1f5f9; color: #0f172a !important; }

    .pf-form-label {
        display: block;
        font-size: 0.82rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #1e293b;
        margin-bottom: 8px;
    }
    .pf-form-label .req { color: #dc2626; }
    .pf-form-label .opt { color: #64748b; font-weight: 500; text-transform: none; letter-spacing: 0; font-size: 0.8rem; }

    .pf-input, .pf-textarea, .pf-select-field {
        width: 100%;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        color: #0f172a;
        padding: 11px 16px;
        font-size: 0.92rem;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        font-family: inherit;
        box-sizing: border-box;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
    }
    .pf-input::placeholder, .pf-textarea::placeholder { color: #94a3b8; }
    .pf-input:focus, .pf-textarea:focus, .pf-select-field:focus {
        border-color: #d4a658;
        box-shadow: 0 0 0 3px rgba(212,166,88,0.25);
    }
    .pf-input.is-invalid, .pf-textarea.is-invalid, .pf-select-field.is-invalid { border-color: #dc2626; }
    .pf-textarea { resize: vertical; min-height: 100px; }
    .pf-select-field option { background: #ffffff; color: #0f172a; }

    .pf-hint { margin-top: 6px; font-size: 0.8rem; color: #64748b; }
    .pf-error { margin-top: 6px; font-size: 0.8rem; color: #dc2626; display: flex; align-items: center; gap: 5px; }
    .pf-field { margin-bottom: 22px; }
    .pf-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    @media (max-width:640px) { .pf-row { grid-template-columns: 1fr; } }
    .pf-divider { border: none; border-top: 1px solid #e2e8f0; margin: 24px 0; }

    .pf-file-wrapper input[type="file"] {
        width: 100%;
        background: #ffffff;
        border: 1px dashed #cbd5e1;
        border-radius: 12px;
        color: #0f172a;
        padding: 11px 16px;
        font-size: 0.9rem;
        cursor: pointer;
        box-sizing: border-box;
        transition: border-color 0.2s;
    }
    .pf-file-wrapper input[type="file"]:hover { border-color: #d4a658; }

    .pf-switch-wrap {
        display: flex; align-items: center; gap: 14px;
        padding: 14px 18px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
    }
    .pf-switch-wrap input[type="checkbox"] { width: 40px; height: 22px; cursor: pointer; accent-color: #d4a658; flex-shrink: 0; }
    .pf-switch-label { font-size: 0.9rem; color: #1e293b; line-height: 1.4; }
    .pf-switch-label strong { color: #0f172a; }

    /* Current image preview */
    .pf-current-img-wrap {
        padding: 16px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        display: inline-flex;
        flex-direction: column;
        gap: 10px;
    }
    .pf-current-img {
        width: 200px;
        height: 150px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid #cbd5e1;
    }

    /* Sidebar */
    .pf-sidebar-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    .pf-sidebar-box__head {
        background: #f8fafc;
        padding: 16px 20px;
        border-bottom: 1px solid #e2e8f0;
        display: flex; align-items: center; gap: 10px;
        font-size: 0.9rem; font-weight: 700;
        color: #0f172a; letter-spacing: 0.05em;
    }
    .pf-sidebar-box__body { padding: 20px; color: #334155; font-size: 0.88rem; line-height: 1.65; }
    .pf-sidebar-box__body p { margin-bottom: 10px; }
    .pf-sidebar-box__body strong { color: #0f172a; }

    .pf-meta-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #e2e8f0;
        font-size: 0.85rem;
        color: #475569;
    }
    .pf-meta-row:last-child { border-bottom: none; }
    .pf-meta-row strong { color: #0f172a; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.08em; }

    .pf-badge-active { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; padding: 3px 10px; border-radius: 8px; font-size: 0.75rem; font-weight: 700; }
    .pf-badge-inactive { background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; padding: 3px 10px; border-radius: 8px; font-size: 0.75rem; font-weight: 700; }
    .pf-badge-order { background: #fefce8; color: #a16207; border: 1px solid #fef08a; padding: 3px 10px; border-radius: 8px; font-size: 0.75rem; font-weight: 700; }

    .pf-page-note { margin-top: 10px; padding: 9px 13px; border-radius: 10px; font-size: 0.82rem; font-weight: 600; }
    .pf-page-note.has-btn { background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; }
    .pf-page-note.no-btn { background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; }

    .pf-preview-box { margin-top: 14px; border: 2px dashed #cbd5e1; border-radius: 14px; overflow: hidden; aspect-ratio: 4/3; max-width: 280px; display: none; }
    .pf-preview-box img { width: 100%; height: 100%; object-fit: cover; }
    .pf-img-info { margin-top: 14px; padding: 14px 16px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 0.82rem; color: #475569; line-height: 1.7; }
    .pf-img-info ul { margin: 8px 0 0 16px; padding: 0; }

    .pf-grid { display: grid; grid-template-columns: 1fr 320px; gap: 28px; align-items: start; }
    @media (max-width: 900px) { .pf-grid { grid-template-columns: 1fr; } }
    .pf-form-actions { display: flex; gap: 12px; flex-wrap: wrap; align-items: center; margin-top: 28px; padding-top: 22px; border-top: 1px solid #e2e8f0; }
</style>

<div class="pf-grid">
    {{-- Main Form --}}
    <div>
        <form action="{{ route('admin.banners.update', $slide->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Page --}}
            <div class="pf-field">
                <label for="page_key" class="pf-form-label">Page <span class="req">*</span></label>
                <select class="pf-select-field @error('page_key') is-invalid @enderror"
                    id="page_key" name="page_key" required onchange="toggleCtaFields(this.value)">
                    @foreach(\App\Models\CarouselSlide::pageOptions() as $value => $label)
                        <option value="{{ $value }}" {{ old('page_key', $slide->page_key) === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('page_key')<div class="pf-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</div>@enderror
                <div class="pf-hint">Choose which page this banner should appear on.</div>
                <div id="cta_page_hint" class="pf-page-note" style="display:none;"></div>
            </div>

            {{-- Title --}}
            <div class="pf-field">
                <label for="title" class="pf-form-label">Banner Title <span class="opt">(Optional)</span></label>
                <input type="text" class="pf-input @error('title') is-invalid @enderror"
                    id="title" name="title" value="{{ old('title', $slide->title) }}"
                    maxlength="100" placeholder="e.g., Discover Our Collection">
                @error('title')<div class="pf-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</div>@enderror
                <div class="pf-hint">Maximum 100 characters.</div>
            </div>

            {{-- Subtitle --}}
            <div class="pf-field">
                <label for="subtitle" class="pf-form-label">Banner Text <span class="opt">(Optional)</span></label>
                <textarea class="pf-textarea @error('subtitle') is-invalid @enderror"
                    id="subtitle" name="subtitle" rows="3" maxlength="500"
                    placeholder="Describe the banner content...">{{ old('subtitle', $slide->subtitle) }}</textarea>
                @error('subtitle')<div class="pf-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</div>@enderror
                <div class="pf-hint">Maximum 500 characters.</div>
            </div>

            {{-- CTA Fields --}}
            <div id="cta_fields_wrapper">
                <div class="pf-row">
                    <div class="pf-field">
                        <label for="cta_text" class="pf-form-label">Button Text <span class="opt">(Optional)</span></label>
                        <input type="text" class="pf-input @error('cta_text') is-invalid @enderror"
                            id="cta_text" name="cta_text"
                            value="{{ old('cta_text', $slide->cta_text) }}"
                            maxlength="50" placeholder="e.g., Shop Now">
                        @error('cta_text')<div class="pf-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</div>@enderror
                        <div class="pf-hint">Maximum 50 characters.</div>
                    </div>
                    <div class="pf-field">
                        <label for="cta_url" class="pf-form-label">Button URL <span class="opt">(Optional)</span></label>
                        <input type="text" class="pf-input @error('cta_url') is-invalid @enderror"
                            id="cta_url" name="cta_url"
                            value="{{ old('cta_url', $slide->cta_url) }}"
                            placeholder="e.g., /new-arrivals or https://example.com">
                        @error('cta_url')<div class="pf-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</div>@enderror
                        <div class="pf-hint">Optional; required only when Button Text is provided</div>
                    </div>
                </div>
            </div>

            <hr class="pf-divider">

            @php
                $imgMeta = $slide->getImageMetaData();
            @endphp
            {{-- Current Image --}}
            {{-- Current Image --}}
            <div class="pf-field">
                <label class="pf-form-label">Current Image</label>
                <div style="display: flex; gap: 20px; align-items: flex-start; flex-wrap: wrap;">
                    @if($slide->image_url)
                        <div class="pf-current-img-wrap" style="flex-shrink: 0; background: rgba(0,0,0,0.3); border-radius: 12px; padding: 10px; border: 1px solid rgba(212, 166, 88, 0.2);">
                            <img src="{{ $slide->image_url }}" alt="{{ $slide->title ?: 'One Stop Mart banner' }}" class="pf-current-img" style="max-width: 250px; max-height: 250px; object-fit: contain; border-radius: 8px;">
                        </div>
                    @elseif(filled($slide->image_name))
                        <div class="pf-current-img-missing d-flex flex-column align-items-center justify-content-center" style="width: 250px; height: 180px; background: rgba(220, 53, 69, 0.08); border: 2px dashed #f07080; border-radius: 8px; color: #f07080; text-align: center; padding: 15px;">
                            <i class="fas fa-exclamation-triangle" style="font-size: 2rem; margin-bottom: 10px; color: #f07080;"></i>
                            <strong style="font-size: 0.95rem; display: block; margin-bottom: 5px;">Image file not found</strong>
                            <span style="font-size: 0.78rem; opacity: 0.85;">Upload a replacement image.</span>
                        </div>
                    @else
                        <div class="pf-current-img-missing d-flex flex-column align-items-center justify-content-center" style="width: 250px; height: 180px; background: rgba(255,255,255,0.03); border: 2px dashed rgba(248,231,208,0.2); border-radius: 8px; color: rgba(248,231,208,0.5); text-align: center; padding: 15px;">
                            <i class="fas fa-image" style="font-size: 2rem; margin-bottom: 10px;"></i>
                            <strong style="font-size: 0.95rem; display: block; margin-bottom: 5px;">No image selected for this banner.</strong>
                        </div>
                    @endif

                    @if(filled($slide->image_name))
                        <div style="flex: 1; min-width: 200px; color: rgba(248, 231, 208, 0.75); font-size: 0.88rem; line-height: 1.6;">
                            <h4 style="color: #d4a658; font-size: 0.95rem; font-weight: bold; margin: 0 0 10px;">Image Details</h4>
                            <div><strong>Filename:</strong> {{ $imgMeta['name'] }}</div>
                            <div><strong>Path:</strong> <code style="color: #f8e7d0; word-break: break-all;">{{ $imgMeta['path'] }}</code></div>
                            <div><strong>Dimensions:</strong> {{ $imgMeta['dimensions'] }}</div>
                            <div><strong>File Size:</strong> {{ $imgMeta['size'] }}</div>
                            <div><strong>Format:</strong> {{ $imgMeta['format'] }}</div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Replace Image --}}
            <div class="pf-field">
                <label for="image" class="pf-form-label">Replace Image <span class="opt">(Optional)</span></label>
                <div class="pf-file-wrapper">
                    <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp">
                </div>
                @error('image')<div class="pf-error"><i class="fas fa-exclamation-circle"></i>{{ $message }}</div>@enderror
                <div class="pf-img-info">
                    <strong style="color:#d4a658;">Image Processing:</strong>
                    <ul>
                        <li>Format: JPEG, PNG, or WebP (auto-converted to WebP)</li>
                        <li>Aspect Ratio: <strong style="color:#d4a658;">Preserved</strong> (no forced cropping)</li>
                        <li>Maximum File Size: 100MB | Leave empty to keep current image</li>
                    </ul>
                </div>
                <div class="pf-preview-box" id="image-preview-container">
                    <img id="image-preview" src="" alt="Preview">
                </div>
            </div>

            {{-- Active Toggle --}}
            <div class="pf-field">
                <div class="pf-switch-wrap">
                    <input type="checkbox" id="is_active" name="is_active"
                        value="1" {{ old('is_active', $slide->is_active) ? 'checked' : '' }}>
                    <div class="pf-switch-label">
                        <strong>Active</strong> — This banner will be visible on the website
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="pf-form-actions">
                <button type="submit" class="pf-btn-gold">
                    <i class="fas fa-save"></i> Update Banner
                </button>
                <a href="{{ route('admin.banners.index') }}" class="pf-btn-cancel">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>

    {{-- Sidebar Info --}}
    <div>
        <div class="pf-sidebar-box">
            <div class="pf-sidebar-box__head">
                <i class="fas fa-info-circle"></i> Banner Information
            </div>
            <div class="pf-sidebar-box__body">
                <div class="pf-meta-row">
                    <strong>Order</strong>
                    <span class="pf-badge-order"># {{ $slide->order }}</span>
                </div>
                <div class="pf-meta-row">
                    <strong>Status</strong>
                    @if($slide->is_active)
                        <span class="pf-badge-active">Active</span>
                    @else
                        <span class="pf-badge-inactive">Inactive</span>
                    @endif
                </div>
                <div class="pf-meta-row">
                    <strong>Created</strong>
                    <span style="font-size:0.8rem;">{{ $slide->created_at->format('M d, Y') }}</span>
                </div>
                <div class="pf-meta-row" style="border-bottom:none;">
                    <strong>Updated</strong>
                    <span style="font-size:0.8rem;">{{ $slide->updated_at->format('M d, Y') }}</span>
                </div>

                @if($slide->creator)
                <p style="margin-top:12px; font-size:0.82rem; color:rgba(248,231,208,0.5);">
                    Created by <strong>{{ $slide->creator->name }}</strong>
                </p>
                @endif

                <hr style="border-color:rgba(212,166,88,0.15); margin:14px 0;">
                <p style="margin-bottom:0; font-size:0.82rem;"><strong>Tip:</strong> Reorder banners by dragging from the list view.</p>
            </div>
        </div>
    </div>
</div>

<script>
    const pagesWithButton = ['home', 'cart'];

    function toggleCtaFields(pageKey) {
        const wrapper = document.getElementById('cta_fields_wrapper');
        const ctaText = document.getElementById('cta_text');
        const ctaUrl  = document.getElementById('cta_url');
        const hint    = document.getElementById('cta_page_hint');
        const hasBtn  = pagesWithButton.includes(pageKey);

        if (hasBtn) {
            wrapper.style.display = 'block';
            hint.className   = 'pf-page-note has-btn';
            hint.style.display = 'block';
            hint.innerHTML   = '<i class="fas fa-check-circle"></i> This page supports a CTA button (Optional).';
        } else {
            wrapper.style.display = 'none';
            hint.className   = 'pf-page-note no-btn';
            hint.style.display = 'block';
            hint.innerHTML   = '<i class="fas fa-ban"></i> This page does not support a CTA button.';
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        toggleCtaFields(document.getElementById('page_key').value);
    });

    document.getElementById('image').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = ev => {
            const box = document.getElementById('image-preview-container');
            document.getElementById('image-preview').src = ev.target.result;
            box.style.display = 'block';
        };
        reader.readAsDataURL(file);
    });
</script>
@endsection
