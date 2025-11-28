<x-customer-dashboard title="Write Review">
<style>
    .review-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .review-header {
        background: linear-gradient(135deg, #872341, #BE3144);
        padding: 40px;
        border-radius: 20px;
        margin-bottom: 32px;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .review-header::before {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        top: -100px;
        right: -100px;
    }

    .review-card {
        background: white;
        border-radius: 20px;
        padding: 32px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        margin-bottom: 24px;
    }

    .appointment-summary {
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 32px;
    }

    .rating-stars {
        display: flex;
        gap: 12px;
        justify-content: center;
        margin: 24px 0;
    }

    .star-input {
        display: none;
    }

    .star-label {
        font-size: 48px;
        color: #e5e7eb;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .star-label:hover,
    .star-label.active,
    .star-input:checked ~ .star-label {
        color: #f59e0b;
        transform: scale(1.2);
    }

    .star-input:checked + .star-label,
    .rating-stars:hover .star-label:hover,
    .rating-stars:hover .star-label:hover ~ .star-label {
        color: #f59e0b;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-label {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 12px;
        display: block;
        font-size: 16px;
    }

    .form-control {
        width: 100%;
        padding: 14px 18px;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 15px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: #872341;
        box-shadow: 0 0 0 3px rgba(135, 35, 65, 0.1);
    }

    textarea.form-control {
        min-height: 150px;
        resize: vertical;
    }

    .btn-submit {
        background: linear-gradient(135deg, #872341, #BE3144);
        color: white;
        padding: 16px 32px;
        border-radius: 12px;
        border: none;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(135, 35, 65, 0.3);
    }

    .btn-cancel {
        background: #f1f5f9;
        color: #64748b;
        padding: 16px 32px;
        border-radius: 12px;
        border: none;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }

    .btn-cancel:hover {
        background: #e2e8f0;
    }

    .rating-value {
        text-align: center;
        font-size: 18px;
        font-weight: 700;
        color: #872341;
        margin-top: 16px;
    }

    .existing-review-alert {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        border-left: 4px solid #f59e0b;
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 24px;
        color: #78350f;
    }
</style>

<div class="review-container">
    <!-- Header -->
    <div class="review-header">
        <div style="position: relative; z-index: 1;">
            <h2 style="font-size: 32px; font-weight: 800; margin-bottom: 8px;color:#fff">
                <i class="bi bi-star-fill me-2"></i>Write a Review
            </h2>
            <p style="font-size: 15px; opacity: 0.9; margin: 0;color:#fff">
                Share your experience with this appointment
            </p>
        </div>
    </div>

    <!-- Existing Review Alert -->
    @if($existingReview)
    <div class="existing-review-alert">
        <div style="display: flex; align-items: center; gap: 12px;">
            <i class="bi bi-info-circle-fill" style="font-size: 24px;"></i>
            <div>
                <div style="font-weight: 700; margin-bottom: 4px;">You've already reviewed this appointment</div>
                <div style="font-size: 14px;">You can update your review below.</div>
            </div>
        </div>
    </div>
    @endif

    <!-- Appointment Summary -->
    <div class="appointment-summary">
        <h5 style="color: #166534; font-weight: 700; margin-bottom: 16px;">
            <i class="bi bi-calendar-check-fill me-2"></i>Appointment Details
        </h5>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
            <div>
                <div style="color: #15803d; font-size: 13px; margin-bottom: 4px;">Date</div>
                <div style="color: #166534; font-weight: 600;">
                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}
                </div>
            </div>
            <div>
                <div style="color: #15803d; font-size: 13px; margin-bottom: 4px;">Time</div>
                <div style="color: #166534; font-weight: 600;">
                    {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }}
                </div>
            </div>
            <div>
                <div style="color: #15803d; font-size: 13px; margin-bottom: 4px;">Salon</div>
                <div style="color: #166534; font-weight: 600;">
                    {{ $appointment->salon->salon_name }}
                </div>
            </div>
            <div>
                <div style="color: #15803d; font-size: 13px; margin-bottom: 4px;">Provider</div>
                <div style="color: #166534; font-weight: 600;">
                    {{ $appointment->provider->name }}
                </div>
            </div>
        </div>

        <div style="margin-top: 16px; padding-top: 16px; border-top: 2px solid #bbf7d0;">
            <div style="color: #15803d; font-size: 13px; margin-bottom: 8px;">Services</div>
            <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                @foreach($appointment->services as $service)
                <span style="background: white; padding: 6px 12px; border-radius: 8px; color: #166534; font-weight: 600; font-size: 14px;">
                    {{ $service->name }}
                </span>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Review Form -->
    <div class="review-card">
        <form action="{{ route('customer.review.store', $appointment) }}" method="POST">
            @csrf

            <!-- Rating -->
            <div class="form-group">
                <label class="form-label">
                    <i class="bi bi-star-fill me-2" style="color: #f59e0b;"></i>Your Rating
                </label>
                
                <div class="rating-stars" id="ratingStars" style="flex-direction: row-reverse;">
                    <input type="radio" name="rating" value="5" id="star5" class="star-input" {{ old('rating', $existingReview->rating ?? '') == 5 ? 'checked' : '' }} required>
                    <label for="star5" class="star-label" data-value="5">
                        <i class="bi bi-star-fill"></i>
                    </label>

                    <input type="radio" name="rating" value="4" id="star4" class="star-input" {{ old('rating', $existingReview->rating ?? '') == 4 ? 'checked' : '' }}>
                    <label for="star4" class="star-label" data-value="4">
                        <i class="bi bi-star-fill"></i>
                    </label>

                    <input type="radio" name="rating" value="3" id="star3" class="star-input" {{ old('rating', $existingReview->rating ?? '') == 3 ? 'checked' : '' }}>
                    <label for="star3" class="star-label" data-value="3">
                        <i class="bi bi-star-fill"></i>
                    </label>

                    <input type="radio" name="rating" value="2" id="star2" class="star-input" {{ old('rating', $existingReview->rating ?? '') == 2 ? 'checked' : '' }}>
                    <label for="star2" class="star-label" data-value="2">
                        <i class="bi bi-star-fill"></i>
                    </label>

                    <input type="radio" name="rating" value="1" id="star1" class="star-input" {{ old('rating', $existingReview->rating ?? '') == 1 ? 'checked' : '' }}>
                    <label for="star1" class="star-label" data-value="1">
                        <i class="bi bi-star-fill"></i>
                    </label>
                </div>

                <div class="rating-value" id="ratingValue">
                    @if(old('rating', $existingReview->rating ?? ''))
                        {{ old('rating', $existingReview->rating ?? '') }} out of 5 stars
                    @else
                        Select a rating
                    @endif
                </div>

                @error('rating')
                    <div style="color: #dc2626; font-size: 14px; margin-top: 8px;">
                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Comment -->
            <div class="form-group">
                <label class="form-label" for="comment">
                    <i class="bi bi-chat-left-text me-2" style="color: #872341;"></i>Your Review (Optional)
                </label>
                <textarea 
                    name="comment" 
                    id="comment" 
                    class="form-control" 
                    placeholder="Tell us about your experience..."
                    maxlength="1000">{{ old('comment', $existingReview->comment ?? '') }}</textarea>
                
                <div style="text-align: right; color: #64748b; font-size: 13px; margin-top: 8px;">
                    <span id="charCount">{{ strlen(old('comment', $existingReview->comment ?? '')) }}</span>/1000 characters
                </div>

                @error('comment')
                    <div style="color: #dc2626; font-size: 14px; margin-top: 8px;">
                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 32px;">
                <a href="{{ route('customer.booking.details', $appointment) }}" class="btn-cancel">
                    <i class="bi bi-arrow-left me-2"></i>Cancel
                </a>
                <button type="submit" class="btn-submit">
                    <i class="bi bi-send-fill me-2"></i>
                    {{ $existingReview ? 'Update Review' : 'Submit Review' }}
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Star rating functionality
    const stars = document.querySelectorAll('.star-label');
    const ratingValue = document.getElementById('ratingValue');
    const ratingInputs = document.querySelectorAll('.star-input');

    // Initialize active stars based on existing rating
    function updateStars(rating) {
        stars.forEach((star, index) => {
            const starValue = 5 - index;
            if (starValue <= rating) {
                star.classList.add('active');
            } else {
                star.classList.remove('active');
            }
        });
    }

    // Check if there's an initial rating
    ratingInputs.forEach(input => {
        if (input.checked) {
            updateStars(parseInt(input.value));
        }
    });

    stars.forEach((star, index) => {
        star.addEventListener('click', function() {
            const rating = this.getAttribute('data-value');
            ratingValue.textContent = rating + ' out of 5 stars';
            updateStars(parseInt(rating));
        });

        star.addEventListener('mouseenter', function() {
            const rating = this.getAttribute('data-value');
            updateStars(parseInt(rating));
        });
    });

    document.getElementById('ratingStars').addEventListener('mouseleave', function() {
        const checkedInput = document.querySelector('.star-input:checked');
        if (checkedInput) {
            updateStars(parseInt(checkedInput.value));
        } else {
            stars.forEach(star => star.classList.remove('active'));
        }
    });

    // Character counter
    const commentTextarea = document.getElementById('comment');
    const charCount = document.getElementById('charCount');

    commentTextarea.addEventListener('input', function() {
        charCount.textContent = this.value.length;
    });
</script>
</x-customer-dashboard>
