@extends('polani.layout')

@section('title', ($product['name'] ?? 'Product Details') . ' — RazaToys')
@section('meta_description', $product['description'] ?? 'Explore this premium toy or essential item at RazaToys.')
@section('body_class', 'page-product')

@push('styles')
    <link rel="stylesheet" href="{{ asset('one-stop-mart/css/product-details.css') }}">
@endpush

@section('content')

  @php
    $videoId = null;
    $videoUrl = $product['intro_video_url'] ?? null;
    if ($videoUrl) {
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $videoUrl, $match)) {
            $videoId = $match[1];
        }
    }

    $hasSpecs = !empty($product['longevity']) ||
                !empty($product['projection']) ||
                !empty($product['season']) ||
                (!empty($product['occasion']) && is_array($product['occasion']) && count($product['occasion']) > 0) ||
                (!empty($product['family']) && is_array($product['family']) && count($product['family']) > 0) ||
                (!empty($product['notes']['top']) || !empty($product['notes']['heart']) || !empty($product['notes']['base']));
  @endphp

  <div class="container osm-product-page">
    
    <!-- ============ BREADCRUMB ============ -->
    <div class="osm-product-breadcrumb">
      <a href="{{ route('home') }}">Home</a>
      <span class="osm-product-breadcrumb__sep">›</span>
      <a href="{{ route('polani.collection') }}">Shop</a>
      <span class="osm-product-breadcrumb__sep">›</span>
      <span class="osm-product-breadcrumb__current">{{ $product['name'] ?? 'Product Details' }}</span>
    </div>

    <!-- ============ TWO-COLUMN LAYOUT ============ -->
    <div class="osm-product-layout">
      
      <!-- Left Media Column -->
      <div class="osm-product-layout__left">
        <div class="osm-product-media">
          <img 
            src="{{ !empty($product['image']) ? $product['image'] : asset('one-stop-mart/images/product-teddy.png') }}" 
            alt="{{ $product['name'] ?? 'Product Image' }}" 
            class="osm-product-main-image"
            loading="eager"
            fetchpriority="high"
          >
        </div>
        
        <!-- Video Section under product image -->
        @if($videoId)
          <div class="osm-product-video" aria-label="Product introduction video">
            <iframe 
              src="https://www.youtube.com/embed/{{ $videoId }}" 
              title="{{ $product['name'] ?? 'Product Video' }}"
              allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
              allowfullscreen
              loading="lazy"
            ></iframe>
          </div>
        @endif
      </div>

      <!-- Right Summary Column -->
      <div class="osm-product-summary">
        @if(!empty($product['category_name']))
          <span class="osm-product-category">{{ $product['category_name'] }}</span>
        @endif

        <h1 class="osm-product-title">{{ $product['name'] ?? 'Unnamed Product' }}</h1>

        <!-- Rating Summary Top -->
        <div class="osm-product-rating" id="product-rating-summary-top" style="display: none;">
          <span class="stars" id="top-stars"></span>
          <span class="rating-val" id="top-rating-val">0.0</span>
          <span class="rating-count" id="top-rating-count">(0 reviews)</span>
        </div>

        <div class="osm-product-price-container" style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px; flex-wrap: wrap;">
          <span class="osm-product-price" style="font-size: 2rem; font-weight: 800; color: var(--color-secondary);">
            Rs. {{ number_format((float) ($product['price'] ?? 0)) }}
          </span>
          @if(!empty($product['old_price']) && (float)$product['old_price'] > (float)$product['price'])
            <span class="osm-product-old-price" style="text-decoration: line-through; color: var(--color-text-muted); font-size: 1.25rem;">
              Rs. {{ number_format((float)$product['old_price']) }}
            </span>
            @php
              $discountPercent = round((($product['old_price'] - $product['price']) / $product['old_price']) * 100);
            @endphp
            <span class="osm-product-discount-badge" style="background: #dc3545; color: #fff; font-size: 0.9rem; padding: 4px 10px; border-radius: 4px; font-weight: bold;">
              {{ $discountPercent }}% OFF
            </span>
          @endif
        </div>


        <p class="osm-product-description">
          {{ $product['description'] ?? 'No description available.' }}
        </p>

        <!-- Add to Cart (Session AJAX Trigger) -->
        <div class="osm-product-cart">
          <button 
            type="button" 
            data-add-to-cart 
            data-add-url="{{ route('polani.cart.add', ['slug' => $product['slug']]) }}"
          >
            Add to Cart
          </button>
        </div>

        <!-- Product Policies -->
        <div class="osm-product-policy">
          <div class="osm-product-policy-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="1" y="3" width="15" height="13"/>
              <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/>
              <circle cx="5.5" cy="18.5" r="2.5"/>
              <circle cx="18.5" cy="18.5" r="2.5"/>
            </svg>
            <span>Exchanges apply only to faulty, damaged, defective, or incorrectly supplied products.</span>
          </div>
          <div class="osm-product-policy-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
              <polyline points="9 12 11 14 15 10"/>
            </svg>
            <span>Change-of-mind returns are not accepted.</span>
          </div>
          <div class="osm-product-policy-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M10 2h4v4h-4z"/><path d="M8 6h8l-1 16H9L8 6z"/><path d="M10 10h4"/>
            </svg>
            <span>Read more policies in the <a href="{{ route('polani.returns') }}" style="text-decoration: underline; color: var(--color-primary-dark);">Returns and Refunds page</a>.</span>
          </div>
        </div>

      </div>
    </div>

    <!-- ============ TABS SECTION ============ -->
    <div class="osm-product-tabs">
      
      <!-- Tab Header Nav -->
      <div class="osm-product-tab-list" role="tablist">
        <button 
          class="osm-product-tab active" 
          role="tab" 
          aria-selected="true" 
          aria-controls="panel-description" 
          id="tab-desc-btn"
        >
          Description
        </button>
        @if($hasSpecs)
          <button 
            class="osm-product-tab" 
            role="tab" 
            aria-selected="false" 
            aria-controls="panel-specifications" 
            id="tab-specs-btn"
          >
            Specifications
          </button>
        @endif
        <button 
          class="osm-product-tab" 
          role="tab" 
          aria-selected="false" 
          aria-controls="panel-reviews" 
          id="tab-reviews-btn"
        >
          Reviews (<span id="review-count-badge">0</span>)
        </button>
        <button 
          class="osm-product-tab" 
          role="tab" 
          aria-selected="false" 
          aria-controls="panel-policy" 
          id="tab-policy-btn"
        >
          Delivery & Exchange
        </button>
      </div>

      <!-- Tab Panels -->

      <!-- Panel: Description -->
      <div class="osm-product-tab-panel active" id="panel-description" role="tabpanel" aria-labelledby="tab-desc-btn" aria-hidden="false">
        @if(!empty($product['long_description']))
          <div class="osm-product-long-desc">
            {!! htmlspecialchars_decode($product['long_description']) !!}
          </div>
        @else
          <p>{{ $product['description'] ?? 'No detailed description available.' }}</p>
        @endif
      </div>

      <!-- Panel: Specifications -->
      @if($hasSpecs)
        <div class="osm-product-tab-panel" id="panel-specifications" role="tabpanel" aria-labelledby="tab-specs-btn" aria-hidden="true">
          <table class="osm-product-specifications">
            @if(!empty($product['notes']))
              @if(!empty($product['notes']['top']))
                <tr>
                  <td>Top Notes</td>
                  <td>{{ is_array($product['notes']['top']) ? implode(', ', $product['notes']['top']) : $product['notes']['top'] }}</td>
                </tr>
              @endif
              @if(!empty($product['notes']['heart']))
                <tr>
                  <td>Heart Notes</td>
                  <td>{{ is_array($product['notes']['heart']) ? implode(', ', $product['notes']['heart']) : $product['notes']['heart'] }}</td>
                </tr>
              @endif
              @if(!empty($product['notes']['base']))
                <tr>
                  <td>Base Notes</td>
                  <td>{{ is_array($product['notes']['base']) ? implode(', ', $product['notes']['base']) : $product['notes']['base'] }}</td>
                </tr>
              @endif
            @endif
            @if(!empty($product['family']) && is_array($product['family']))
              <tr>
                <td>Fragrance Family</td>
                <td>{{ implode(', ', $product['family']) }}</td>
              </tr>
            @endif
            @if(!empty($product['occasion']) && is_array($product['occasion']))
              <tr>
                <td>Best Occasion</td>
                <td>{{ implode(', ', $product['occasion']) }}</td>
              </tr>
            @endif
            @if(!empty($product['longevity']))
              <tr>
                <td>Longevity</td>
                <td>{{ $product['longevity'] }}</td>
              </tr>
            @endif
            @if(!empty($product['projection']))
              <tr>
                <td>Projection</td>
                <td>{{ $product['projection'] }}</td>
              </tr>
            @endif
            @if(!empty($product['season']))
              <tr>
                <td>Best Season</td>
                <td>{{ $product['season'] }}</td>
              </tr>
            @endif
          </table>
        </div>
      @endif

      <!-- Panel: Reviews -->
      <div 
        class="osm-product-tab-panel" 
        id="panel-reviews" 
        role="tabpanel" 
        aria-labelledby="tab-reviews-btn" 
        aria-hidden="true"
        data-ratings-url="{{ route('courses.ratings', ['course' => $product['db_id']]) }}"
      >
        <div class="osm-product-reviews">
          
          <!-- Rating Summary Left Box -->
          <div class="osm-product-reviews-left">
            <div class="osm-product-reviews-avg" id="avg-rating-display">—</div>
            <div class="osm-product-reviews-stars" id="avg-stars-display">
              <span class="pdt-star pdt-star--empty">★</span>
              <span class="pdt-star pdt-star--empty">★</span>
              <span class="pdt-star pdt-star--empty">★</span>
              <span class="pdt-star pdt-star--empty">★</span>
              <span class="pdt-star pdt-star--empty">★</span>
            </div>
            <div class="osm-product-reviews-count" id="total-reviews-label">No reviews yet</div>
          </div>

          <!-- Rating Statistics Bars -->
          <div class="osm-product-reviews-bars" id="rating-bars">
            @foreach([5,4,3,2,1] as $star)
              <div class="osm-product-bar-row" data-star="{{ $star }}">
                <span class="osm-product-bar-label">{{ $star }} ★</span>
                <div class="osm-product-bar-track">
                  <div class="osm-product-bar-fill"></div>
                </div>
                <span class="osm-product-bar-count">0</span>
              </div>
            @endforeach
          </div>

        </div>

        <!-- Rendered Reviews Card List -->
        <div class="osm-product-reviews-list" id="reviews-list">
          <div class="osm-product-reviews-empty" id="no-reviews-msg">
            No reviews have been submitted for this product yet.
          </div>
        </div>

        <!-- Write Review Form -->
        <div class="osm-product-review-form-wrapper">
          <h4 class="osm-product-review-form-title">Write a Review</h4>
          <form 
            class="osm-product-review-form" 
            id="review-form"
            data-review-submit-url="{{ route('courses.rate', ['course' => $product['db_id']]) }}"
          >
            <div class="osm-product-review-field">
              <label for="rev-name">Your Name</label>
              <input type="text" id="rev-name" placeholder="e.g., Ahmed Khan">
            </div>
            
            <div class="osm-product-review-field">
              <label>Rating</label>
              <div class="osm-product-star-picker" id="star-picker" role="radiogroup" aria-label="Rating">
                @foreach([1,2,3,4,5] as $s)
                  <button type="button" class="pdt-star-pick" data-val="{{ $s }}" aria-label="{{ $s }} star">★</button>
                @endforeach
              </div>
              <input type="hidden" id="rev-rating" value="0">
            </div>

            <div class="osm-product-review-field">
              <label for="rev-body">Your Review</label>
              <textarea id="rev-body" rows="4" placeholder="Tell us about your experience..." required></textarea>
            </div>

            <button type="submit">Post Review</button>
            <div class="osm-product-review-msg" id="rev-msg" style="display: none;"></div>
          </form>
        </div>

      </div>

      <!-- Panel: Delivery & Exchange -->
      <div class="osm-product-tab-panel" id="panel-policy" role="tabpanel" aria-labelledby="tab-policy-btn" aria-hidden="true">
        <p><strong>RazaToys Store Policies:</strong></p>
        <ul>
          <li>Exchanges are considered only for faulty, damaged, defective, or incorrectly supplied products.</li>
          <li>Claims must be reported to our customer center within the timeframe listed in our return policies.</li>
          <li>Change-of-mind returns are not accepted.</li>
          <li>Approved exchange claims will receive a replacement of the identical product; cash refunds are not normally issued.</li>
          <li>Your statutory legal rights remain completely unaffected.</li>
        </ul>
        <p>For more details, please view our full <a href="{{ route('polani.returns') }}" style="text-decoration: underline; color: var(--color-primary-dark);">Returns & Refunds policy</a>.</p>
      </div>

    </div>

  </div>

