// ========== Mobile Menu ==========
document.addEventListener('DOMContentLoaded', function() {
    const mobileToggle = document.getElementById('mobileToggle');
    const navMenu = document.getElementById('navMenu');
    const mobileOverlay = document.getElementById('mobileOverlay');
    
    if (mobileToggle && navMenu && mobileOverlay) {
        function toggleMenu() {
            navMenu.classList.toggle('active');
            mobileOverlay.classList.toggle('active');
            document.body.style.overflow = navMenu.classList.contains('active') ? 'hidden' : '';
        }
        
        mobileToggle.addEventListener('click', toggleMenu);
        mobileOverlay.addEventListener('click', toggleMenu);
        
        // Close menu on link click
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (navMenu.classList.contains('active')) {
                    toggleMenu();
                }
            });
        });
        
        // Close menu on ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && navMenu.classList.contains('active')) {
                toggleMenu();
            }
        });
    }
});

// ========== Flash Messages Auto-Close ==========
document.addEventListener('DOMContentLoaded', function() {
    const flashMessages = document.querySelectorAll('.flash-message');
    flashMessages.forEach(msg => {
        setTimeout(() => {
            msg.style.transition = 'opacity 0.5s';
            msg.style.opacity = '0';
            setTimeout(() => msg.remove(), 500);
        }, 5000);
    });
});

// ========== Ticket Quantity Selector ==========
document.addEventListener('DOMContentLoaded', function() {
    const decreaseBtn = document.getElementById('decrease-ticket');
    const increaseBtn = document.getElementById('increase-ticket');
    const quantityDisplay = document.getElementById('ticket-quantity');
    const quantityInput = document.getElementById('ticket-quantity-input');
    
    if (decreaseBtn && increaseBtn && quantityDisplay) {
        let quantity = parseInt(quantityDisplay.textContent) || 1;
        
        decreaseBtn.addEventListener('click', function() {
            if (quantity > 1) {
                quantity--;
                quantityDisplay.textContent = quantity;
                if (quantityInput) quantityInput.value = quantity;
            }
        });
        
        increaseBtn.addEventListener('click', function() {
            const max = parseInt(this.dataset.max) || 10;
            if (quantity < max) {
                quantity++;
                quantityDisplay.textContent = quantity;
                if (quantityInput) quantityInput.value = quantity;
            }
        });
    }
});

// ========== Contact Form Validation ==========
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contact-form');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            const name = document.getElementById('name');
            const email = document.getElementById('email');
            const subject = document.getElementById('subject');
            const message = document.getElementById('message');
            
            let isValid = true;
            
            // Validate name
            if (name && name.value.trim().length < 2) {
                showError(name, 'Name must be at least 2 characters');
                isValid = false;
            } else if (name) {
                clearError(name);
            }
            
            // Validate email
            if (email && !isValidEmail(email.value)) {
                showError(email, 'Please enter a valid email address');
                isValid = false;
            } else if (email) {
                clearError(email);
            }
            
            // Validate subject
            if (subject && subject.value.trim().length < 3) {
                showError(subject, 'Subject must be at least 3 characters');
                isValid = false;
            } else if (subject) {
                clearError(subject);
            }
            
            // Validate message
            if (message && message.value.trim().length < 10) {
                showError(message, 'Message must be at least 10 characters');
                isValid = false;
            } else if (message) {
                clearError(message);
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
});

function showError(input, message) {
    const formGroup = input.closest('.form-group');
    if (formGroup) {
        input.classList.add('error');
        let errorEl = formGroup.querySelector('.form-error');
        if (!errorEl) {
            errorEl = document.createElement('div');
            errorEl.className = 'form-error';
            formGroup.appendChild(errorEl);
        }
        errorEl.textContent = message;
    }
}

function clearError(input) {
    const formGroup = input.closest('.form-group');
    if (formGroup) {
        input.classList.remove('error');
        const errorEl = formGroup.querySelector('.form-error');
        if (errorEl) {
            errorEl.remove();
        }
    }
}

function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

