// Course Detail Page JavaScript

// Global variables
let currentCourseId = null;
let currentLectureId = null;
let attachments = {
    images: [],
    pdfs: [],
    voice: null
};
let mediaRecorder = null;
let recordingChunks = [];

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializePage();
});

function initializePage() {
    // Get course ID from URL or data attribute
    const pathParts = window.location.pathname.split('/');
    const courseDetailIndex = pathParts.indexOf('my-course-detail');
    currentCourseId = courseDetailIndex !== -1 ? pathParts[courseDetailIndex + 1] : null;
    
    console.log('URL path:', window.location.pathname);
    console.log('Path parts:', pathParts);
    console.log('Course detail index:', courseDetailIndex);
    console.log('Current course ID:', currentCourseId);

    // Initialize all components
    initializeVideoProtection();
    initializeStarRating();
    initializeAttachments();
    initializeQuestionForm();
    initializeRatingForm();
    initializeLectureNavigation();

    // Load existing ratings and questions
    loadRatings();
    loadQuestions();
    loadQuestionLimits();

    // Auto-refresh questions every 30 seconds to check for new answers
    setInterval(loadQuestions, 30000);
    setInterval(loadQuestionLimits, 30000);

    // Handle dynamic progress bar widths
    const progressBars = document.querySelectorAll('.progress-bar[data-width]');
    progressBars.forEach(bar => {
        const width = bar.getAttribute('data-width');
        if (width) {
            bar.style.width = width + '%';
        }
    });
}

// Video Protection Functions
function initializeVideoProtection() {
    // Apply protection to all video elements
    document.querySelectorAll('iframe, video').forEach(function(mediaElement) {
        // Add protection class
        const container = mediaElement.closest('.ratio') || mediaElement.parentElement;
        if (container) {
            container.classList.add('video-protected');
        }

        // Disable right-click context menu
        mediaElement.addEventListener('contextmenu', function(e) {
            e.preventDefault();
            return false;
        });

        // Apply specific protection to HTML5 video
        if (mediaElement.tagName === 'VIDEO') {
            mediaElement.setAttribute('controlsList', 'nodownload nofullscreen noremoteplayback');
            mediaElement.setAttribute('disablePictureInPicture', 'true');
        }
    });

    // Global keyboard shortcut prevention
    document.addEventListener('keydown', function(e) {
        // Disable F12, Ctrl+Shift+I, Ctrl+U, Ctrl+S
        if (e.key === 'F12' ||
            (e.ctrlKey && e.shiftKey && e.key === 'I') ||
            (e.ctrlKey && e.key === 'u') ||
            (e.ctrlKey && e.key === 's')) {
            e.preventDefault();
            return false;
        }
    });

    // Disable drag and drop on videos
    document.querySelectorAll('iframe, video').forEach(function(element) {
        element.addEventListener('dragstart', function(e) {
            e.preventDefault();
        });
    });
}

// Star Rating Functions
function initializeStarRating() {
    const stars = document.querySelectorAll('.rating-star');
    const ratingInput = document.getElementById('rating-value');

    if (!stars.length || !ratingInput) return;

    stars.forEach(function(star, index) {
        star.addEventListener('mouseenter', function() {
            highlightStars(index + 1);
        });

        star.addEventListener('mouseleave', function() {
            const currentRating = parseInt(ratingInput.value) || 0;
            highlightStars(currentRating);
        });

        star.addEventListener('click', function() {
            const rating = index + 1;
            ratingInput.value = rating;
            highlightStars(rating);
        });
    });
}

function highlightStars(rating) {
    const stars = document.querySelectorAll('.rating-star');
    stars.forEach(function(star, index) {
        if (index < rating) {
            star.classList.remove('far');
            star.classList.add('fas', 'active');
        } else {
            star.classList.remove('fas', 'active');
            star.classList.add('far');
        }
    });
}

