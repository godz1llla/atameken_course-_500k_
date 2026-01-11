// ============ ANIMATIONS & EFFECTS ============

document.addEventListener('DOMContentLoaded', function() {
    
    // Плавное появление элементов при скролле
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    // Наблюдаем за всеми карточками
    document.querySelectorAll('.course-card, .stat-card, .achievement-card, .certificate-card').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'all 0.6s ease';
        observer.observe(el);
    });
    
    // ============ FORM VALIDATION ============
    
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn && !form.id) {
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.6';
                const originalText = submitBtn.textContent;
                submitBtn.innerHTML = '<span style="display: inline-block; animation: spin 1s linear infinite;">⏳</span> Loading...';
                
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.style.opacity = '1';
                    submitBtn.textContent = originalText;
                }, 5000);
            }
        });
        
        // Живая валидация
        const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (this.value.trim() === '') {
                    this.style.borderColor = '#ef4444';
                    showError(this, 'This field is required');
                } else {
                    this.style.borderColor = '#10b981';
                    hideError(this);
                }
            });
            
            input.addEventListener('input', function() {
                if (this.value.trim() !== '') {
                    this.style.borderColor = '#10b981';
                    hideError(this);
                }
            });
        });
    });
    
    function showError(input, message) {
        hideError(input);
        const error = document.createElement('div');
        error.className = 'input-error';
        error.style.cssText = 'color: #ef4444; font-size: 12px; margin-top: 5px; animation: slideIn 0.3s ease;';
        error.textContent = message;
        input.parentElement.appendChild(error);
    }
    
    function hideError(input) {
        const error = input.parentElement.querySelector('.input-error');
        if (error) error.remove();
    }
    
    // ============ PROGRESS BARS ANIMATION ============
    
    const progressBars = document.querySelectorAll('.progress-fill');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0';
        setTimeout(() => {
            bar.style.width = width;
        }, 300);
    });
    
    // ============ TEST TIMER ============
    
    const testForm = document.querySelector('.test-form');
    if (testForm) {
        const timeLimit = testForm.dataset.timeLimit;
        if (timeLimit) {
            let timeRemaining = parseInt(timeLimit) * 60;
            
            const timerDisplay = document.createElement('div');
            timerDisplay.className = 'timer-display';
            timerDisplay.style.cssText = `
                position: fixed;
                top: 100px;
                right: 30px;
                background: linear-gradient(135deg, #6366f1 0%, #ec4899 100%);
                padding: 20px 30px;
                border-radius: 12px;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
                font-size: 28px;
                font-weight: 800;
                color: white;
                z-index: 999;
                animation: pulse 2s infinite;
            `;
            document.body.appendChild(timerDisplay);
            
            const timer = setInterval(function() {
                timeRemaining--;
                
                const minutes = Math.floor(timeRemaining / 60);
                const seconds = timeRemaining % 60;
                
                timerDisplay.textContent = `⏱ ${minutes}:${seconds.toString().padStart(2, '0')}`;
                
                if (timeRemaining <= 0) {
                    clearInterval(timer);
                    showNotification('Time is up! Submitting test...', 'warning');
                    setTimeout(() => testForm.submit(), 1000);
                }
                
                if (timeRemaining <= 60) {
                    timerDisplay.style.background = 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)';
                    timerDisplay.style.animation = 'shake 0.5s infinite';
                }
            }, 1000);
        }
    }
    
    // ============ MESSAGES AUTO-SCROLL ============
    
    const messagesContainer = document.querySelector('.messages-container');
    if (messagesContainer) {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
        
        // Плавный скролл
        messagesContainer.style.scrollBehavior = 'smooth';
    }
    
    // ============ NOTIFICATIONS ============
    
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = 'notification';
        
        const colors = {
            success: 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
            error: 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)',
            warning: 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
            info: 'linear-gradient(135deg, #6366f1 0%, #4f46e5 100%)'
        };
        
        const icons = {
            success: '✓',
            error: '✗',
            warning: '⚠',
            info: 'ℹ'
        };
        
        notification.style.cssText = `
            position: fixed;
            top: 100px;
            right: 30px;
            background: ${colors[type]};
            color: white;
            padding: 20px 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            z-index: 9999;
            font-weight: 600;
            animation: slideInRight 0.4s ease;
        `;
        
        notification.innerHTML = `<span style="font-size: 20px; margin-right: 10px;">${icons[type]}</span>${message}`;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.4s ease';
            setTimeout(() => notification.remove(), 400);
        }, 3000);
    }
    
    // ============ TOOLTIPS ============
    
    const tooltipElements = document.querySelectorAll('[data-tooltip]');
    tooltipElements.forEach(el => {
        el.style.position = 'relative';
        el.style.cursor = 'help';
        
        el.addEventListener('mouseenter', function() {
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.textContent = this.dataset.tooltip;
            tooltip.style.cssText = `
                position: absolute;
                bottom: 100%;
                left: 50%;
                transform: translateX(-50%);
                background: #1f2937;
                color: white;
                padding: 8px 12px;
                border-radius: 6px;
                font-size: 12px;
                white-space: nowrap;
                margin-bottom: 5px;
                z-index: 1000;
                animation: fadeIn 0.3s ease;
            `;
            this.appendChild(tooltip);
        });
        
        el.addEventListener('mouseleave', function() {
            const tooltip = this.querySelector('.tooltip');
            if (tooltip) tooltip.remove();
        });
    });
    
    // ============ SMOOTH SCROLL ============
    
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // ============ IMAGE LAZY LOADING ============
    
    const images = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                observer.unobserve(img);
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));
    
    // ============ VIDEO EMBED AUTO-RESIZE ============
    
    const videoIframes = document.querySelectorAll('.video-container iframe');
    videoIframes.forEach(iframe => {
        iframe.addEventListener('load', function() {
            console.log('✅ Video loaded successfully');
            showNotification('Video loaded!', 'success');
        });
    });
    
    // ============ CONFIRM DIALOGS ============
    
    const deleteLinks = document.querySelectorAll('a[href*="delete"]');
    deleteLinks.forEach(link => {
        if (!link.hasAttribute('onclick')) {
            link.addEventListener('click', function(e) {
                if (!confirm('Are you sure you want to delete this item?')) {
                    e.preventDefault();
                }
            });
        }
    });
    
    // ============ AUTO-SAVE DRAFT ============
    
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        const key = 'draft_' + window.location.pathname;
        
        // Загрузка сохраненного черновика
        const savedDraft = localStorage.getItem(key);
        if (savedDraft && textarea.value === '') {
            textarea.value = savedDraft;
            showNotification('Draft restored', 'info');
        }
        
        // Автосохранение
        textarea.addEventListener('input', function() {
            localStorage.setItem(key, this.value);
        });
        
        // Очистка при отправке
        textarea.closest('form')?.addEventListener('submit', function() {
            localStorage.removeItem(key);
        });
    });
    
    // ============ KEYBOARD SHORTCUTS ============
    
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + S для сохранения формы
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            const form = document.querySelector('form');
            if (form) {
                form.requestSubmit();
                showNotification('Saving...', 'info');
            }
        }
        
        // ESC для закрытия модалок
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal, .overlay').forEach(el => {
                el.style.display = 'none';
            });
        }
    });
    
    // ============ COURSE CARD HOVER EFFECTS ============
    
    document.querySelectorAll('.course-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // ============ COPY TO CLIPBOARD ============
    
    document.querySelectorAll('[data-copy]').forEach(el => {
        el.addEventListener('click', function() {
            const text = this.dataset.copy;
            navigator.clipboard.writeText(text).then(() => {
                showNotification('Copied to clipboard!', 'success');
            });
        });
    });
    
    // ============ STATS COUNTER ANIMATION ============
    
    const statNumbers = document.querySelectorAll('.stat-number');
    statNumbers.forEach(stat => {
        const target = parseInt(stat.textContent);
        let current = 0;
        const increment = target / 50;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                stat.textContent = target;
                clearInterval(timer);
            } else {
                stat.textContent = Math.floor(current);
            }
        }, 30);
    });
    
    // ============ DARK MODE TOGGLE (BONUS) ============
    
    const darkModeToggle = document.querySelector('[data-dark-mode]');
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', function() {
            document.body.classList.toggle('dark-mode');
            localStorage.setItem('darkMode', document.body.classList.contains('dark-mode'));
        });
        
        if (localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark-mode');
        }
    }
    
});

// ============ CSS ANIMATIONS ============

const style = document.createElement('style');
style.textContent = `
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(100px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes slideOutRight {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(100px);
        }
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
`;
document.head.appendChild(style);

// ============ UTILITY FUNCTIONS ============

// Форматирование даты
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('ru-RU', options);
}

// Debounce для оптимизации
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Проверка видимости элемента
function isElementInViewport(el) {
    const rect = el.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
}

console.log('🚀 LMS System loaded successfully!');