@endsection

@push('scripts')
  <script defer src="{{ asset('one-stop-mart/js/product-details.js') }}"></script>
  
  <!-- Embedded reviews network request handlers to preserve existing business logic exactly -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const PRODUCT_DB_ID = '{{ $product["db_id"] }}';
      const ratingsPanel = document.getElementById('panel-reviews');
      const ratingsUrl = ratingsPanel ? ratingsPanel.getAttribute('data-ratings-url') : '';
      const reviewForm = document.getElementById('review-form');
      const reviewSubmitUrl = reviewForm ? reviewForm.getAttribute('data-review-submit-url') : '';

      function fetchReviews() {
        if (!ratingsUrl) return;
        fetch(ratingsUrl)
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              renderReviews(data.ratings, data.average_rating, data.rating_count);
            }
          })
          .catch(err => console.error('Error fetching reviews:', err));
      }

      function renderStars(avg) {
        const full = Math.floor(avg);
        const hasHalf = (avg - full) >= 0.5;
        return [1,2,3,4,5].map(i => {
          if (i <= full) return '<span class="pdt-star pdt-star--full" style="color: #FFC107;">★</span>';
          if (i === full + 1 && hasHalf) return '<span class="pdt-star pdt-star--half" style="color: #FFC107; opacity: 0.7;">★</span>';
          return '<span class="pdt-star pdt-star--empty" style="color: #e0e0e0;">★</span>';
        }).join('');
      }

      function updateTopRatingSummary(reviews, averageRating, ratingCount) {
        const topSummary = document.getElementById('product-rating-summary-top');
        const topStars = document.getElementById('top-stars');
        const topVal = document.getElementById('top-rating-val');
        const topCount = document.getElementById('top-rating-count');

        if (!topSummary) return;

        if (reviews.length === 0) {
          topStars.innerHTML = [1,2,3,4,5].map(() => '<span class="pdt-star pdt-star--empty" style="color: #e0e0e0;">★</span>').join('');
          topVal.textContent = '0.0';
          topCount.textContent = '(0 reviews)';
          topSummary.style.display = 'inline-flex';
          return;
        }

        const avg = parseFloat(averageRating || 0);
        
        const full = Math.floor(avg);
        const hasHalf = (avg - full) >= 0.5;
        topStars.innerHTML = [1,2,3,4,5].map(i => {
          if (i <= full) return '<span class="pdt-star pdt-star--full" style="color: #FFC107;">★</span>';
          if (i === full + 1 && hasHalf) return '<span class="pdt-star pdt-star--half" style="color: #FFC107; opacity: 0.7;">★</span>';
          return '<span class="pdt-star pdt-star--empty" style="color: #e0e0e0;">★</span>';
        }).join('');

        topVal.textContent = avg.toFixed(1);
        topCount.textContent = `(${ratingCount} review${ratingCount !== 1 ? 's' : ''})`;
        topSummary.style.display = 'inline-flex';
      }

      function renderReviews(reviews, averageRating, ratingCount) {
        updateTopRatingSummary(reviews, averageRating, ratingCount);

        const list = document.getElementById('reviews-list');
        const badge = document.getElementById('review-count-badge');
        const avgEl = document.getElementById('avg-rating-display');
        const starsEl = document.getElementById('avg-stars-display');
        const totalEl = document.getElementById('total-reviews-label');

        if (badge) badge.textContent = reviews.length;

        if (reviews.length === 0) {
          if (list) {
            list.innerHTML = `
              <div class="osm-product-reviews-empty" id="no-reviews-msg">
                No reviews have been submitted for this product yet.
              </div>
            `;
          }
          if (avgEl) avgEl.textContent = '—';
          if (starsEl) {
            starsEl.innerHTML = [1,2,3,4,5].map(() => '<span class="pdt-star pdt-star--empty" style="color: #e0e0e0;">★</span>').join('');
          }
          if (totalEl) totalEl.textContent = 'No reviews yet';
          
          [5,4,3,2,1].forEach(s => {
            const row = document.querySelector(`.osm-product-bar-row[data-star="${s}"]`);
            if (row) {
              row.querySelector('.osm-product-bar-fill').style.width = '0%';
              row.querySelector('.osm-product-bar-count').textContent = '0';
            }
          });
          return;
        }

        if (avgEl) avgEl.textContent = averageRating ? parseFloat(averageRating).toFixed(1) : '—';
        if (starsEl) starsEl.innerHTML = renderStars(averageRating);
        if (totalEl) totalEl.textContent = ratingCount + ' review' + (ratingCount !== 1 ? 's' : '');

        const counts = {5:0,4:0,3:0,2:0,1:0};
        reviews.forEach(r => {
          if (counts[r.rating] !== undefined) {
            counts[r.rating]++;
          }
        });

        [5,4,3,2,1].forEach(s => {
          const row = document.querySelector(`.osm-product-bar-row[data-star="${s}"]`);
          if (!row) return;
          const pct = reviews.length ? Math.round((counts[s] / reviews.length) * 100) : 0;
          row.querySelector('.osm-product-bar-fill').style.width = pct + '%';
          row.querySelector('.osm-product-bar-count').textContent = counts[s];
        });

        const cards = reviews.map(r => {
          const reviewerName = r.reviewer_name;
          const hasName = !!reviewerName;
          const nameHtml = hasName ? `<div class="osm-product-review-card-name">${reviewerName}</div>` : '';
          const userEmail = r.user && r.user.email ? `<div class="osm-product-review-card-email">${r.user.email}</div>` : '';
          const date = new Date(r.created_at).toLocaleDateString('en-PK', { year:'numeric', month:'short', day:'numeric' });
          const comment = r.comment || '';
          const starsHtml = '★'.repeat(r.rating) + '☆'.repeat(5-r.rating);
          return `
            <div class="osm-product-review-card">
              <div class="osm-product-review-card-header">
                <div class="osm-product-review-card-user">
                  ${nameHtml}
                  ${userEmail}
                </div>
                <div class="osm-product-review-card-meta">
                  <div class="osm-product-review-card-stars" style="color: #FFC107;">${starsHtml}</div>
                  <div class="osm-product-review-card-date">${date}</div>
                </div>
              </div>
              <div class="osm-product-review-card-comment">${comment}</div>
            </div>
          `;
        }).join('');

        if (list) list.innerHTML = cards;
      }

      /* Star picker interactive behavior */
      let selectedRating = 0;
      const starPicks = document.querySelectorAll('.pdt-star-pick');
      const ratingInput = document.getElementById('rev-rating');

      starPicks.forEach((btn, idx) => {
        btn.addEventListener('mouseenter', () => {
          starPicks.forEach((b, i) => b.classList.toggle('is-selected', i <= idx));
        });
        btn.addEventListener('mouseleave', () => {
          starPicks.forEach((b, i) => b.classList.toggle('is-selected', i < selectedRating));
        });
        btn.addEventListener('click', () => {
          selectedRating = idx + 1;
          if (ratingInput) ratingInput.value = selectedRating;
          starPicks.forEach((b, i) => b.classList.toggle('is-selected', i < selectedRating));
        });
      });

      /* Submit review form handler */
      if (reviewForm) {
        reviewForm.addEventListener('submit', function (e) {
          e.preventDefault();
          const name = document.getElementById('rev-name') ? document.getElementById('rev-name').value.trim() : '';
          const rating = ratingInput ? parseInt(ratingInput.value) : 0;
          const comment = document.getElementById('rev-body') ? document.getElementById('rev-body').value.trim() : '';
          const msg = document.getElementById('rev-msg');

          if (!comment) {
            if (msg) {
              msg.className = 'osm-product-review-msg osm-product-review-msg--error';
              msg.textContent = 'Please fill in your review.';
              msg.style.display = 'block';
            }
            return;
          }
          if (rating < 1) {
            if (msg) {
              msg.className = 'osm-product-review-msg osm-product-review-msg--error';
              msg.textContent = 'Please select a star rating.';
              msg.style.display = 'block';
            }
            return;
          }

          const csrfEl = document.querySelector('meta[name="csrf-token"]');
          const token = csrfEl ? csrfEl.getAttribute('content') : '';

          if (!reviewSubmitUrl) return;

          fetch(reviewSubmitUrl, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': token,
              'Accept': 'application/json'
            },
            body: JSON.stringify({
              name: name,
              rating: rating,
              comment: comment
            })
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              if (msg) {
                msg.className = 'osm-product-review-msg osm-product-review-msg--success';
                msg.textContent = 'Thank you! Your review has been posted.';
                msg.style.display = 'block';
              }
              
              this.reset();
              selectedRating = 0;
              if (ratingInput) ratingInput.value = 0;
              starPicks.forEach(b => b.classList.remove('is-selected'));
              
              fetchReviews();
            } else {
              if (msg) {
                msg.className = 'osm-product-review-msg osm-product-review-msg--error';
                msg.textContent = data.message || 'Something went wrong. Please try again.';
                msg.style.display = 'block';
              }
            }
            if (msg) setTimeout(() => msg.style.display = 'none', 4000);
          })
          .catch(err => {
            console.error('Error posting review:', err);
            if (msg) {
              msg.className = 'osm-product-review-msg osm-product-review-msg--error';
              msg.textContent = 'Error sending review. Please try again later.';
              msg.style.display = 'block';
              setTimeout(() => msg.style.display = 'none', 4000);
            }
          });
        });
      }

      /* Top rating click to scroll to reviews tab panel */
      const topCount = document.getElementById('top-rating-count');
      if (topCount) {
        topCount.addEventListener('click', () => {
          const reviewsSection = document.querySelector('.osm-product-tabs');
          if (reviewsSection) {
            reviewsSection.scrollIntoView({ behavior: 'smooth' });
            const reviewsTabBtn = document.getElementById('tab-reviews-btn');
            if (reviewsTabBtn) {
              reviewsTabBtn.click();
            }
          }
        });
      }

      fetchReviews();
    });
  </script>
@endpush