// Attachment Functions
function initializeAttachments() {
    // Image upload
    const imageBtn = document.getElementById('attach-image-btn');
    const imageInput = document.getElementById('image-upload');

    if (imageBtn && imageInput) {
        imageBtn.addEventListener('click', () => imageInput.click());
        imageInput.addEventListener('change', handleImageUpload);
    }

    // PDF upload
    const pdfBtn = document.getElementById('attach-pdf-btn');
    const pdfInput = document.getElementById('pdf-upload');

    if (pdfBtn && pdfInput) {
        pdfBtn.addEventListener('click', () => pdfInput.click());
        pdfInput.addEventListener('change', handlePdfUpload);
    }

    // Voice recording
    const startRecordingBtn = document.getElementById('start-recording-btn');
    const stopRecordingBtn = document.getElementById('stop-recording-btn');

    if (startRecordingBtn && stopRecordingBtn) {
        startRecordingBtn.addEventListener('click', startRecording);
        stopRecordingBtn.addEventListener('click', stopRecording);
    }
}

function handleImageUpload(event) {
    const files = Array.from(event.target.files);
    files.forEach(file => {
        if (file.type.startsWith('image/')) {
            attachments.images.push(file);
        }
    });
    updateAttachmentPreview();
}

function handlePdfUpload(event) {
    const files = Array.from(event.target.files);
    files.forEach(file => {
        if (file.type === 'application/pdf') {
            attachments.pdfs.push(file);
        }
    });
    updateAttachmentPreview();
}

async function startRecording() {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        mediaRecorder = new MediaRecorder(stream);
        recordingChunks = [];

        mediaRecorder.ondataavailable = function(event) {
            if (event.data.size > 0) {
                recordingChunks.push(event.data);
            }
        };

        mediaRecorder.onstop = function() {
            const blob = new Blob(recordingChunks, { type: 'audio/wav' });
            attachments.voice = blob;
            updateAttachmentPreview();

            // Stop all tracks
            stream.getTracks().forEach(track => track.stop());
        };

        mediaRecorder.start();

        // Update UI
        document.getElementById('start-recording-btn').classList.add('hidden');
        document.getElementById('stop-recording-btn').classList.remove('d-none');
        document.getElementById('start-recording-btn').classList.add('recording-animation');

    } catch (error) {
        console.error('Error starting recording:', error);
        alert('Could not start recording. Please check your microphone permissions.');
    }
}

function stopRecording() {
    if (mediaRecorder && mediaRecorder.state === 'recording') {
        mediaRecorder.stop();
    }

    // Update UI
    document.getElementById('start-recording-btn').classList.remove('hidden', 'recording-animation');
    document.getElementById('stop-recording-btn').classList.add('d-none');
}

