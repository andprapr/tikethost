// Admin Dashboard JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Initialize form handlers
    initializeFormHandlers();
    
    // Initialize image error handling
    initializeImageErrorHandling();
});

function initializeFormHandlers() {
    // Handle gift form submission
    const giftForm = document.getElementById('giftForm');
    if (giftForm) {
        giftForm.addEventListener('submit', function(e) {
            e.preventDefault();
            submitGiftForm();
        });
    }
}

function initializeImageErrorHandling() {
    // Add error handling for all images
    const images = document.querySelectorAll('img[src*="storage"]');
    images.forEach(img => {
        img.addEventListener('error', function() {
            // Fallback to default image if storage image fails
            if (!this.src.includes('default-gift.png')) {
                this.src = '/images/default-gift.png';
            }
        });
        
        // Add loading placeholder
        img.addEventListener('load', function() {
            this.style.opacity = '1';
        });
        
        img.style.opacity = '0.5';
    });
}

function submitGiftForm() {
    const form = document.getElementById('giftForm');
    const formData = new FormData(form);
    const giftId = document.getElementById('giftId').value;
    const method = document.getElementById('formMethod').value;
    
    let url = '/admin/store-gift';
    if (method === 'PUT' && giftId) {
        url = `/admin/update-gift/${giftId}`;
        formData.append('_method', 'PUT');
    }
    
    // Add CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        formData.append('_token', csrfToken.getAttribute('content'));
    }
    
    // Show loading state
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
    submitBtn.disabled = true;
    
    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showNotification('Hadiah berhasil disimpan!', 'success');
            closeModal();
            // Reload page to show updated data
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showNotification(data.message || 'Terjadi kesalahan', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Terjadi kesalahan saat menyimpan', 'error');
    })
    .finally(() => {
        // Reset button state
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        'bg-blue-500 text-white'
    }`;
    
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas ${
                type === 'success' ? 'fa-check-circle' :
                type === 'error' ? 'fa-exclamation-circle' :
                'fa-info-circle'
            } mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Image preview function
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('imagePreview');
            if (preview) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Enhanced image loading with retry
function loadImageWithRetry(img, maxRetries = 3) {
    let retries = 0;
    
    function attemptLoad() {
        const newImg = new Image();
        newImg.onload = function() {
            img.src = this.src;
            img.style.opacity = '1';
        };
        
        newImg.onerror = function() {
            retries++;
            if (retries < maxRetries) {
                setTimeout(attemptLoad, 1000 * retries); // Exponential backoff
            } else {
                img.src = '/images/default-gift.png';
                img.style.opacity = '1';
            }
        };
        
        newImg.src = img.dataset.src || img.src;
    }
    
    attemptLoad();
}