// ========== Search Functionality ==========
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');
    let searchTimeout;
    
    if (searchInput && searchResults) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            if (query.length < 2) {
                searchResults.innerHTML = '';
                searchResults.style.display = 'none';
                return;
            }
            
            searchTimeout = setTimeout(() => {
                performSearch(query);
            }, 300);
        });
    }
});

async function performSearch(query) {
    try {
        const response = await fetch(`/api/search.php?q=${encodeURIComponent(query)}`);
        const results = await response.json();
        
        const searchResults = document.getElementById('search-results');
        if (!searchResults) return;
        
        if (results.length === 0) {
            searchResults.innerHTML = '<div class="search-empty">No results found</div>';
        } else {
            let html = '';
            results.forEach(item => {
                html += `
                    <a href="${item.url}" class="search-result-item">
                        <i class="${item.icon}"></i>
                        <div>
                            <div class="search-result-title">${item.title}</div>
                            <div class="search-result-type">${item.type}</div>
                        </div>
                    </a>
                `;
            });
            searchResults.innerHTML = html;
        }
        searchResults.style.display = 'block';
    } catch (error) {
        console.error('Search error:', error);
    }
}

// Close search results on click outside
document.addEventListener('click', function(e) {
    const searchContainer = document.querySelector('.search-container');
    const searchResults = document.getElementById('search-results');
    if (searchContainer && searchResults) {
        if (!searchContainer.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    }
});

// ========== Match Center Live Updates ==========
class MatchCenter {
    constructor() {
        this.updateInterval = null;
        this.init();
    }
    
    init() {
        if (document.getElementById('match-center')) {
            this.fetchLiveMatch();
            this.updateInterval = setInterval(() => this.fetchLiveMatch(), 30000);
        }
    }
    
    async fetchLiveMatch() {
        try {
            const response = await fetch('/api/matches.php?action=live');
            const data = await response.json();
            
            if (data && data.id) {
                this.updateMatchUI(data);
            } else {
                // No live match, show upcoming
                this.showUpcomingMatch();
            }
        } catch (error) {
            console.error('Error fetching match data:', error);
        }
    }
    
    updateMatchUI(match) {
        const scoreEl = document.getElementById('match-score');
        const statusEl = document.getElementById('match-status');
        const homeTeamEl = document.getElementById('home-team');
        const awayTeamEl = document.getElementById('away-team');
        const venueEl = document.getElementById('match-venue');
        
        if (scoreEl) {
            scoreEl.textContent = `${match.home_score || 0} - ${match.away_score || 0}`;
        }
        
        if (statusEl) {
            statusEl.textContent = match.status.charAt(0).toUpperCase() + match.status.slice(1);
            statusEl.className = `match-status status-${match.status}`;
        }
        
        if (homeTeamEl) homeTeamEl.textContent = 'CLEDAN FC';
        if (awayTeamEl) awayTeamEl.textContent = match.opponent;
        if (venueEl) venueEl.textContent = match.venue || 'Farasi Lane';
        
        // Update stats
        this.updateStat('possession-home', match.possession_home);
        this.updateStat('possession-away', match.possession_away);
        this.updateStat('shots-home', match.shots_home);
        this.updateStat('shots-away', match.shots_away);
    }
    
    updateStat(elementId, value) {
        const el = document.getElementById(elementId);
        if (el && value !== null && value !== undefined) {
            el.textContent = value;
        }
    }
    
    showUpcomingMatch() {
        // Show upcoming match placeholder
        // Could fetch the next scheduled match
    }
    
    destroy() {
        if (this.updateInterval) {
            clearInterval(this.updateInterval);
        }
    }
}

// Initialize Match Center
document.addEventListener('DOMContentLoaded', function() {
    const matchCenter = new MatchCenter();
});

// ========== Lazy Loading Images ==========
document.addEventListener('DOMContentLoaded', function() {
    if ('IntersectionObserver' in window) {
        const lazyImages = document.querySelectorAll('img[data-src]');
        
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        lazyImages.forEach(img => imageObserver.observe(img));
    }
});

// ========== Smooth Scrolling ==========
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href === '#') return;
            
            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});