function updateAttachmentPreview() {
    const previewContainer = document.getElementById('attachments-preview');
    const attachmentList = document.getElementById('attachment-list');

    if (!previewContainer || !attachmentList) return;

    // Clear existing previews
    attachmentList.innerHTML = '';

    // Show/hide preview container
    const hasAttachments = attachments.images.length > 0 || attachments.pdfs.length > 0 || attachments.voice;
    if (hasAttachments) {
        previewContainer.classList.remove('d-none');
    } else {
        previewContainer.classList.add('d-none');
        return;
    }

    // Add image previews
    attachments.images.forEach((image, index) => {
        const imgPreview = document.createElement('div');
        imgPreview.className = 'attachment-preview';
        imgPreview.innerHTML = `
            <div class="card card-preview-small">
                <img src="${URL.createObjectURL(image)}" class="card-img-top" alt="Image preview">
                <div class="card-footer p-1">
                    <button type="button" class="btn btn-sm btn-danger w-100 remove-attachment" data-type="image" data-index="${index}">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
        attachmentList.appendChild(imgPreview);
    });

    // Add PDF previews
    attachments.pdfs.forEach((pdf, index) => {
        const pdfPreview = document.createElement('div');
        pdfPreview.className = 'attachment-preview';
        pdfPreview.innerHTML = `
            <div class="card card-preview-small">
                <div class="card-body p-2 text-center">
                    <i class="fas fa-file-pdf fa-2x text-danger"></i>
                    <p class="mb-0 small text-truncate">${pdf.name}</p>
                </div>
                <div class="card-footer p-1">
                    <button type="button" class="btn btn-sm btn-danger w-100 remove-attachment" data-type="pdf" data-index="${index}">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
        attachmentList.appendChild(pdfPreview);
    });

    // Add voice preview
    if (attachments.voice) {
        const voicePreview = document.createElement('div');
        voicePreview.className = 'attachment-preview';
        voicePreview.innerHTML = `
            <div class="card card-preview-medium">
                <div class="card-body p-2">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-microphone text-primary"></i>
                        <audio controls class="audio-controls">
                            <source src="${URL.createObjectURL(attachments.voice)}" type="${attachments.voice.type}">
                        </audio>
                    </div>
                </div>
                <div class="card-footer p-1">
                    <button type="button" class="btn btn-sm btn-danger w-100 remove-attachment" data-type="voice">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
        attachmentList.appendChild(voicePreview);
    }

    // Add event listeners for remove buttons
    attachmentList.querySelectorAll('.remove-attachment').forEach(button => {
        button.addEventListener('click', function() {
            const type = this.dataset.type;
            const index = parseInt(this.dataset.index);

            if (type === 'image') {
                attachments.images.splice(index, 1);
            } else if (type === 'pdf') {
                attachments.pdfs.splice(index, 1);
            } else if (type === 'voice') {
                attachments.voice = null;
            }

            updateAttachmentPreview();
        });
    });
}

// Placeholder functions for other features
function initializeQuestionForm() {
    // Question form initialization
    const submitBtn = document.getElementById('submit-question');
    const questionContent = document.getElementById('question-content');
    const charCount = document.getElementById('char-count');
    
    if (submitBtn) {
        submitBtn.addEventListener('click', submitQuestion);
    }
    
    // Add character counter and validation
    if (questionContent && charCount) {
        questionContent.addEventListener('input', function() {
            const length = this.value.trim().length;
            charCount.textContent = length;
            
            // Update button state
            if (length < 5) {
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50');
            } else {
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50');
            }
        });
        
        // Initial state
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-50');
    }
}

function initializeRatingForm() {
    // Rating form initialization
    const submitBtn = document.getElementById('submit-rating');
    const ratingValue = document.getElementById('rating-value');
    const ratingDisplay = document.getElementById('rating-display');
    
    if (submitBtn) {
        submitBtn.addEventListener('click', submitRating);
        // Initial state - disable button
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-50');
    }
    
    // Update rating display when stars are clicked
    const stars = document.querySelectorAll('.rating-star');
    stars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = this.getAttribute('data-value');
            if (ratingDisplay) {
                ratingDisplay.textContent = rating + ' star' + (rating > 1 ? 's' : '');
            }
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50');
            }
        });
    });
}

function initializeLectureNavigation() {
    // Lecture navigation initialization
    // This would handle lecture switching logic
}

function loadRatings() {
    console.log('Loading ratings for course:', currentCourseId);
    
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const url = '/courses/' + currentCourseId + '/ratings';
    
    fetch(url, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Ratings loaded:', data);
        const container = document.getElementById('ratings-container');
        if (container && data.ratings && data.ratings.length > 0) {
            let html = '';
            data.ratings.forEach(rating => {
                const stars = Array(5).fill(0).map((_, i) => 
                    i < rating.rating ? '<i class="fas fa-star text-warning"></i>' : '<i class="far fa-star text-warning"></i>'
                ).join('');
                html += `
                    <div class="review-item mb-4 p-4 border rounded">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h5 class="mb-0">${rating.user.name || 'Anonymous'}</h5>
                                <div class="stars my-2">${stars}</div>
                            </div>
                            <div class="text-muted">
                                <small>${new Date(rating.created_at).toLocaleDateString()}</small>
                            </div>
                        </div>
                        <p class="review-text mb-0">${rating.comment || '<em class="text-muted">No comment provided</em>'}</p>
                    </div>
                `;
            });
            container.innerHTML = html;
        }
    })
    .catch(error => console.error('Error loading ratings:', error));
}

function loadQuestionLimits() {
    console.log('Loading question limits for course:', currentCourseId);
    
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch('/questions/limits', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            course_id: parseInt(currentCourseId),
            lecture_id: currentLectureId ? parseInt(currentLectureId) : null
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Question limits loaded:', data);
        
        if (data.success && data.limit_info) {
            const limitsDisplay = document.getElementById('question-limits-display');
            const lectureRemaining = document.getElementById('lecture-questions-remaining');
            const courseRemaining = document.getElementById('course-questions-remaining');
            
            if (limitsDisplay && lectureRemaining && courseRemaining) {
                lectureRemaining.textContent = data.limit_info.lecture.remaining;
                courseRemaining.textContent = data.limit_info.course.remaining;
                limitsDisplay.style.display = 'block';
                
                // Update button state based on limits
                const submitBtn = document.getElementById('submit-question');
                if (submitBtn) {
                    if (!data.limit_info.can_ask) {
                        submitBtn.disabled = true;
                        submitBtn.classList.add('opacity-50');
                        
                        // Show error message
                        const errorDiv = document.getElementById('question-error');
                        if (errorDiv) {
                            errorDiv.textContent = data.limit_info.blocking_reason || 'You have reached the question limit.';
                            errorDiv.style.display = 'block';
                        }
                    } else {
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('opacity-50');
                        
                        // Hide error message
                        const errorDiv = document.getElementById('question-error');
                        if (errorDiv) {
                            errorDiv.style.display = 'none';
                        }
                    }
                }
            }
        }
    })
    .catch(error => console.error('Error loading question limits:', error));
}

function loadQuestions() {
    console.log('Loading questions for course:', currentCourseId);
    
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const currentUserId = document.querySelector('meta[name="user-id"]')?.getAttribute('content');
    
    console.log('Current user ID:', currentUserId);
    
    fetch('/questions/get', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            course_id: parseInt(currentCourseId),
            user_id: currentUserId ? parseInt(currentUserId) : null
        })
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Questions loaded:', data);
        console.log('Number of questions:', data.questions ? data.questions.length : 0);
        
        if (data.success) {
            const container = document.getElementById('questions-container');
            const noQuestionsMsg = document.getElementById('no-questions-message');

            if (data.questions.length > 0) {
                // Hide the "no questions" message
                noQuestionsMsg.style.display = 'none';

                // Sort questions by created_at in descending order (newest first)
                data.questions.sort((a, b) => {
                    const dateA = new Date(a.created_at.replace(/(\d+)(st|nd|rd|th)/, "$1"));
                    const dateB = new Date(b.created_at.replace(/(\d+)(st|nd|rd|th)/, "$1"));
                    return dateB - dateA; // Newest first
                });

                // Generate HTML for questions and answers
                let html = '';

                data.questions.forEach(question => {
                    // Create conversation container for this question and its answers
                    html += '<div class="conversation-group mb-4">';

                    // Add status indicator
                    html += `<div class="status-indicator status-${question.status.toLowerCase()}">${formatStatus(question.status)}</div>`;

                    // Add date header
                    const formattedDate = formatDate(question.created_at);
                    html += `<div class="conversation-date">${formattedDate}</div>`;

                    // Prepare attachment previews for question
                    let attachmentsHtml = '';
                    if (question.attachments && question.attachments.length > 0) {
                        attachmentsHtml += '<div class="chat-attachments">';

                        question.attachments.forEach(attachment => {
                            if (attachment.type === 'image') {
                                attachmentsHtml += `
                                    <div class="mb-2">
                                        <a href="${attachment.url}" target="_blank">
                                            <img src="${attachment.url}" class="img-thumbnail" style="max-height: 150px;">
                                        </a>
                                    </div>`;
                            } else if (attachment.type === 'pdf') {
                                attachmentsHtml += `
                                    <div class="mb-2">
                                        <a href="${attachment.url}" target="_blank" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-file-pdf me-1"></i> ${attachment.name}
                                        </a>
                                    </div>`;
                            } else if (attachment.type === 'voice') {
                                attachmentsHtml += `
                                    <div class="mb-2">
                                        <audio controls>
                                            <source src="${attachment.url}" type="${attachment.mime_type || 'audio/webm'}">
                                            Your browser does not support the audio element.
                                        </audio>
                                    </div>`;
                            }
                        });

                        attachmentsHtml += '</div>';
                    }

                    // Create question chat bubble
                    html += `
                    <div class="chat-message question">
                        <div class="chat-meta">
                            <span class="user-name">${question.user || 'Anonymous'}</span>
                            <span class="badge ms-2 bg-${getStatusBadgeColor(question.status)}">${formatStatus(question.status)}</span>
                        </div>
                        <div class="chat-bubble">
                            <p class="mb-0">${question.content}</p>
                            ${attachmentsHtml}
                            <div class="chat-time">${formatTime(question.created_at)}</div>
                        </div>
                    </div>`;

                    // Add answers if any
                    if (question.answers && question.answers.length > 0) {
                        html += renderAnswers(question.answers);
                    }

                    // Close the conversation group
                    html += '</div>';
                });

                container.innerHTML = html;
            } else {
                // Show the "no questions" message
                noQuestionsMsg.style.display = 'block';
            }
        } else {
            console.error('Error loading questions:', data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Helper function to render answers
function renderAnswers(answers) {
    let html = '';

    // Sort answers to show pinned answers first
    answers.sort((a, b) => {
        if (a.is_pinned && !b.is_pinned) return -1;
        if (!a.is_pinned && b.is_pinned) return 1;
        return 0;
    });

    answers.forEach(answer => {
        // Prepare attachments for answers if any
        let attachmentsHtml = '';
        if (answer.attachments && answer.attachments.length > 0) {
            attachmentsHtml += '<div class="chat-attachments">';

            answer.attachments.forEach(attachment => {
                if (attachment.type === 'image') {
                    attachmentsHtml += `
                        <div class="mb-2">
                            <a href="${attachment.url}" target="_blank">
                                <img src="${attachment.url}" class="img-thumbnail" style="max-height: 150px;">
                            </a>
                        </div>`;
                } else if (attachment.type === 'pdf') {
                    attachmentsHtml += `
                        <div class="mb-2">
                            <a href="${attachment.url}" target="_blank" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-file-pdf me-1"></i> ${attachment.name}
                            </a>
                        </div>`;
                } else if (attachment.type === 'voice' || attachment.type === 'audio') {
                    attachmentsHtml += `
                        <div class="mb-2">
                            <audio controls>
                                <source src="${attachment.url}" type="${attachment.mime_type || 'audio/webm'}">
                                Your browser does not support the audio element.
                            </audio>
                        </div>`;
                }
            });

            attachmentsHtml += '</div>';
        }

        // Create answer chat bubble
        html += `
        <div class="chat-message answer ${answer.is_pinned ? 'pinned' : ''}">
            <div class="chat-meta">
                ${answer.is_pinned ? '<span class="badge bg-success me-2"><i class="fas fa-thumbtack me-1"></i> Pinned</span>' : ''}
                <span class="user-name">${answer.user || 'Anonymous'}</span>
            </div>
            <div class="chat-bubble">
                <p class="mb-0">${answer.content}</p>
                ${attachmentsHtml}
                <div class="chat-time">${formatTime(answer.created_at)}</div>
            </div>
        </div>`;
    });

    return html;
}

// Helper function to get badge color based on status
function getStatusBadgeColor(status) {
    switch(status) {
        case 'pending': return 'warning';
        case 'answered': return 'success';
        case 'rejected': return 'danger';
        default: return 'secondary';
    }
}

// Helper function to format status
function formatStatus(status) {
    return status.charAt(0).toUpperCase() + status.slice(1);
}

// Helper function to format date
function formatDate(dateString) {
    // If the date string is already short (like "2 hours ago"), just return it
    if (dateString && dateString.length < 20) {
        return dateString;
    }

    try {
        // Try to parse the date - first remove any ordinal suffixes that might cause issues
        const cleanStr = dateString.replace(/(\d+)(st|nd|rd|th)/, "$1");

        // Create a date object - this works with ISO strings and many other formats
        const date = new Date(cleanStr);

        // Check if the date is valid
        if (isNaN(date.getTime())) {
            // If we can't parse it, return the original string
            return dateString;
        }

        // Format date as MMM d, yyyy
        return date.toLocaleDateString([], {month: 'short', day: '2-digit', year: 'numeric'});
    } catch (e) {
        // In case of any error, return the original string
        return dateString;
    }
}

// Helper function to format time
function formatTime(dateString) {
    // If the date string is already short (like "2 hours ago"), just return it
    if (dateString && dateString.length < 20) {
        return dateString;
    }

    try {
        // Try to parse the date - first remove any ordinal suffixes that might cause issues
        const cleanStr = dateString.replace(/(\d+)(st|nd|rd|th)/, "$1");

        // Create a date object - this works with ISO strings and many other formats
        const date = new Date(cleanStr);

        // Check if the date is valid
        if (isNaN(date.getTime())) {
            // If we can't parse it, return the original string
            return dateString;
        }

        // Format time as HH:MM (using locale settings)
        return date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
    } catch (e) {
        // In case of any error, return the original string
        return dateString;
    }
}

function submitQuestion() {
    console.log('Submit Question clicked');
    
    const questionContent = document.getElementById('question-content');
    const submitBtn = document.getElementById('submit-question');
    
    if (!questionContent) {
        console.error('Question content element not found');
        return;
    }
    
    const content = questionContent.value.trim();
    
    // Check if there's content or attachments
    if (!content && !attachments.images.length && !attachments.pdfs.length && !attachments.voice) {
        alert('Please enter your question or add an attachment');
        return;
    }
    
    // Disable button during submission
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Submitting...';
    
    // Create FormData object for file uploads
    const formData = new FormData();
    formData.append('content', content);
    formData.append('course_id', currentCourseId);
    
    // Only add lecture_id if we're on a lecture page
    if (currentLectureId) {
        formData.append('lecture_id', currentLectureId);
    }
    
    console.log('Submitting question with course_id:', currentCourseId, 'lecture_id:', currentLectureId);
    
    // Add attachments
    attachments.images.forEach((image, index) => {
        formData.append(`images[${index}]`, image);
    });
    
    attachments.pdfs.forEach((pdf, index) => {
        formData.append(`pdfs[${index}]`, pdf);
    });
    
    if (attachments.voice) {
        formData.append('voice', attachments.voice);
    }
    
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Send AJAX request to submit question
    fetch('/questions', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Clear textarea and attachments
            questionContent.value = '';
            attachments = {
                images: [],
                pdfs: [],
                voice: null
            };
            updateAttachmentPreview();
            
            // Show success message
            alert('Your question has been submitted successfully!');
            
            // Reload questions and limits
            loadQuestions();
            loadQuestionLimits();
        } else {
            alert('Error: ' + data.message);
            // Reload limits to show current status
            loadQuestionLimits();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while submitting your question.');
    })
    .finally(() => {
        // Re-enable button
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i> Submit Question';
    });
}

function submitRating() {
    console.log('Submit Rating clicked');
    
    const ratingValue = document.getElementById('rating-value');
    const ratingComment = document.getElementById('rating-comment');
    const submitBtn = document.getElementById('submit-rating');
    
    if (!ratingValue) {
        console.error('Rating value element not found');
        return;
    }
    
    const rating = parseInt(ratingValue.value);
    const comment = ratingComment ? ratingComment.value.trim() : '';
    
    console.log('Rating submission data:', { rating, comment });
    
    if (rating === 0) {
        console.warn('No rating selected');
        alert('Please select a rating before submitting.');
        return;
    }
    
    // Disable button
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Submitting...';
    
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const url = '/courses/' + currentCourseId + '/rate';
    
    console.log('Sending rating to:', url);
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            rating: rating,
            comment: comment
        })
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            console.log('Rating submitted successfully');
            alert(data.message || 'Rating submitted successfully!');
            
            // Update average rating
            const avgRatingEl = document.getElementById('average-rating');
            if (avgRatingEl) {
                avgRatingEl.textContent = parseFloat(data.average_rating).toFixed(1);
            }
            
            // Reset form
            ratingValue.value = 0;
            if (ratingComment) ratingComment.value = '';
            highlightStars(0);
            
            // Reload ratings
            loadRatings();
        } else {
            console.error('Rating submission failed:', data.message);
            alert('Error: ' + (data.message || 'Failed to submit rating'));
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        alert('An error occurred while submitting your rating.');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-star me-2"></i> Submit Rating';
    });
}

// Load and display question limits
function loadQuestionLimits() {
    console.log('Loading question limits for course:', currentCourseId);
    
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch('/questions/limits', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            course_id: parseInt(currentCourseId),
            lecture_id: currentLectureId ? parseInt(currentLectureId) : null
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Question limits loaded:', data);
        
        if (data.success && data.limit_info) {
            displayQuestionLimits(data.limit_info);
        }
    })
    .catch(error => {
        console.error('Error loading question limits:', error);
    });
}

// Display question limits in the UI
function displayQuestionLimits(limitInfo) {
    const limitContainer = document.getElementById('question-limits-display');
    
    if (!limitContainer) {
        console.warn('Question limits display container not found');
        return;
    }
    
    let html = '<div class="question-limits-info">';
    
    // Show course level limits only if flagged to show
    if (limitInfo.show_course_limit && limitInfo.course) {
        const courseLimitClass = limitInfo.course.can_ask ? 'text-success' : 'text-danger';
        html += `
            <div class="limit-item mb-2">
                <strong>Course Questions:</strong>
                <span class="${courseLimitClass}">
                    ${limitInfo.course.remaining}/${limitInfo.course.limit} remaining
                </span>
            </div>
        `;
    }
    
    // Show lecture level limits only if flagged to show and on a lecture page
    if (limitInfo.show_lecture_limit && limitInfo.lecture && currentLectureId) {
        const lectureLimitClass = limitInfo.lecture.can_ask ? 'text-success' : 'text-danger';
        html += `
            <div class="limit-item mb-2">
                <strong>Lecture Questions:</strong>
                <span class="${lectureLimitClass}">
                    ${limitInfo.lecture.remaining}/${limitInfo.lecture.limit} remaining
                </span>
            </div>
        `;
    }
    
    // Show warning if limits reached
    if (!limitInfo.can_ask) {
        html += `
            <div class="alert alert-warning mt-2 mb-0" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                ${limitInfo.blocking_reason}
            </div>
        `;
    }
    
    html += '</div>';
    
    limitContainer.innerHTML = html;
    limitContainer.style.display = 'block';
    
    // Disable submit button if limits reached
    const submitBtn = document.getElementById('submit-question');
    if (submitBtn) {
        submitBtn.disabled = !limitInfo.can_ask;
        if (!limitInfo.can_ask) {
            submitBtn.classList.add('opacity-50');
            submitBtn.title = limitInfo.blocking_reason;
        } else {
            submitBtn.classList.remove('opacity-50');
            submitBtn.title = '';
        }
    }
}
